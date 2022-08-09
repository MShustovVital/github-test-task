<?php

namespace App\Controller;

use App\Services\Github\Contracts\GithubService;
use App\Services\Github\Exceptions\InvalidResponseException;
use App\Services\Github\Exceptions\RequestValidationException;
use App\Services\Github\Request\GithubRequest;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GithubRepositoryController extends ApiController
{
	public function __construct(private readonly GithubService $githubService)
	{
	}

	/**
	 * List of the all repositories.
	 *
	 * ---
	 *
	 * @OA\Parameter(name="username",description="min 2 symbols, max 80" ,in="query", @OA\Schema(type="string")),
	 * @OA\Response(
	 *     response=200,
	 *       @OA\JsonContent(
	 *       @OA\Property(property="username", type="string"),
	 *       @OA\Property(property="repositories", type="array",description="",@OA\Items(
	 *             @OA\Property(property="full_name", type="string"),
	 *             @OA\Property(property="url", type="string"),
	 *       )),
	 *     ),
	 *     description="Returns the list of all policies",
	 * ),
	 * @OA\Response(
	 *     response=422,
	 *     description="Validation error",
	 *     @OA\JsonContent(
	 *       @OA\Property(property="status", type="string", example="error"),
	 *       @OA\Property(property="message", type="string", example="Validation error"),
	 *       @OA\Property(property="errors", type="array",description="",@OA\Items(
	 *             @OA\Property(property="property", type="string"),
	 *             @OA\Property(property="value", type="string"),
	 *             @OA\Property(property="message", type="string"),
	 *       )),
	 *     ),
	 * )
	 */
	public function index(GithubRequest $request): JsonResponse
	{
		try {
			$request->validate();
			$username = $request->validated()['username'];
			$data = $this->githubService->listOfRepositories($username);
		} catch (RequestValidationException $e) {
			$data = json_decode($e->getMessage());

			return $this->sendError($data->message, $e->getCode(), $data->errors);
		} catch (InvalidResponseException $e) {
			return $this->sendError($e->getMessage(), $e->getCode());
		}

		return $this->sendResponse($data);
	}
}
