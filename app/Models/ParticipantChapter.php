<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
    protected $casts = [
        'starting_date' => 'date:Y-m-d',
        'completion_date' => 'date:Y-m-d',
    ];

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
    // public function currentParticipantCurriculum()
    // {
    //     return $this->belongsTo(
    //         ParticipantCurriculum::class,
    //         'current_participant_curriculum_id'
    //     );
    // }

    // 登録チャプター内の1番目のカリキュラムを取得
    public function firstCurriculum()
    {
        // dd($this);
        $chapter = $this->chapter()->first();
        // dd($chapter);
        return $chapter->curricula()->where('curriculum_number',1)->first();
    }
    public function isStartChapter():bool
    {
        return $this->participantCurricula()->exists();
    }
    // public function syncCurricula(int $number)
    // {
    //     $curriculum = $this->chapter->curricula->where('curriculum_number', $number)->firstOrFail();

    //     // dd($curricula);
    //     // dd($this->id);
    //     $this->participantCurricula()->firstOrCreate(
    //         [
    //             'curriculum_id' => $curriculum->id
    //         ],
    //         [
    //             'starting_date' => now(),
    //         ]
    //     );

}
