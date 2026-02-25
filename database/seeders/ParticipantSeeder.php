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
    }
}
