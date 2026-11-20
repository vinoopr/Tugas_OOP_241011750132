<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends LoginController
{
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'phone' => ['nullable', 'string', 'max:255'],
                'address' => ['nullable', 'string'],
                'role' => ['nullable', 'string', 'max:255'],
            ]);
            $result = DB::transaction(function () use ($data) {
                $data['password'] = bcrypt($data['password']);
                $user = User::create($data);

                return [$user, $user->createToken('auth_token')->plainTextToken];
            });

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil.',
                'access_token' => $result[1],
                'token_type' => 'Bearer',
                'data' => $result[0],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            report($e);

            return response()->json(['success' => false, 'message' => 'Registrasi gagal.', 'data' => null], 500);
        }
    }

    public function loginApi(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['success' => false, 'message' => 'Email atau password salah.', 'data' => null], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
            'data' => $user,
        ]);
    }

    public function logoutApi(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => true, 'message' => 'Logout berhasil.', 'data' => null]);
    }
}
