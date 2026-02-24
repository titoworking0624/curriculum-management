<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    /** @use HasFactory<\Database\Factories\ParticipantFactory> */
    use HasFactory;

    public function curricula()
    {
        return $this->belongsToMany(Curriculum::class)
            ->withPivot('chapter_id')
            ->withTimestamps();
    }
    public function chapters()
    {
        return $this->belongsToMany(Chapter::class)
            ->withPivot('course_id')
            ->withTimestamps();
    }
}
