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
                    ');

        //
        $participants = DB::query()
            ->fromSub($sub, 't')
            ->where('t.rn', 1)
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

        $starting = $participants->whereNull('co_date')->whereNotNull('st_date');
        $stoped = $participants->whereNotNull('co_date');
        $notRegistered = $participants->whereNull('co.date')->whereNull('st_date');

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
            $participant = Participant::create([
                'name' => $request->name
            ]);

            $chapters = $request->chapters;

            // dd($chapters);
            $chapterOrder = 1;
            foreach($chapters as $chapter){
                // dd($chapter['id']);
                $participantChapter = $participant->participantChapters()->create([
                    'chapter_id' => $chapter['id'],
                    'chapter_order' => $chapterOrder,
                    'starting_date' => $chapterOrder === 1 ? now() : null,
                ]);
                // curricula生成
                if($chapterOrder === 1){
                    // dd($chapterOrder);
                    $participantChapter->syncCurricula(1);
                    // dd($participantChapter->participantCurricula());
                }
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
        $curriculum = $participant->currentCurriculum()?->curriculum;

        $nextCurriculum = $participant?->nextCurrentCurriculum();
        // dd($nextCurriculum);

        $prevCurriculum = $participant->prevCurriculum()?->curriculum;

        // if(!$nextCurriculum){
        //     $nextChapter = $participant?->participantChapters()->orderBy('chapter_order')->first();
        //     if($nextChapter){
        //         $nextCurriculum = Curriculum::where('chapter_id', $nextChapter->chapter_id)
        //             ->where('curriculum_number', 1)
        //             ->first();
        //     }
        // }

        // if(!$curriculum){

        // }
        // dd($participant->currentCurriculum());
        // dd($curriculum->checklist);

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
        $courses = Course::with('chapters')->get();

        $participantChapters = $participant->participantChapters()
            ->with('chapter.course','participantCurricula.curriculum')
            ->get()
            ->flatMap(function ($pc) {
                return $pc->participantCurricula->map(function($pcur) use ($pc){
                    return [
                        'chapter' => $pc->chapter,
                        'curriculum' => $pcur->curriculum,
                        'courseName' => $pc->chapter->course->name,
                        'starting_date' => $pcur->starting_date,
                        'completion_date' => $pcur->completion_date,
                    ];
                });
            })
            ;
        // $participantCurricula = $participant->participantCurricula()
        //     ->with('participantChapter.chapter.course','curriculum')
        //     ->get()
        //     ->map(fn ($pc) => [
        //             'chapter' => $pc->participantChapter->chapter,
        //             'curriculum' => $pc->curriculum,
        //             'courseName' => $pc->participantChapter->chapter->course->name,
        //             'starting_date' => $pc->starting_date,
        //             'completion_date' => $pc->completion_date,
        //     ]);

        $participant->load([
            'participantCurricula.curriculum',
            'participantCurricula.participantChapter.chapter',
            'participantChapters.chapter.course'
        ]);

        return Inertia::render('Participant/Edit', [
            'courses' => $courses,
            'participant' => $participant,
            'participantChapters' => $participantChapters,
            // 'curricula' => $participantcurricula,
        ]);
    }

    /**
     * 受講者編集処理
     */
    public function update(UpdateParticipantRequest $request, Participant $participant)
    {
        // dd($request);
        DB::transaction(function () use ($participant, $request) {

            $current = $participant->participantChapters()
                ->pluck('chapter_id')
                ->toArray();

            $new = $request->chapters;

            $add = array_diff($new, $current);
            $delete = array_diff($current, $new);


            $chapterOrder = $participant->participantChapters()->latest('chapter_order')->first()->chapter_order ?? 0;
            // dd($chapterOrder);

            // 追加
            foreach ($add as $chapterId) {
                $participant->participantChapters()->create([
                    'chapter_id' => $chapterId,
                    'chapter_order' => $chapterOrder + 1
                ]);
                $chapterOrder++;
            }

            // 削除
            if ($delete) {
                $participant->participantChapters()
                    ->whereIn('chapter_id', $delete)
                    ->delete();
            }
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

}
