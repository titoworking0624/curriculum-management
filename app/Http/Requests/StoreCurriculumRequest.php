<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCurriculumRequest extends FormRequest
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
        return [
            'chapter_id' => ['required', 'exists:chapters,id'],
            'name' => ['required'],
            'content' => ['required'],
            'curriculum_number' => [
                'required',
                Rule::unique('curricula')
                    ->where(
                        fn($q) =>
                        $q->where('chapter_id', $this->chapter_id)
                    ),
            ],
        ];
    }
}
