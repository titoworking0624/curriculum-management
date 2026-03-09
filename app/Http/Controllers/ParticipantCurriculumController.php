<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Participant;
use App\Models\ParticipantChapter;
use App\Models\ParticipantCurriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantCurriculumController extends Controller
{
    /**
     * 次の課題選択
     */
    public function complete(Participant $participant)
    {
        // dd($participant);
        DB::transaction(function () use($participant) {

            $current = $participant->currentCurriculum();

            if (!$current) {
                return response()->json(['message' => 'No curriculum'], 404);
            }

            $current->update([
                'completion_date' => now()
                ]);

            $next = Curriculum::where('chapter_id', $current->curriculum->chapter_id)
                ->where('curriculum_number', $current->curriculum->curriculum_number + 1)
                ->first();

            if ($next) {

                ParticipantCurriculum::create([
                    'participant_chapter_id' => $current->participant_chapter_id,
                    'curriculum_id' => $next->id,
                    'starting_date' => now()
                ]);
            }else{
                // 次の curriculum がない = chapter 完了
                $this->completeChapter($current->participant_chapter_id);
            }

            return response()->json(['success' => true]);
        });
    }
    public function completeChapter($participantChapterId)
    {
        $participantChapter = ParticipantChapter::find($participantChapterId);

        $participantChapter->update([
            'completion_date' => now()
        ]);

        // 次の chapter
        $nextChapter = ParticipantChapter::where('participant_id', $participantChapter->participant_id)
            ->where('chapter_order', $participantChapter->chapter_order + 1)
            ->first();

        if (!$nextChapter) {
            return; // 全course完了
        }

        // 次の chapter 開始
        $nextChapter->update([
            'starting_date' => now()
        ]);

        // その chapter の curriculum1開始
        $firstCurriculum = Curriculum::where('chapter_id', $nextChapter->chapter_id)
            ->where('curriculum_number', 1)
            ->first();

        ParticipantCurriculum::create([
            'participant_chapter_id' => $nextChapter->id,
            'curriculum_id' => $firstCurriculum->id,
            'starting_date' => now()
        ]);
    }
}
