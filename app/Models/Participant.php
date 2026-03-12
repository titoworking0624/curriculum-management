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
    public function currentChapter()
    {
        $current = $this->participantChapters()
            ->whereNotNull('participant_chapters.starting_date')
            ->with([
                'participantCurricula.curriculum',
                'chapter'
                ])
            ->first();

        return $current;
    }
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

    public function nextChapter()
    {
        $nextChapter = $this->participantChapters()
            ->whereNull('participant_chapters.starting_date')
            ->orderBy('chapter_order')
            ->first();

        // dd($nextChapter);

        return $nextChapter;
    }
    public function nextCurriculum()
    {
        $nextChapter = $this->nextChapter();
        // dd($next);
        if (!$nextChapter) {
            return null;
        }
        // その chapter の curriculum1開始
        $firstCurriculum = Curriculum::where('chapter_id', $nextChapter->chapter_id)
            ->where('curriculum_number', 1)
            ->first();

        return $firstCurriculum;
    }
    public function nextCurrentCurriculum()
    {
        $current = $this->currentCurriculum() ?? $this->prevCurriculum();
        if($current){
            $nextCurriculum = Curriculum::where('chapter_id', $current->participantChapter->chapter_id)
                ->where('curriculum_number', $current->curriculum->curriculum_number + 1)
                ->first();
        }else{
            $nextCurriculum = null;
        }
        // dd($this->nextCurriculum());
        // dd($nextCurriculum);
        if (!$nextCurriculum) {
            $nextChapter = $this->nextChapter();
            // dd($nextChapter);
            if(!$nextChapter){
                return null;
            }
            // dd($nextChapter?->firstCurriculum());
            return $nextChapter?->firstCurriculum() ?? null;
        }

        return $nextCurriculum;
    }

    public function prevCurriculum()
    {
        $prevCurriculum = $this->participantCurricula()
            ->whereNotNull('participant_curricula.completion_date')
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

    public function startCurriculum()
    {
        $nextChapter = $this->participantChapters()
            ->whereNull('participant_chapters.completion_date')
            ->orderBy('chapter_order')
            ->first();
        // dd($next);
        if (!$nextChapter) {
            return;
        }
        $nextChapter->update([
            'starting_date' => now()
        ]);
        // その chapter の curriculum1開始
        $firstCurriculum = Curriculum::where('chapter_id', $nextChapter->chapter_id)
            ->where('curriculum_number', 1)
            ->first();

        // id(!$firstCurriculum){
        //     retr
        // }
        ParticipantCurriculum::create([
            'participant_chapter_id' => $nextChapter->id,
            'curriculum_id' => $firstCurriculum->id,
            'starting_date' => now()
        ]);
    }
    /**
     *
     */
    public function isLastCurriculum(): bool
    {
        $participantCurriculum = $this->participantCurricula()->latest('id')->first()->curriculum;
        // dd($participantCurriculum);
        return !Curriculum::where('chapter_id', $participantCurriculum->chapter_id)
            ->where('curriculum_number', '>', $participantCurriculum->curriculum_number)
            ->exists();
    }
}
