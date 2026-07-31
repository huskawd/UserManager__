<?php

namespace App\Response;

class JsonResponse
{
    public function send(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
