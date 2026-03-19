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

            // 現在の課題を取得
            $current = $participant->currentCurriculum();

            if (!$current) {
                return response()->json(['message' => 'No curriculum'], 404);
            }

            // 現在の課題を完了させる（完了日を登録）
            $current->update([
                'completion_date' => now()
                ]);

            // チャプター内に次のカリキュラムがあるか確認（isLastCurriculumと同じなので後で合わせる）
            $next = Curriculum::where('chapter_id', $current->curriculum->chapter_id)
                ->where('curriculum_number', $current->curriculum->curriculum_number + 1)
                ->first();

            if(!$next){
                // 次のカリキュラムがない = チャプター完了
                $this->completeChapter($current->participant_chapter_id);
            }
            // if ($next) {

                // ParticipantCurriculum::create([
                //     'participant_chapter_id' => $current->participant_chapter_id,
                //     'curriculum_id' => $next->id,
                //     'starting_date' => now()
                // ]);
            // }else{
            //     // 次のカリキュラムがない = chapter 完了
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
        // 登録チャプターを取得
        $participantChapter = ParticipantChapter::find($participantChapterId);

        // 登録チャプターを完了させる（完了日を登録）
        $participantChapter->update([
            'completion_date' => now()
        ]);

        // 次のチャプター
        $nextChapter = ParticipantChapter::where('participant_id', $participantChapter->participant_id)
            ->where('chapter_order', $participantChapter->chapter_order + 1)
            ->first();

        // dd($nextChapter);
        if (!$nextChapter) {
            return; // 全course完了
        }

        // // 次の chapter 開始
        // $nextChapter->update([
        //     'starting_date' => now()
        // ]);

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

            // 現在の課題を取得
            $current = $participant->currentCurriculum();

            // 直前に完了したカリキュラム
            $prev = $participant->prevCurriculum();

            if(!$prev){
                return;
            }

            // 課題が作られている場合は削除
            if($current){
                $current->delete();
            }

            // 課題完了を取り消し
            $prev->update([
                'completion_date' => null
            ]);

            // チャプターが完了していた場合は戻す
            $chapter = $prev->participantChapter;

            if ($chapter->completion_date) {
                $chapter->update([
                    'completion_date' => null
                ]);
            }

            });
        return redirect()->back();
    }
    /**
     * 課題スタート処理
     */
    public function startCurriculum(Participant $participant)
    {
        DB::transaction(function() use ($participant){

            // 登録チャプター内に未開始のカリキュラムが存在しない場合
            if($participant->isLastCurriculum()){
                // 次の登録チャプターを開始し、その中の1番目のカリキュラムを開始
                $participant->startCurriculum();
            }else{
                // 直近完了したカリキュラムを取得
                $prev = $participant->prevCurriculum();

                // 登録チャプター内で次のカリキュラムを取得
                $next = Curriculum::where('chapter_id', $prev->curriculum->chapter_id)
                    ->where('curriculum_number', $prev->curriculum->curriculum_number + 1)
                    ->first();

                // 課題にカリキュラムを登録
                ParticipantCurriculum::create([
                    'participant_chapter_id' => $prev->participant_chapter_id,
                    'curriculum_id' => $next->id,
                    'starting_date' => now()
                ]);
            }
        });
        return redirect()->back();
    }
    /**
     * 課題を停止する
     */
    public function stopCurriculum(Participant $participant)
    {
        DB::transaction(function() use ($participant){
            // 現在の課題を取得
            $current = $participant->currentCurriculum();

            if (!$current) {
                return response()->json(['message' => 'No curriculum'], 404);
            }

            // チャプター内で1番目のカリキュラムであった場合
            if($current->isFirstCurriculum()){
                // 登録チャプターを未開始にする(開始日をnullにする)
                $participant->currentChapter()->update([
                    'starting_date' => null,
                ]);
            }

            // 現在の課題を削除
            $current->delete();

            // 登録チャプター内でカリキュラムが存在しない場合登録チャプターの開始日をnullにする
            $participant->chapterInNullCurriculum();
        });
        return redirect()->back();
    }
}
