<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    /** @use HasFactory<\Database\Factories\ParticipantFactory> */
    use HasFactory;

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
            ->whereNull('participant_curricula.completion_date') //カリキュラムが未完了
            ->whereHas('participantChapter',function($query){
                $query->whereNull('completion_date') //章未完了
                    ->whereNotNull('starting_date'); // 開始済み
            })
            ->join('curricula','participant_curricula.curriculum_id','=','curricula.id')
            ->orderBy('curricula.curriculum_number')
            ->with([
                'curriculum',
                'participantChapter.chapter.course'
            ])
            ->select('participant_curricula.*')
            ->first();

        return $current;
    }
    // public function getCurrentCurriculumAttribute(){
    //     $current = $this->currentCurriculum();
    //     return $current;
    // }
}
