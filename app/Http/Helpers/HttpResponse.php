<?php

namespace App\Http\Helpers;
use Illuminate\Http\JsonResponse;

if (!function_exists('httpResponse')) {
    function httpResponse(int $code = 200 , ?string $message, array|object|null $data = []) : JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
