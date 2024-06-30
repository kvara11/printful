<?php

namespace App\Traits;


trait ApiResponses
{

    protected function success($data, $message = null, $code = 200)
    {
        $this->sendResponse([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }


    protected function error($data, $message = null, $code = 500)
    {
        $this->sendResponse([
            'status' => false,
            'message' => $message,
            'data' => $data
        ], $code);
    }


    private function sendResponse($responseData, $code)
    {
        http_response_code($code);
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        echo json_encode($responseData);
        exit;
    }
}
