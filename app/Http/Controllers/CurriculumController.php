<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurriculumRequest;
use App\Http\Requests\UpdateCurriculumRequest;
use App\Models\Chapter;
use App\Models\Curriculum;
use Illuminate\Http\Request;
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
        $chapters = Chapter::where('course_id',$request->query('course_id'))->get();
        // dd($chapters);

        return Inertia::render('Curriculum/Create', [
            'chapter' => $chapter,
            'chapters' => $chapters,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurriculumRequest $request)
    {
        //
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
