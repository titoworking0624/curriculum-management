<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Models\Course;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sub = DB::table('participants as p')
                    ->join('participant_chapters as pch', 'p.id', '=','pch.participant_id')
                    ->join('participant_curricula as pcu','pch.id','=','pcu.participant_chapter_id')
                    ->latest('pcu.starting_date')
                    ->distinct('p.name')
                    ->selectRaw('
                        p.id,
                        p.name,
                        pcu.curriculum_id as curriculum_id,
                        pcu.starting_date as starting_date,
                        pcu.completion_date as completion_date,
                        ROW_NUMBER() OVER (
                            PARTITION BY p.id
                            ORDER BY pcu.starting_date DESC
                        ) as rn
                    ');

            $participants = DB::query()
                ->fromSub($sub, 't')
                ->where('t.rn', 1)
                ->rightJoin('curricula as c', 'c.id', '=', 'curriculum_id')
                ->select([
                    't.id as pa_id',
                    't.name as pa_name',
                    'c.curriculum_code as cu_code',
                    'c.name as cu_name',
                    't.starting_date as st_date',
                    't.completion_date as co_date',
                ])
                ->latest('st_date')
                ->get();

        //     $sub = DB::table('participant_curricula as pc')
        //         ->join('participant_chapters as pch', 'pc.participant_chapter_id', '=', 'pch.id')
        //         ->join('curricula as c', 'pc.curriculum_id', '=', 'c.id')
        //         ->whereNull('pc.completion_date')
        //         ->whereNull('pch.completion_date')
        //         ->whereNotNull('pch.starting_date')
        //         ->selectRaw('
        //     pch.participant_id,
        //     pc.starting_date,
        //     c.curriculum_code as curriculum_code,
        //     c.name as curriculum_name,
        //     ROW_NUMBER() OVER (
        //         PARTITION BY pch.participant_id
        //         ORDER BY c.curriculum_number
        //     ) as rn
        // ')
        //     $participants = DB::query()
        //         ->fromSub($sub, 't')
        //         ->where('t.rn', 1)
        //         ->rightJoin('participants as p', 'p.id', '=', 't.participant_id')
        //         ->select([
        //             'p.id as pa_id',
        //             'p.name as pa_name',
        //             't.curriculum_code as cu_code',
        //             't.curriculum_name as cu_name',
        //             't.starting_date as st_date',
        //         ])
        //         ->orderBy('p.name')
        //         ->get();

        return Inertia::render('Participant/Index',[
            'participants' => $participants,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::with('chapters')->get();

        return Inertia::render('Participant/Create',[
            'courses' => $courses
        ]);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        $curriculum = $participant->currentCurriculum()?->curriculum;

        $nextCurriculum = $participant?->nextCurrentCurriculum();

        $prevCurriculum = $participant->prevCurriculum()?->curriculum;

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
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
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

            // 追加
            foreach ($add as $chapterId) {
                $participant->participantChapters()->create([
                    'chapter_id' => $chapterId,
                    'chapter_order' => 0
                ]);
            }

            // 削除
            if ($delete) {
                $participant->participantChapters()
                    ->whereIn('chapter_id', $delete)
                    ->delete();
            }
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
