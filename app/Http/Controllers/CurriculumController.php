<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurriculumRequest;
use App\Http\Requests\UpdateCurriculumRequest;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Curriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CurriculumController extends Controller
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
        // dd($request->chapter_id);
        // dd($request->course_id);
        $chapter = Chapter::find($request->query('chapter_id'));
        // dd($chapter);
        $courses = Course::with('chapters.curricula')->get();
        // dd($chapters);

        return Inertia::render('Curriculum/Create', [
            'chapter' => $chapter,
            'courses' => $courses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurriculumRequest $request)
    {
        // dd($request);
        DB::beginTransaction();

        try {
            $curriculum = Curriculum::create([
                'curriculum_number' => $request->curriculum_number,
                'curriculum_code' => $request->curriculum_code,
                'chapter_id' => $request->chapter_id,
                'name' => $request->name,
                'content' => $request->content,
                'checklist' => $request->checklist,
            ]);

            DB::commit();

            $chapter = $curriculum->chapter()->first();
            $curricula = $chapter->curricula()->get();
            $course = $chapter->course()->first();
            // dd($course);

            return to_route('chapters.show', [
                'curricula' => $curricula,
                'chapter' => $chapter,
                'course' => $course,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Curriculum $curriculum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curriculum $curriculum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurriculumRequest $request, Curriculum $curriculum)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curriculum $curriculum)
    {
        //
    }
}
