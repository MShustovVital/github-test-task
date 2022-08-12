<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends AbstractController
{
	public function sendResponse(array $data, int $code = Response::HTTP_OK): JsonResponse
	{
		return $this->json($data, $code);
	}

	public function sendError(string $message = 'Not found', int $code = Response::HTTP_NOT_FOUND, $errors = []): JsonResponse
	{
		return $this->json(['status' => 'error', 'message' => $message, 'errors' => $errors], $code);
	}
}
