<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'chapter_id',
        'chapter_order',
        'starting_date',
        'completion_date',
    ];
    // protected $casts = [
    //     'starting_date' => 'date',
    //     'completion_date' => 'date',
    // ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
    public function participantCurricula()
    {
        return $this->hasMany(ParticipantCurriculum::class);
    }
    public function currentParticipantCurriculum()
    {
        return $this->belongsTo(
            ParticipantCurriculum::class,
            'current_participant_curriculum_id'
        );
    }
    public function syncCurricula()
    {
        $curricula = $this->chapter->curricula;

        // dd($curricula);
        foreach ($curricula as $curriculum) {
            // dd($curriculum);
            $this->participantCurricula()->create([
                'curriculum_id' => $curriculum->id,
                'starting_date' => $curriculum->curriculum_number === 1 ? now() : null,
            ]);
        }
    }
    // /**
    //  * 章完了判定
    //  */
    // public function isCompleted(): bool
    // {
    //     return $this->participantCurricula()
    //         ->where('completion_date', null)
    //         ->doesntExist();
    // }
}
