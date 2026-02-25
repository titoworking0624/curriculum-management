<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\ParticipantCurriculum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chapter = Chapter::findOrFail(1);
        $chapter->curricula()->createMany([
            [
                'id' => 1,
                'curriculum_number' => 1,
                'curriculum_code' => 'co-1-1',
                'name' => 'プログラミングに触れる',
                'content' => 'プログラミングに触れるの内容',
                'checklist' => '・チェックリスト1・チェックリスト2',
            ],
            [
                'id' => 2,
                'curriculum_number' => 2,
                'curriculum_code' => 'co-1-2',
                'name' => 'コーディングの流れを知る',
                'content' => 'コーディングの流れを知るの内容',
                'checklist' => '・チェックリスト1・チェックリスト2',
            ],
            [
                'id' => 3,
                'curriculum_number' => 3,
                'curriculum_code' => 'co-1-3',
                'name' => 'レイアウトの整え方',
                'content' => 'レイアウトの整え方の内容',
                'checklist' => '・チェックリスト1・チェックリスト2',
            ],
            [
                'id' => 4,
                'curriculum_number' => 4,
                'curriculum_code' => 'co-1-4',
                'name' => 'HTML/CSS基礎の総復習',
                'content' => 'HTML/CSS基礎の総復習の内容',
                'checklist' => '・チェックリスト1・チェックリスト2',
            ],
        ]);
    }
}
