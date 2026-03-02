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
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd($request->course_id);
        $course = Course::find($request->query('course_id'));
        // dd($courseId);
        $courses = Course::with('chapters')->get();

        return Inertia::render('Chapter/Create', [
            'course' => $course,
            'courses' => $courses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChapterRequest $request)
    {
        DB::beginTransaction();
        // dd($request);

        try {
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
     * Display the specified resource.
     */
    public function show(Chapter $chapter)
    {
        // dd($course);
        $curricula = $chapter->curricula()->get();
        // dd($chapters);
        $course = $chapter->course()->first();
        // dd($course);
        return Inertia::render('Chapter/Show', [
            'curricula' => $curricula,
            'chapter' => $chapter,
            'course' => $course,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChapterRequest $request, Chapter $chapter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        //
    }
}
