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

    public function nextCurriculum()
    {
        $nextChapter = $this->participantChapters()
            ->whereNull('participant_chapters.completion_date')
            ->orderBy('chapter_order')
            ->first();
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
        if(!$current){
            return null;
        }
        // dd($current->curriculum);
        $nextCurriculum = Curriculum::where('chapter_id', $current->participantChapter->chapter_id)
            ->where('curriculum_number', $current->curriculum->curriculum_number + 1)
            ->first();
        // dd($this->nextCurriculum());
        if (!$nextCurriculum) {
            return $this->nextCurriculum();
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
}
