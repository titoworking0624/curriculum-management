<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * 受講者コントローラー
 */
class ParticipantController extends Controller
{
    /**
     * 受講者一覧画面
     */
    public function index()
    {
        // サブクエリ(後でスコープ化)
        $sub = DB::table('participants as p')
                    ->leftjoin('participant_chapters as pch', 'p.id', '=','pch.participant_id')
                    ->leftjoin('participant_curricula as pcu','pch.id','=','pcu.participant_chapter_id')
                    ->latest('pcu.starting_date')
                    ->distinct('p.name')
                    ->selectRaw('
                        p.id,
                        p.name,
                        pcu.curriculum_id as curriculum_id,
                        pcu.starting_date as starting_date,
                        pcu.completion_date as completion_date,
                        pch.chapter_id as chapter_id,
                        ROW_NUMBER() OVER (
                            PARTITION BY p.id
                            ORDER BY pcu.curriculum_id DESC
                        ) as rn
                    '); // 課題作成順に番号を割り振る

        //
        $participants = DB::query()
            ->fromSub($sub, 't')
            ->where('t.rn', 1) // 課題作成順の最新を取得
            ->leftJoin('chapters as ch', 'ch.id', '=', 'chapter_id')
            ->leftJoin('curricula as c', 'c.id', '=', 'curriculum_id')
            ->select([
                't.id as pa_id', // 受講者ID
                't.name as pa_name', // 受講者名
                'c.curriculum_code as cu_code', // カリキュラムコード
                'c.name as cu_name', // カリキュラム名
                'ch.name as ch_name', // 章(チャプター)名
                't.starting_date as st_date', // 課題送信日
                't.completion_date as co_date', // 課題完了日
            ])
            ->latest('st_date')
            ->get();

        $starting = $participants->whereNull('co_date')->whereNotNull('st_date'); // 現在の課題がある受講者一覧
        $stoped = $participants->whereNotNull('co_date'); // 現在の課題が存在しない受講者一覧
        $notRegistered = $participants->whereNull('co.date')->whereNull('st_date'); // 課題が未開始の受講者一覧

        return Inertia::render('Participant/Index',[
            'participants' => $participants,
            'starting' => $starting,
            'stoped' => $stoped,
            'notRegistered' => $notRegistered,
        ]);
    }

    /**
     * 受講者登録画面
     */
    public function create()
    {
        $courses = Course::with('chapters')->get();

        return Inertia::render('Participant/Create',[
            'courses' => $courses
        ]);
    }

    /**
     * 受講者登録処理
     */
    public function store(StoreParticipantRequest $request)
    {
        // dd($request);
        DB::transaction(function () use ($request) {
            // 受講者を登録
            $participant = Participant::create([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // チャプター一覧
            $chapters = $request->chapters;

            $chapterOrder = 1; // チャプター順の初期値
            // チャプターごとに
            foreach($chapters as $chapter){
                // dd($chapter['id']);
                // チャプターを受講者に登録する
                $participantChapter = $participant->participantChapters()->create([
                    'chapter_id' => $chapter['id'], // チャプターID
                    'chapter_order' => $chapterOrder, // チャプター順
                    // 'starting_date' => $chapterOrder === 1 ? now() : null, // チャプター順が1番目を開始させる
                ]);
                // カリキュラム生成
                // if($chapterOrder === 1){
                //     // dd($chapterOrder);
                //     $participantChapter->syncCurricula(1);
                //     // dd($participantChapter->participantCurricula());
                // }
                $chapterOrder++;
            }

        });

        return to_route('index');
    }

    /**
     * 受講者課題確認画面
     */
    public function show(Participant $participant)
    {
        // 現在のカリキュラムを取得
        $curriculum = $participant->currentCurriculum()?->curriculum;

        // 次のカリキュラムを取得
        $nextCurriculum = $participant?->nextCurrentCurriculum();
        // dd($nextCurriculum);

        // 前回の（直近で完了した）カリキュラムを取得
        $prevCurriculum = $participant->prevCurriculum()?->curriculum;

        return Inertia::render('Participant/Show',[
            'participant' => $participant,
            'curriculum' => $curriculum,
            'nextCurriculum' => $nextCurriculum,
            'prevCurriculum' => $prevCurriculum,
        ]);
    }

    /**
     * 受講者編集画面
     */
    public function edit(Participant $participant)
    {
        // コース一覧
        $courses = Course::with('chapters')->get();


        // Eagerロード
        $participant->load([
            'participantCurricula' => function ($q) {
                $q->latest('starting_date')
                    ->latest('id');
            },
            'participantCurricula.curriculum',
            'participantCurricula.participantChapter.chapter',
            'participantChapters.chapter.course'
        ]);

        // // 開始済みのチャプターID一覧取得
        // $startedChapterIds = $participant
        //     ->participantChapters()
        //     ->whereNotNull('starting_date')
        //     ->pluck('chapter_id');

        $currentCurriculum = $participant->currentCurriculum()?->load([
            'curriculum',
            'participantChapter.chapter',
        ]);
        // 前回の（直近で完了した）カリキュラムを取得
        $prevCurriculum = $participant->prevCurriculum()?->load([
            'curriculum',
            'participantChapter.chapter',
        ]);
        // 次の課題取得
        $nextCurriculum = $participant->nextCurrentCurriculum()?->load([
            'chapter.course',
        ]);

        return Inertia::render('Participant/Edit', [
            'courses' => $courses,
            'participant' => $participant,
            // 'startedChapterIds' => $startedChapterIds,
            'currentCurriculum' => $currentCurriculum,
            'nextCurriculum' => $nextCurriculum,
            'prevCurriculum' => $prevCurriculum,
        ]);
    }

    /**
     * 受講者編集処理
     */
    public function update(UpdateParticipantRequest $request, Participant $participant)
    {
        // dd($request);
        // 2026-03-18 カリキュラム開始日完了日編集入れてもいいかも
        DB::transaction(function () use ($participant, $request) {

            // 登録されているチャプターID一覧
            $current = $participant->participantChapters()
                ->pluck('chapter_id')
                ->toArray();

            // 更新するチャプターID一覧(リクエスト)
            $new = $request->chapters;

            // $add = array_diff($new, $current); // 追加するチャプターID一覧
            $delete = array_diff($current, $new); // 削除するチャプターID一覧

            // 削除するチャプターがある場合
            if ($delete) {
                $participant->participantChapters()
                ->whereIn('chapter_id', $delete)
                ->delete();
                }

            // チャプター順を追加・更新
            $this->updateOrder($request,$participant->id);

            // if(!$participant->currentCurriculum()){
            //     $participant->startCurriculum();
            // }
        });

        return redirect()->route('participants.edit', $participant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        //
    }
    private function updateOrder(UpdateParticipantRequest $request, int $participantId)
    {
        // dd($request);
        // チャプター一覧
        $chapters = $request->chapters;

        DB::transaction(function () use ($chapters, $participantId) {

            $cases = [];
            $ids = [];

            foreach ($chapters as $index => $chapter) {

                // dd($index,$chapter);
                $id = $chapter; // チャプターID
                $order = $index + 1; // チャプター順

                $cases[] = "WHEN {$id} THEN {$order}";
                $ids[] = $id;
            }

            $ids = implode(',', $ids);
            $cases = implode(' ', $cases);

            // dd($ids,$cases);
            // 登録チャプターのチャプター順を追加更新
            DB::update("
                UPDATE participant_chapters
                SET chapter_order = CASE chapter_id
                    {$cases}
                END
                WHERE participant_id = ?
                AND chapter_id IN ({$ids})
            ", [$participantId]);
        });

        // return back();
    }
}
