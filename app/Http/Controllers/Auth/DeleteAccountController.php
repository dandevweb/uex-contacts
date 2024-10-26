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

        Hash::check($request->password, $user->password)
            ? $user->delete()
            : abort(422, 'Senha incorreta');

        return response()->noContent();
    }
}
