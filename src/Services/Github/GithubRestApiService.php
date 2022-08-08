<?php

namespace App\Services\Github;

use App\Services\Github\Contracts\ApiClient;
use App\Services\Github\Contracts\GithubService;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;

final class GithubRestApiService extends ApiClient implements GithubService
{
    public const CREATE_URL = '/user/repos';
    public const DELETE_URL = '/user/repos';
    public const USERS_URL = '/users/';

    private string $token;

    public function __construct(Client $client, LoggerInterface $logger,private Container $container  )
    {
       parent::__construct(client: $client,logger: $logger);
       $config = $this->container->getParameter('GITHUB');

        if (! is_array($config)) {
            throw new InvalidArgumentException('Config variable is not array');
        }
        $this->host = $config['host'];
        $this->token = $config['token'];

    }

    public function createRepository(string $name): array
    {
        $options[RequestOptions::QUERY] = [
            'name' => $name,
        ];

        return $this->sendRequest(self::CREATE_URL, $options);
    }

    public function removeRepository(string $name): mixed
    {
        $options[RequestOptions::QUERY] = [
            'name' => $name,
        ];

        return $this->sendRequest(self::CREATE_URL, $options,'DELETE');
    }

    public function listOfRepositories(): array
    {
        // TODO: Implement listOfRepositories() method.
    }

    protected function getHeaders(): array
    {
       return [];
    }
}