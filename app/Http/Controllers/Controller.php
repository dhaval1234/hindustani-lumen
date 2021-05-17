<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function sendBadRequest($returnArray = null, $returnMessage, $statusCode = RESPONSE_BAD_REQUEST)
    {
        $response = [
            'success' => false,
            'status' => $statusCode,
            'data' => null,
            'message' => is_string($returnMessage) ? ucfirst(strtolower($returnMessage)) : $returnMessage
        ];
        return response()->json($response);
    }

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }
}
