<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChapterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd($this);
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'chapter_number' => [
                'required',
                Rule::unique('chapters')
                    ->where(
                        fn($q) =>
                        $q->where('course_id', $this->course_id)
                    )->ignore($this->chapter),
            ],
        ];
    }
}
