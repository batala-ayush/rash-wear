<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        $user = User::where('email', $data['email'])->first();
        if (!$user || $user->status !== 'active' || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid email or password.'], 422);
        }
        Auth::login($user, true);
        $request->session()->regenerate();
        return response()->json(['user' => $user->only(['id', 'name', 'email', 'phone', 'role'])]);
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255', 'unique:users,email'], 'phone' => ['nullable', 'string', 'max:30'], 'password' => ['required', Password::min(4)] ]);
        $user = User::create([...$data, 'password' => Hash::make($data['password']), 'role' => 'customer', 'status' => 'active']);
        Auth::login($user);
        $request->session()->regenerate();
        return response()->json(['user' => $user->only(['id', 'name', 'email', 'phone', 'role'])], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['ok' => true]);
    }

    public function current(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()?->only(['id', 'name', 'email', 'phone', 'role'])]);
    }
}
