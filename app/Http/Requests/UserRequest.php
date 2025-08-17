<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', "max:120",'unique:users,username,' . $this->route('id')],
            'role'=> ['required', 'string', 'in:Admin,Develop'],
        ];
    }

    public function generate(Request $request): User
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->username), // Default password
        ]);

        return $user;
    }

    public function modify(Request $request, int $id): User
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
        ]);


        return $user;
    }
}
