<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    /** @use HasFactory<\Database\Factories\ParticipantFactory> */
    use HasFactory;

    protected $fillable = ['name'];
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
     * 現在の章を取得
     */
    public function currentChapter()
    {
        $current = $this->participantChapters()
            ->whereNotNull('participant_chapters.starting_date') // 課題が開始されている
            ->latest('id') // 直近で作られた
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
    public function currentCurriculum()
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
     * 登録されている中で、次に進む章を取得
     */
    public function nextChapter()
    {
        $nextChapter = $this->participantChapters()
            ->whereNull('participant_chapters.starting_date') // チャプター(章)を開始している
            ->orderBy('chapter_order') // チャプター順
            ->first();

        // dd($nextChapter);

        return $nextChapter;
    }

    /**
     * 現在の課題の一つ後の課題を取得
     */
    public function nextCurrentCurriculum()
    {
        // 現在の課題か完了している場合は一つ前の課題を取得
        $current = $this->currentCurriculum() ?? $this->prevCurriculum();

        if($current){
            $nextCurriculum = Curriculum::where('chapter_id', $current->participantChapter->chapter_id) // 現在のチャプター内
                ->where('curriculum_number', $current->curriculum->curriculum_number + 1) // 現在のカリキュラムの次が存在するか
                ->first();
        }else{ // 課題が未開始の場合
            $nextCurriculum = null;
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
            // 次のチャプターの1番目のカリキュラムを返す
            return $nextChapter?->firstCurriculum() ?? null;
        }

        return $nextCurriculum;
    }

    /**
     * 現在の課題の一つ前の（完了日が一番新しい）課題を取得
     */
    public function prevCurriculum()
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
    public function startCurriculum()
    {
        $nextChapter = $this->participantChapters() // 登録しているチャプター
            ->whereNull('participant_chapters.completion_date') // 完了していないチャプター
            ->orderBy('chapter_order') // 登録チャプター順
            ->first();
        // dd($next);
        if (!$nextChapter) {
            return;
        }
        // 登録チャプターを開始する
        $nextChapter->update([
            'starting_date' => now()
        ]);
        // そのチャプターのカリキュラム1番目開始
        $firstCurriculum = Curriculum::where('chapter_id', $nextChapter->chapter_id)
            ->where('curriculum_number', 1)
            ->first();

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
    public function chapterInNullCurriculum()
    {
        $notCompletedChapters =
            $this
            ->participantChapters()
            ->whereNull('completion_date')
            ->whereNotNull('starting_date')
            ->get();

        foreach ($notCompletedChapters as $c) {
            if (!$c->participantCurricula()->exists()) {
                $c->update([
                    'starting_date' => null,
                ]);
            }
        }
    }
}
