<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            [
                'id' => 1,
                'course_code' => 'wd',
                'name' => 'webデザイン',
            ],
            [
                'id' => 2,
                'course_code' => 'co',
                'name' => 'プログラミング',
            ],
            [
                'id' => 3,
                'course_code' => 'mo',
                'name' => '動画クリエイター',
            ],
            [
                'id' => 4,
                'course_code' => 'ds',
                'name' => 'データサイエンス',
            ],
        ]);
    }
}
