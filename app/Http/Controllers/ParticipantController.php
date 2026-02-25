<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParticipantRequest $request)
    {
        // DB::transaction(function () use ($participant, $chapter) {

        //     $participantChapter = $participant->participantChapters()->create([
        //         'chapter_id' => $chapter->id,
        //         'starting_date' => now(),
        //     ]);

        //     // curricula生成
        //     foreach ($chapter->curricula as $curriculum) {
        //         $participantChapter->participantCurricula()->create([
        //             'curriculum_id' => $curriculum->id,
        //         ]);
        //     }

        //     // 最初のcurriculumをcurrentにセット
        //     $first = $participantChapter->participantCurricula()
        //         ->join('curricula', 'participant_curricula.curriculum_id', '=', 'curricula.id')
        //         ->orderBy('curricula.curriculum_number')
        //         ->select('participant_curricula.*')
        //         ->first();

        //     $participantChapter->update([
        //         'current_participant_curriculum_id' => $first?->id,
        //     ]);
        // });
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParticipantRequest $request, Participant $participant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        //
    }
}
