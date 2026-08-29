<?php

namespace App\Modules\Tag\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagInUseException extends Exception
{
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'No se puede eliminar la etiqueta: todavía está en uso por una o más tareas.',
        ], 409);
    }
}
