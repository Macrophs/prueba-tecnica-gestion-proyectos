<?php

namespace App\Http\Requests\Project;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AssignUserToTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id']
        ];
    }

   
}
