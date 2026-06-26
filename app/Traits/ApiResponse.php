<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse(mixed $data, string $message = 'Data retrieved successfully', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'meta' => [
                'service_name' => 'Vehicle-Service',
                'api_version' => 'v1',
            ],
        ], $statusCode);
    }

    protected function errorResponse(string $message, mixed $errors = null, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
