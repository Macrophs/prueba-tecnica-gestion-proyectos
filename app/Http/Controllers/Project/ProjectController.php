<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        return response()->json([
            'data' => $project
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search', '');

        $projects = Project::with('user')
            ->when($search, function ($query) use ($search) {
                $query->whereAny(['name', 'description'], 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'LIKE', "%{$search}%");
                    });
            })
            ->orderBy("created_at", "desc")
            ->paginate(10);

        return response()->json($projects);
    }

    public function users(int $projectId): JsonResponse
    {
        $project = Project::with('user')->findOrFail($projectId);
        return response()->json($project->user);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $project = $request->generate($request);

        return response()->json([
            'message' => 'Proyecto creado correctamente',
            'data' => $project
        ], 201);
    }

    public function update(ProjectRequest $request, int $id): JsonResponse
    {
        $project = $request->modify($request, $id);

        return response()->json([
            'message' => 'Proyecto actualizado correctamente',
            'data' => $project
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $project->delete();

        return response()->json([
            'message' => 'Proyecto eliminado correctamente'
        ], 200);
    }
}
