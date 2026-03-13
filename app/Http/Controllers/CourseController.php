<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * コース一覧画面
     */
    public function index()
    {
        $courses = Course::get();

        return Inertia::render('Course/Index',[
            'courses' => $courses
        ]);
    }

    /**
     * コース作成画面
     */
    public function create()
    {
        return Inertia::render('Course/Create');
    }

    /**
     * コース作成処理
     */
    public function store(StoreCourseRequest $request)
    {
        // dd($request);
        // DB::beginTransaction();
        Course::create([
            'course_code' => $request->code,
            'name' => $request->name,

            ]);
            return to_route('courses.index');
    }

    /**
     * コース内章(チャプター)一覧画面
     */
    public function show(Course $course)
    {
        // dd($course);
        $chapters = $course->chapters()->get();
        // dd($chapters);
        return Inertia::render('Course/Show', [
            'course' => $course,
            'chapters' => $chapters,
        ]);
    }

    /**
     * コース編集画面
     */
    public function edit(Course $course)
    {
        $chapters = $course->chapters;
        return Inertia::render('Course/Edit',[
            'course' => $course,
            'chapters' => $chapters,
        ]);
    }

    /**
     * コース編集処理
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        // dd($request->name);
        // dd($course);
        $course->course_code = $request->code;
        $course->name = $request->name;
        $course->save();

        $this->updateOrder($request);

        return Inertia::render('Course/Edit', [
            'course' => $course,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
    private function updateOrder(UpdateCourseRequest $request)
    {
        dd($request);
        $chapters = $request->chapters;

        DB::transaction(function () use ($chapters) {

            $cases = [];
            $ids = [];

            foreach ($chapters as $index => $chapter) {

                $id = $chapter['id'];
                $order = $index + 1;

                $cases[] = "WHEN {$id} THEN {$order}";
                $ids[] = $id;
            }

            $ids = implode(',', $ids);
            $cases = implode(' ', $cases);

            DB::update("
            UPDATE chapters
            SET chapter_number = CASE id
                {$cases}
            END
            WHERE id IN ({$ids})
        ");
        });

        return back();
    }
}
