<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course = Course::findOrFail(2);
        $course->chapters()->createMany([
            [
                'id' => 1,
                'chapter_number' => 1,
                'name' => 'HTML/CSS基礎'
            ],
            [
                'id' => 2,
                'chapter_number' => 2,
                'name' => 'HTML/CSS応用'
            ],
            [
                'id' => 3,
                'chapter_number' => 3,
                'name' => 'JavaScript・jQueryの習得'
            ],
            [
                'id' => 4,
                'chapter_number' => 4,
                'name' => 'WordPressでの構築'
            ],
        ]);
    }
}
