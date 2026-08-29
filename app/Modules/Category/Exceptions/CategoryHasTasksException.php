<?php

namespace App\Modules\Category\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryHasTasksException extends Exception
{
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'No se puede eliminar la categoría: todavía tiene tareas asociadas.',
        ], 409);
    }
}
