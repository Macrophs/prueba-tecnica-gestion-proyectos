<?php

namespace App\Http\Requests\Project;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $rules = [
            'title' => ['sometimes', 'string', 'max:120'],
            'description' => ['sometimes', 'string', "max:1000"],
            'status' => ['sometimes', 'string', 'in:Pendiente,En Proceso,Finalizada']
        ];

        // rule for updating task status
        if ($this->isMethod('put') && $this->route()->named('update_task.status')) {
            return [
                'status' => ['required', 'string', 'in:Pendiente,En Proceso,Finalizada']
            ];
        }

        return $rules;
    }

    public function generate(Request $request, int $id): Task
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $id,
            'status' => $request->status ?? 'Pendiente',
        ]);

        return $task;
    }

    public function modify(Request $request, int $id): Task
    {
        $task = Task::findOrFail($id);

        $task->update([
            'title' => $request->title,
            'description' => $request->description
        ]);


        return $task;
    }

    public function updateStatus(Request $request, int $id): Task
    {
        $task = Task::findOrFail($id);

        $task->update([
            'status' => $request->status
        ]);

        return $task;
    }
}
