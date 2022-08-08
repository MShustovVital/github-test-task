<?php

namespace App\Services\Github\Contracts;

use App\Services\Github\Exceptions\InvalidResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

abstract class ApiClient
{

    protected string $host;

    public function __construct(protected Client $client, protected LoggerInterface $logger)
    {
    }

    /**
     * @throws InvalidResponseException
     */
    protected function sendRequest(string $url, array $options, string $method = 'GET'): array
    {
        try {
            $options['headers'] = $this->getHeaders();
            $response = $this->client->request($method, $this->host . $url, $options);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            throw new InvalidResponseException($e->getMessage(), $e->getCode());
        }
    }

    abstract protected function getHeaders(): array;
}
