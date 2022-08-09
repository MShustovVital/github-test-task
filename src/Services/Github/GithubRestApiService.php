<?php

namespace App\Services\Github;

use App\Services\Github\Contracts\ApiClient;
use App\Services\Github\Contracts\GithubService;
use App\Services\Github\Enums\HttpMethod;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;

final class GithubRestApiService extends ApiClient implements GithubService
{
    public const CREATE_URL = '/user/repos';
    public const DELETE_URL = '/repos';
    public const USERS_URL = '/users';

    private string $token;
    private string $username;

    public function __construct(Client $client, LoggerInterface $logger,private readonly Container $container)
    {
       parent::__construct(client: $client,logger: $logger);
       $config = $this->container->getParameter('GITHUB');

        if (! is_array($config)) {
            throw new InvalidArgumentException('Config variable is not array');
        }
        $this->host = $config['host'];
        $this->token = $config['token'];
        $this->username = $config['username'];
    }

    /**
     * @throws Exceptions\RequestValidationException
     */
    public function createRepository(string $name): array
    {
        $options[RequestOptions::JSON] = [
            'name' => $name,
        ];

        return $this->sendRequest(self::CREATE_URL, $options,HttpMethod::POST);
    }

    /**
     * @throws Exceptions\InvalidResponseException
     */
    public function removeRepository(string $name): array
    {
        $url = self::DELETE_URL . "/$this->username/$name";

        return $this->sendRequest($url, [], HttpMethod::DELETE);
    }

    /**
     * @throws Exceptions\InvalidResponseException
     */
    #[ArrayShape(['username' => "string", 'repositories' => "array"])]
    public function listOfRepositories(string $username): array
    {
        $url = self::USERS_URL . "/$username/repos";
        $response = $this->sendRequest($url, [],HttpMethod::GET);
        return [
            'username'=>$username,
            'repositories'=>$this->transformListOfRepositories($response)
        ];
    }

    #[ArrayShape(['Authorization' => "string"])]
    protected function getHeaders(): array
    {
       return ['Authorization'=>"token {$this->token}"];
    }

    private function transformListOfRepositories(array $data):array
    {
        return array_map(function ($value){
            return [
                'full_name'=>$value['full_name'],
                'url'=>$value['url']
            ];
        },$data);
    }
}