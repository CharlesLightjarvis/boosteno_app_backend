<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function sendResponse($result, $message, $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => $message,
        ], $status);
    }

    protected function sendError($error, $errorMessages = [], $code = 404): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $error,
            'errors' => $errorMessages,
        ], $code);
    }
}
