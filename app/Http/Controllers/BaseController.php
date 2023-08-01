<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class BaseController extends Controller
{

    public function sendResponse($result, $message = null, $code = 200)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'code' => $code
        ];

        if (isset($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }



    public function sendError($error, $errorMessages = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'code' => $code

        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
