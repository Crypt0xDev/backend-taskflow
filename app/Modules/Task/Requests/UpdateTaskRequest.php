<?php

namespace App\Modules\Task\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'category_id' => [
                'nullable',
                Rule::exists('category', 'id')->where('user_id', $this->user()->id),
            ],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
            'priority' => ['sometimes', Rule::in(['baja', 'media', 'alta'])],
            'due_date' => ['nullable', 'date'],
            'tag_ids' => ['sometimes', 'array'],
            'tag_ids.*' => [Rule::exists('tag', 'id')->where('user_id', $this->user()->id)],
        ];
    }
}
