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
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::get();

        return Inertia::render('Course/Index',[
            'courses' => $courses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Course/Create');
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return Inertia::render('Course/Edit',[
            'course' => $course,
        ]);
    }

    /**
     * Update the specified resource in storage.
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
