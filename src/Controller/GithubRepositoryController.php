<?php

namespace App\Controller;

use App\Services\Github\Contracts\GithubService;
use App\Services\Github\Exceptions\InvalidResponseException;
use App\Services\Github\Exceptions\RequestValidationException;
use App\Services\Github\Request\GithubRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GithubRepositoryController extends ApiController
{
    public function __construct(private readonly GithubService $githubService)
    {

    }

    public function index(GithubRequest $request): JsonResponse
    {
        try {
            $request->validate();
            $username = $request->validated()['username'];
            $data = $this->githubService->listOfRepositories($username);
        }
        catch (RequestValidationException|InvalidResponseException $e)
        {
            return $this->sendError($e->getMessage(),$e->getCode());
        }

        return $this->sendResponse($data);
    }
}
