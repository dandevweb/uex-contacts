<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteAccountController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => __('Invalid password')], 422);
        }

        $user->delete();

        return response()->noContent();
    }
}
