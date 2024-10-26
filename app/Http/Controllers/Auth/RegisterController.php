<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\{JsonResponse, Request};
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name'     => ['required', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'max:40', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/', 'confirmed'],
        ]);

        $user = User::create($validatedData);

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user,
        ], 201);
    }
}
