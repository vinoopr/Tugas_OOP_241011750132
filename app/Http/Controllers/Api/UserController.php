<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'message' => 'Daftar user berhasil diambil.', 'data' => User::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return response()->json(['success' => true, 'message' => 'User berhasil dibuat.', 'data' => $user], 201);
    }

    public function show($id)
    {
        return response()->json(['success' => true, 'message' => 'User berhasil diambil.', 'data' => User::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate($this->rules($user));
        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        return response()->json(['success' => true, 'message' => 'User berhasil diperbarui.', 'data' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->delete();
        });

        return response()->json(['success' => true, 'message' => 'User berhasil dihapus.', 'data' => null]);
    }

    private function rules(?User $user = null): array
    {
        $presence = $user ? 'sometimes' : 'required';

        return [
            'name' => [$presence, 'required', 'string', 'max:255'],
            'email' => [$presence, 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'role' => ['nullable', 'string', 'max:255'],
        ];
    }
}
