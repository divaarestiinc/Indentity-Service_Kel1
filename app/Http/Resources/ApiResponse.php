<?php
namespace App\Http\Resources;

class ApiResponse
{
    public static function success($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    public static function error($message = 'Error', $status = 400, $errors = null)
    {
        $payload = [
            'success' => false,
            'message' => $message
        ];
        if ($errors !== null) $payload['errors'] = $errors;
        return response()->json($payload, $status);
    }
}
