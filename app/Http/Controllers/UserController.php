<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


use App\Http\Requests\UserRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search', '');

        $user = User::
            when($search, function ($query) use ($search) {
                $query->whereAny(['name', 'username','role'], 'LIKE', "%{$search}%");
            })
            ->where('id', '!=', Auth::id())
            ->orderBy("created_at", "desc")
            ->paginate(10);

        return response()->json($user);
    }


    public function store(UserRequest $request): JsonResponse
    {
        $user = $request->generate($request);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'data' => $user
        ], 201);
    }

    public function update(UserRequest $request, int $id): JsonResponse
    {
        $user = $request->modify($request, $id);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $user
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente'
        ], 200);
    }
}
