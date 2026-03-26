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

    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function curricula(){
        return $this->hasMany(Curriculum::class);
    }
    public function participantChapters(){
        return $this->hasMany(ParticipantChapter::class);
    }
    /**
     * チャプターの中にカリキュラムが存在しているか
     */
    public function isExistCurriculum() :bool
    {
        return $this->curricula()->exists();
    }
}
