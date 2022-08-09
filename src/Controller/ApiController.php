<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiController extends AbstractController
{
    public function sendResponse(array $data, int $code = 200): JsonResponse
    {
        return $this->json(['status' => 'success', 'data' => $data], $code);
    }

    public function sendError(string $message = 'Not found', int $code = 404): JsonResponse
    {
        return $this->json(['status' => 'error', 'message' => $message], $code);
    }
}
