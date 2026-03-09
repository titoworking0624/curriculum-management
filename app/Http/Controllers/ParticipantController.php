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
        $sub = DB::table('participant_curricula as pc')
            ->join('participant_chapters as pch', 'pc.participant_chapter_id', '=', 'pch.id')
            ->join('curricula as c', 'pc.curriculum_id', '=', 'c.id')
            ->whereNull('pc.completion_date')
            ->whereNull('pch.completion_date')
            ->whereNotNull('pch.starting_date')
            ->selectRaw('
        pch.participant_id,
        pc.starting_date,
        c.curriculum_code as curriculum_code,
        c.name as curriculum_name,
        ROW_NUMBER() OVER (
            PARTITION BY pch.participant_id
            ORDER BY c.curriculum_number
        ) as rn
    ');

        $participants = DB::query()
            ->fromSub($sub, 't')
            ->where('t.rn', 1)
            ->rightJoin('participants as p', 'p.id', '=', 't.participant_id')
            ->select([
                'p.id as pa_id',
                'p.name as pa_name',
                't.curriculum_code as cu_code',
                't.curriculum_name as cu_name',
                't.starting_date as st_date',
            ])
            ->orderBy('p.name')
            ->get();

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
        $curriculum = $participant->currentCurriculum()->curriculum;
        // dd($participant->currentCurriculum());
        // dd($curriculum->checklist);

        return Inertia::render('Participant/Show',[
            'participant' => $participant,
            'curriculum' => $curriculum,
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
        // $participantCurricula = $participantChapters->participantCurricula()->get();

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
