<?php
// app/Http/Requests/StoreTaskRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                // Unique combination of title + due_date
                Rule::unique('tasks')->where(function ($query) {
                    return $query->where('due_date', $this->due_date);
                })
            ],
            'due_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'priority' => [
                'required',
                Rule::in(['low', 'medium', 'high'])
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'A task with this title already exists for the given due date.',
            'due_date.after_or_equal' => 'The due date must be today or later.',
        ];
    }
}
