<?php

namespace Database\Seeders;

use App\Models\ParticipantChapter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantChaptersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('participant_chapters')->insert([
            [
                'id' => 1,
                'participant_id' => 1,
                'chapter_id' => 1,
                'chapter_order' => 1,
                'starting_date' => now()->addday(-1),
                'completion_date' => null,
            ],
            [
                'id' => 2,
                'participant_id' => 1,
                'chapter_id' => 2,
                'chapter_order' => 2,
                'starting_date' => null,
                'completion_date' => null,
            ],
            [
                'id' => 3,
                'participant_id' => 1,
                'chapter_id' => 3,
                'chapter_order' => 3,
                'starting_date' => null,
                'completion_date' => null,
            ],
            [
                'id' => 4,
                'participant_id' => 1,
                'chapter_id' => 4,
                'chapter_order' => 4,
                'starting_date' => null,
                'completion_date' => null,
            ],
        ]);
    }
}
