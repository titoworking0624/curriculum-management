<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    /** @use HasFactory<\Database\Factories\ParticipantFactory> */
    use HasFactory;

    protected $fillable = ['name','email'];
    // protected $appends = ['current_curriculum'];

    public function participantChapters()
    {
        return $this->hasMany(ParticipantChapter::class);
    }
    public function participantCurricula()
    {
        return $this->hasManyThrough(
            ParticipantCurriculum::class,
            ParticipantChapter::class,
            'participant_id',           // FK on participant_chapters
            'participant_chapter_id',   // FK on participant_curricula
            'id',                       // local key on participants
            'id'                        // local key on participant_chapters
        );
    }
    /**
     * 現在のチャプターを取得
     */
    public function currentChapter() : ?ParticipantChapter
    {
        $current = $this->participantChapters()
            ->whereNotNull('participant_chapters.starting_date') // 課題が開始されている
            ->whereNull('participant_chapters.completion_date') // 課題が未完了
            ->orderBy('participant_chapters.chapter_order') // チャプター順
            ->with([
                'participantCurricula.curriculum',
                'chapter'
                ])
            ->first();

        return $current;
    }
    /**
     * 現在の課題を取得する
     */
    public function currentCurriculum() : ?ParticipantCurriculum
    {
        $current = $this->participantCurricula()
            ->whereNull('participant_curricula.completion_date') // カリキュラム未完了
            ->with([
                'curriculum',
                'participantChapter.chapter.course',
            ])
            ->first();

        return $current;
    }

    /**
     * 登録されている中で、次に進むチャプターを取得
     */
    public function nextChapter() : ?ParticipantChapter
    {
        $startingChapterCount = $this->startingChapterCount();
        // dd($startingChapterCount);
        if($startingChapterCount <= 1){
            $nextChapter = $this->participantChapters()
                ->whereNull('participant_chapters.starting_date') // チャプター(章)を開始していない
                ->whereNull('participant_chapters.completion_date') // 完了していないチャプター
                ->orderBy('chapter_order') // チャプター順
                ->first();
        }else{
            $currentChapter = $this->currentChapter();
            $nextChapter = $this->participantChapters()
                ->whereNull('participant_chapters.completion_date')
                ->whereNotNull('participant_chapters.starting_date')
                ->where('id','!=',$currentChapter->id)
                ->orderBy('chapter_order')
                ->first();
        }


        // dd($nextChapter);

        return $nextChapter;
    }

    /**
     * 現在の課題の一つ後の課題を取得
     */
    public function nextCurrentCurriculum(): ?Curriculum
    {
        // 現在の課題か完了している場合は一つ前の課題を取得
        $current = $this->currentCurriculum() ?? $this->prevCurriculum();

        $nextCurriculum = null;

        // 直近完了した課題と現在のチャプターが違う場合
        if($this->isChapterCorrect()){
            $nextCurriculum = $this->reStartWithPrevChapter();
            // dd($nextCurriculum);
        }
        // 現在のチャプターが完了していなかったら
        elseif($current && !$current->participantChapter->completion_date){
            $nextCurriculum = Curriculum::where('chapter_id', $current->participantChapter->chapter_id) // 現在のチャプター内
                ->where('curriculum_number', $current->curriculum->curriculum_number + 1) // 現在のカリキュラムの次が存在するか
                ->first();
        }
        // dd($this->nextCurriculum());
        // dd($nextCurriculum);

        // 現在のチャプター内に次の課題がない場合
        if (!$nextCurriculum) {
            // 次のチャプターを取得
            $nextChapter = $this->nextChapter();
            // 次のチャプターを登録してない場合
            if(!$nextChapter){
                return null;
            }

            // 次のチャプターで既に完了しているカリキュラムが存在する場合
            $completionCurriculum = $this
                ->participantCurricula()->where('participant_chapter_id', $nextChapter->id);
            if($completionCurriculum->exists()){
                // 既に完了したカリキュラム取得
                $prevCurriculum = $completionCurriculum?->latest('id')->first()->curriculum;
                // 完了したカリキュラムの次があればそれを返す
                $nextCurriculum = $nextChapter->chapter->curricula()->where('curriculum_number', $prevCurriculum->curriculum_number + 1)->first();
                // if($nextCurriculum) return $nextCurriculum;
            }
            // // 次のチャプターの1番目のカリキュラムを返す
            // return $nextChapter?->firstCurriculum() ?? null;

            // カリキュラムが登録されているチャプターを探索して、
            if(!$nextCurriculum){
                $nextCurriculum = $nextChapter->searchCurriculumInChapter();
            }
        }

        return $nextCurriculum;
    }

    /**
     * 現在の課題の一つ前の（完了日が一番新しい）課題を取得
     */
    public function prevCurriculum():?ParticipantCurriculum
    {
        $prevCurriculum = $this->participantCurricula()
            ->whereNotNull('participant_curricula.completion_date') // 完了している課題
            ->latest('id')
            // ->with('curriculum')
            // ->orderBy('participant_curricula.completion_date','DESC')
            ->first();
        // dd($prevCurriculum);
        if (!$prevCurriculum) {
            return null;
        }

        return $prevCurriculum;
    }

    /**
     * 登録されている未開始のチャプターを開始し、最初のカリキュラムを開始する
     */
    public function startCurriculum():void
    {
        while($nextChapter = $this->nextChapter()){
                // dd($next);
                // if (!$nextChapter) {
                //     return;
                // }
                // 登録チャプターを開始する
                $nextChapter->update([
                    'starting_date' => now()
                ]);
                // 登録チャプターにカリキュラムが登録されていない場合
                if(!$nextChapter->chapter->isExistCurriculum()){
                    $nextChapter->update([
                        'completion_date' => now(),
                    ]);
                    continue;
                }
                // そのチャプターのカリキュラム1番目開始
                $firstCurriculum = Curriculum::where('chapter_id', $nextChapter->chapter_id)
                    ->where('curriculum_number', 1)
                    ->first();
                break;
            }

        // 課題を登録
        ParticipantCurriculum::create([
            'participant_chapter_id' => $nextChapter->id,
            'curriculum_id' => $firstCurriculum->id,
            'starting_date' => now()
        ]);
    }
    /**
     * 章の中のカリキュラムが存在するか判定
     * 章内に未完了のカリキュラムが存在していなかったらTrue
     */
    public function isLastCurriculum(): bool
    {
        // 直近の課題を取得
        $curriculum = $this->participantCurricula()->latest('id')->first()?->curriculum;

        // 課題未開始の場合
        if(!$curriculum) return true;

        // チャプター内に直近の課題の次のカリキュラムが存在していたらTrue
        return !Curriculum::where('chapter_id', $curriculum->chapter_id)
            ->where('curriculum_number', '>', $curriculum->curriculum_number)
            ->exists();
    }

    /**
     * 登録チャプター内でカリキュラムが存在しない場合登録チャプターの開始日をnullにする
     */
    public function chapterInNullCurriculum():void
    {
        // 完了したチャプター内にカリキュラムが存在していなかったら未完了状態にする
        while($chapter = $this->finalCompletionChpater()){
            // dd(!$chapter->isStartChapter());
            if(!$chapter->isStartChapter()){
                $chapter->update([
                    'completion_date' => null,
                ]);
            }else{
                break;
            }
        }

        // 未完了（開始済み）のチャプターを取得
        $notCompletedChapters =
            $this
            ->participantChapters()
            ->whereNull('completion_date')
            ->whereNotNull('starting_date')
            ->get();

        // チャプター内にカリキュラムが存在していなかったら未開始状態にする
        foreach ($notCompletedChapters as $c) {
            if (!$c->participantCurricula()->exists()) {
                $c->update([
                    'starting_date' => null,
                ]);
            }
        }
    }
    /**
     * 開始しているチャプターを返す
     */
    public function isStartChapter():?ParticipantChapter
    {
        $currentChapter =
            $this->participantChapters()
                ->whereNotNull('starting_date')
                ->whereNull('completion_date')
                ->orderBy('chapter_order')
                ->first();

        if(!$currentChapter) return null;

        return $currentChapter;
    }
    /**
     * Edit用Eagerロード
     */
    public function participantInEdit():Participant
    {
        return $this->load([
            'participantCurricula' => function ($q) {
                $q->latest('starting_date')
                    ->latest('id');
            },
            'participantCurricula.curriculum',
            'participantCurricula.participantChapter.chapter',
            'participantChapters.chapter.course'
        ]);
    }
    /**
     * 直近完了した課題と現在のチャプターが違う場合trueを返す
     */
    public function isChapterCorrect():bool
    {
        [$current,$currentChapter] = $this->currentCurriculumAndChapter();
        if(!$currentChapter) return false;

        return $currentChapter?->id !== $current?->participantChapter->id;
    }
    /**
     * 開始済みのチャプターから、次に進める課題を返す
     */
    public function reStartWithPrevChapter():?Curriculum
    {
        // 現在のチャプターが取得出来なくなるまで次の課題を探す
        while($currentChapter = $this->currentChapter()){

            // 現在のチャプターの中で既に完了した課題を取得
            $completionCurriculum = $this->participantCurricula()->where('chapter_id', $currentChapter?->chapter_id)->latest('id')?->first()?->curriculum;

            // 完了した課題の次のカリキュラムを取得
            $nextCurriculum = $currentChapter?->chapter->curricula->where(
                'curriculum_number',
                $completionCurriculum->curriculum_number + 1
                )->first();

            // 次のカリキュラムが存在しなかった場合
            if(!$nextCurriculum){
                // 現在のチャプターを完了させる
                $currentChapter->update([
                    'completion_date' => now(),
                ]);
            // 次のカリキュラムが存在した場合
            }else{
                // ループから抜け出す
                break;
            }
        }
        return $nextCurriculum ?? null;
    }
    /**
     * 開始済みのチャプターの個数を返す
    */
    public function startingChapterCount() :int
    {
        $startingChapterCount = $this->participantChapters()
        ->whereNull('participant_chapters.completion_date')
        ->whereNotNull('participant_chapters.starting_date')
        ->count();

        return $startingChapterCount;
        }
    /**
     * 現在のチャプターと直近の課題を取得する
     */
    private function currentCurriculumAndChapter():array
    {
        $current = $this->currentCurriculum() ?? $this->prevCurriculum();

        $currentChapter = $this->currentChapter();

        return [$current,$currentChapter];
    }
    /**
     * 直近完了したチャプターを取得
     */
    private function finalCompletionChpater():?ParticipantChapter
    {
        return $this->participantChapters()->whereNotNull('completion_date')->latest('chapter_order')->first();
    }

}
