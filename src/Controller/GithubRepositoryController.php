<?php

namespace App\Controller;

use App\Services\Github\Contracts\GithubService;
use App\Services\Github\Request\GithubRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GithubRepositoryController extends AbstractController
{
    public function __construct(private readonly GithubService $githubService)
    {

    }

    public function index(GithubRequest $request): JsonResponse
    {
        $username = $request->validated()['username'];
        $data = $this->githubService->listOfRepositories($username);

        return $this->json([
           $data
        ]);
    }
}
