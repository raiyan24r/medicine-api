<?php

namespace App\Http\Helpers;
use Illuminate\Http\JsonResponse;

/**
 * Helper function to generate a JSON response with a given HTTP status code, message, and data.
 *
 * @param int $code The HTTP status code (default is 200).
 * @param string|null $message The message to include in the response.
 * @param array|object|null $data The data to include in the response.
 *
 * @return JsonResponse The JSON response with the specified code, message, and data.
 */
if (!function_exists('httpResponse')) {
    function httpResponse(int $code = 200 , ?string $message, array|object|null $data = []) : JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
