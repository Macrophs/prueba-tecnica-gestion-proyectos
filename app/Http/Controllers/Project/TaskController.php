<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Project\AssignUserToTaskRequest;
use App\Http\Requests\Project\ProjectRequest;
use App\Http\Requests\Project\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class TaskController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $task = Project::findOrFail($id);

        return response()->json([
            'data' => $task
        ]);
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $search = $request->query('search', '');
        $status = $request->query('status', '');

        $tasks = Task::with('user')
            ->where('project_id', $id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('username', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy("created_at", "desc")
            ->paginate(10);

        return response()->json($tasks);
    }

    public function store(TaskRequest $request, int $id): JsonResponse
    {
        $task = $request->generate($request, $id);

        return response()->json([
            'message' => 'Tarea creada correctamente',
            'data' => $task
        ], 201);
    }

    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $task = $request->modify($request, $id);

        return response()->json([
            'message' => 'Tarea actualizada correctamente',
            'data' => $task
        ], 200);
    }

    public function updateStatus(TaskRequest $request, int $id): JsonResponse
    {
        $task = $request->updateStatus($request, $id);

        return response()->json([
            'message' => 'Estado de la tarea actualizado correctamente',
            'data' => $task
        ], 200);
    }

    public function assignUser(AssignUserToTaskRequest $request, int $taskId): JsonResponse
    {
        $task = Task::findOrFail($taskId);

        $project = $task->project;
        if (!$project->user()->where('users.id', $request->user_id)->exists()) {
            return response()->json([
                'message' => 'El usuario no está asignado a este proyecto'
            ], 403);
        }

        $task->update([
            'user_id' => $request->user_id
        ]);

        return response()->json([
            'message' => 'Usuario asignado correctamente',
            'data' => $task->load('user')
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        $task->delete();

        return response()->json([
            'message' => 'Tarea eliminada correctamente'
        ], 200);
    }
}
