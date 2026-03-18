<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    /** @use HasFactory<\Database\Factories\CurriculumFactory> */
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'curriculum_number',
        'curriculum_code',
        'name',
        'content',
        'checklist',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
    public function participantCurricula()
    {
        return $this->hasMany(ParticipantCurriculum::class);
    }
}
