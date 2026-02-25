<?php

namespace Database\Seeders;

use App\Models\ParticipantChapter;
use App\Models\ParticipantCurriculum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantCurriculaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('participant_curricula')->insert([
            [
                'id' => 1,
                'participant_chapter_id' => 2,
                'curriculum_id' => 1,
                'starting_date' => now()->addday(-1),
                'completion_date' => now(),
            ],
            [
                'id' => 2,
                'participant_chapter_id' => 2,
                'curriculum_id' => 2,
                'starting_date' => now(),
                'completion_date' => null,
            ],
            [
                'id' => 3,
                'participant_chapter_id' => 2,
                'curriculum_id' => 3,
                'starting_date' => null,
                'completion_date' => null,
            ],
            [
                'id' => 4,
                'participant_chapter_id' => 2,
                'curriculum_id' => 4,
                'starting_date' => null,
                'completion_date' => null,
            ],
        ]);
        // ParticipantChapter::findOrFail(2)->update([
        //     'current_participant_curriculum_id' => 2,
        // ]);

    }
}
