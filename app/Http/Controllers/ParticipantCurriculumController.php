<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Participant;
use App\Models\ParticipantChapter;
use App\Models\ParticipantCurriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 受講者課題コントローラー
 */

class ParticipantCurriculumController extends Controller
{
    /**
     * 課題完了処理
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

            if(!$next){
                // 次の curriculum がない = chapter 完了
                $this->completeChapter($current->participant_chapter_id);
            }
            // if ($next) {

                // ParticipantCurriculum::create([
                //     'participant_chapter_id' => $current->participant_chapter_id,
                //     'curriculum_id' => $next->id,
                //     'starting_date' => now()
                // ]);
            // }else{
            //     // 次の curriculum がない = chapter 完了
            //     $this->completeChapter($current->participant_chapter_id);
            // }

            return response()->json(['success' => true]);
        });
    }
    /**
     * 章(チャプター)完了処理
     */
    private function completeChapter($participantChapterId)
    {
        $participantChapter = ParticipantChapter::find($participantChapterId);

        $participantChapter->update([
            'completion_date' => now()
        ]);

        // 次の chapter
        $nextChapter = ParticipantChapter::where('participant_id', $participantChapter->participant_id)
            ->where('chapter_order', $participantChapter->chapter_order + 1)
            ->first();

        dd($nextChapter);
        if (!$nextChapter) {
            return; // 全course完了
        }

        // 次の chapter 開始
        $nextChapter->update([
            'starting_date' => now()
        ]);

        // // その chapter の curriculum1開始
        // $firstCurriculum = Curriculum::where('chapter_id', $nextChapter->chapter_id)
        //     ->where('curriculum_number', 1)
        //     ->first();

        // // id(!$firstCurriculum){
        // //     retr
        // // }
        // ParticipantCurriculum::create([
        //     'participant_chapter_id' => $nextChapter->id,
        //     'curriculum_id' => $firstCurriculum->id,
        //     'starting_date' => now()
        // ]);
    }
    /**
     * 課題完了キャンセル処理
     */
    public function cancelComplete(Participant $participant)
    {
        DB::transaction(function () use ($participant) {

            $current = $participant->currentCurriculum();

            // 直前に完了した curriculum
            $prev = $participant->prevCurriculum();

            if(!$prev){
                return;
            }

            // 次の curriculum が作られている場合は削除
            if($current){
                $current->delete();
            }

            // curriculum 完了を取り消し
            $prev->update([
                'completion_date' => null
            ]);

            // chapter が完了していた場合は戻す
            $chapter = $prev->participantChapter;

            if ($chapter->completion_date) {
                $chapter->update([
                    'completion_date' => null
                ]);
            }

            });
        return response()->json(['success' => true]);
    }
    /**
     * 課題スタート処理
     */
    public function startCurriculum(Participant $participant)
    {
        DB::transaction(function() use ($participant){

            if($participant->isLastCurriculum()){
                $participant->startCurriculum();
            }else{
                $prev = $participant->prevCurriculum();

                $next = Curriculum::where('chapter_id', $prev->curriculum->chapter_id)
                    ->where('curriculum_number', $prev->curriculum->curriculum_number + 1)
                    ->first();

                ParticipantCurriculum::create([
                    'participant_chapter_id' => $prev->participant_chapter_id,
                    'curriculum_id' => $next->id,
                    'starting_date' => now()
                ]);
            }
        });
        return redirect()->back();
    }
}
