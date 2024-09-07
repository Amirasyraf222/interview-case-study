<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $statusCode = 200;

    public function formatResponse($response, $headers = [])
    {
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response;  // Just return the JsonResponse object as is
        }
    
        // Proceed with the normal array handling
        $status = $response['status'] ?? 500;  // Default to 500 if status not set
        $json_status = (200 == $status) ? 'data' : 'error';
    
        return response()->json([
            $json_status => $response
        ], $status, $headers);
    }

    public function validationErrorResponse($errors)
    {
        $message = [
            'status' => 422,
            'errors' => [$errors],
        ];
        return response()->json($message);
    }
}
