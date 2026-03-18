<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipantCurriculum extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_chapter_id',
        'curriculum_id',
        'starting_date',
        'completion_date',
    ];

    protected $casts = [
        'starting_date' => 'date:Y-m-d',
        'completion_date' => 'date:Y-m-d',
    ];

    public function participantChapter(): BelongsTo
    {
        return $this->belongsTo(ParticipantChapter::class);
    }
    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }
    // public function scopeIncomplete(Builder $query)
    // {
    //     return $query->whereNull('completion_date');
    // }
    // public function scopeCompleted($query)
    // {
    //     return $query->whereNotNull('completion_date');
    // }
    // public function nextCurriculum()
    // {
    //     return Curriculum::where('chapter_id', $this->curriculum->chapter_id)
    //         ->where('curriculum_number', '>', $this->curriculum->curriculum_number)
    //         ->orderBy('curriculum_number')
    //         ->first();
    // }
    // public function createNext()
    // {
    //     $next = $this->nextCurriculum();

    //     if (!$next) {
    //         return null;
    //     }

    //     return self::create([
    //         'participant_chapter_id' => $this->participant_chapter_id,
    //         'curriculum_id' => $next->id,
    //         'starting_date' => now(),
    //     ]);
    // }
    // public function nextChapter()
    // {
    //     $currentChapter = $this->participantChapter()->chapter();
    //     dd($currentChapter);
    // }
    // /**
    //  * 章の中のカリキュラムが存在するか判定
    //  * 章内に未完了のカリキュラムが存在していなかったらTrue
    //  */
    // public function isLastCurriculum(): bool
    // {
    //     return !Curriculum::where('chapter_id', $this->curriculum->chapter_id)
    //         ->where('curriculum_number', '>', $this->curriculum->curriculum_number)
    //         ->exists();
    // }

    // チャプター内に1番目のカリキュラムが存在しているか
    public function isFirstCurriculum(): bool
    {
        return $this->curriculum()->where('curriculum_number',1)->exists();
    }


}
