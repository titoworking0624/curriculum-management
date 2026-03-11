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
        return Inertia::render('Course/Edit',[
            'course' => $course,
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
}
