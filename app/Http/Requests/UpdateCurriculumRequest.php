<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCurriculumRequest extends FormRequest
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
            'content' => ['required','string'],
            'checklist' => ['nullable','string'],
            'curriculum_number' => [
                'required',
                Rule::unique('curricula')
                    ->where(
                        fn($q) =>
                        $q->where('chapter_id', $this->chapter_id)
                    )->ignore($this->curriculum),
            ],
            'curriculum_code' => ['nullable', 'string'],
        ];    }
}
