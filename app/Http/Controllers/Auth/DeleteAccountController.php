<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteAccountController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $user->delete();

        return response()->noContent();
    }
}
