<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\{Request};

class CoordinatesNotFoundException extends Exception
{
    protected $message = 'Coordinates not found.';

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error'   => class_basename($this),
            'message' => $this->getMessage(),
        ], 404);
    }
}
