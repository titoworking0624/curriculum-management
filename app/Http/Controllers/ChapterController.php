<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;
use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * 章(チャプター)作成画面
     */
    public function create(Request $request)
    {
        // 章一覧(courses.show)から選択した際のコース
        $course = Course::find($request->query('course_id'));
        // コース一覧
        $courses = Course::with('chapters')->get();

        return Inertia::render('Chapter/Create', [
            'course' => $course,
            'courses' => $courses,
        ]);
    }

    /**
     * 章(チャプター)作成処理
     */
    public function store(StoreChapterRequest $request)
    {
        DB::beginTransaction();
        // dd($request);

        try {
            // 章登録
            $chapter = Chapter::create([
                'chapter_number' => $request->chapter_number,
                'course_id' => $request->course_id,
                'name' => $request->name,
                ]);

            DB::commit();

            return to_route('courses.show', [
                'course' => $chapter->course_id
            ]);
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * 章(チャプター)内チャプター一覧画面
     */
    public function show(Chapter $chapter)
    {
        // カリキュラム一覧取得
        $curricula = $chapter->curricula()->get();
        // コース名取得のため
        $course = $chapter->course()->first();
        // dd($course);
        return Inertia::render('Chapter/Show', [
            'curricula' => $curricula,
            'chapter' => $chapter,
            'course' => $course,
        ]);
    }

    /**
     * 章(チャプター)編集画面
     */
    public function edit(Chapter $chapter)
    {
        // dd($chapter);
        $course = $chapter->course()->with('chapters')->first();
        // dd($course);

        return Inertia::render('Chapter/Edit',[
            'course' => $course,
            'chapter' => $chapter,
        ]);
    }

    /**
     * 章(チャプター)編集処理
     */
    public function update(UpdateChapterRequest $request, Chapter $chapter)
    {
        $chapter->chapter_number = $request->chapter_number;
        $chapter->name = $request->name;
        $chapter->save();

        $course = $chapter->course()->first();
        $chapters = $course->chapters()->get();

        return to_route('courses.show', [
            'course' => $course,
            'chapters' => $chapters,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        //
    }
}
