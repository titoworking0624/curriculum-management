<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantCurriculum extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_chapter_id',
        'curriculum_id',
        'starting_date',
        'completion_date',
    ];

    // protected $casts = [
    //     'starting_date' => 'date',
    //     'completion_date' => 'date',
    // ];

    public function participantChapter()
    {
        return $this->belongsTo(ParticipantChapter::class);
    }
    public function curriculum(){
        return $this->belongsTo(Curriculum::class);
    }
    public function scopeIncomplete($query){
        return $query->whereNull('completion_date');
    }
}
