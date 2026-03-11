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
     * カリキュラム作成画面
     */
    public function create(Request $request)
    {
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
     * カリキュラム作成処理
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
     * カリキュラム詳細画面
     */
    public function show(Curriculum $curriculum)
    {
        $chapter = $curriculum->chapter()->with('curricula')->first();
        $course = $chapter->course()->first();

        // dd($chapter);

        return Inertia::render('Curriculum/Show', [
            'curriculum' => $curriculum,
            'chapter' => $chapter,
            'course' => $course,
        ]);
    }

    /**
     * カリキュラム編集画面
     */
    public function edit(Curriculum $curriculum)
    {
        $chapter = $curriculum->chapter()->with('curricula')->first();
        $course = $chapter->course()->first();
        // dd($course);
        return Inertia::render('Curriculum/Edit', [
            'curriculum' => $curriculum,
            'chapter' => $chapter,
            'course' => $course,
        ]);
    }

    /**
     * カリキュラム編集処理
     */
    public function update(UpdateCurriculumRequest $request, Curriculum $curriculum)
    {
        // dd($request);
        DB::beginTransaction();
        try{
            $curriculum->update($request->validated());

            DB::commit();

            return to_route('chapters.show',[
                'chapter' => $curriculum->chapter_id
            ]);

        }catch(\Exception $e){
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curriculum $curriculum)
    {
        //
    }
}
