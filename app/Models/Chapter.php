<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    /** @use HasFactory<\Database\Factories\ChapterFactory> */
    use HasFactory;

    protected $fillable = [
        'course_id',
        'chapter_number',
        'name',
    ];

    public function participants(){
        return $this->belongsToMany(Participant::class)
            ->withPivot('name')
            ->withTimestamps();
    }
    public function curricula(){
        return $this->hasMany(Curriculum::class);
    }
}
