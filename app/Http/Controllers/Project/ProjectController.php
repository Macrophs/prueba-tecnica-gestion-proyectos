<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Project\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        return response()->json([
            'data' => $project
        ]);
    }

    public function index(): JsonResponse
    {
        $projects = Project::with('user')->paginate(10);

        return response()->json($projects);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $project = $request->generate($request);

        return response()->json([
            'message' => 'Proyecto creado correctamente',
            'data' => $project
        ], 201);
    }

    public function destroy(Request $request): RedirectResponse
    {

        return redirect('/');
    }
}
