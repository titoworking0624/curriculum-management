<?php

namespace Database\Seeders;

use App\Models\Participant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('participants')->insert([
            [
                'id' => 1,
                'name' => '伊藤貴幸',
            ],
        ]);
        $participant = Participant::findOrFail(1);
        $participant->chapters()->attach([
            1 => ['completion_flag' => true],
            2 => ['completion_flag' => false],
        ]);
        $participant->curricula()->attach([
            1 => [
                'starting_date' => now()->addday(-1),
                'completion_date' => now(),
            ],
            2 => [
                'starting_date' => now(),
                'completion_date' => null,
            ],
            3 => [
                'starting_date' => null,
                'completion_date' => null,
            ],
            4 => [
                'starting_date' => null,
                'completion_date' => null,
            ],
        ]);
    }
}
