<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Api;

use Emecef\Core\Client;
use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Contract\HttpResponseInterface;
use Emecef\Core\Contract\LoggerInterface;
use PHPUnit\Framework\TestCase;

abstract class ClientApiTestCase extends TestCase
{
    protected const BASE_URL = 'https://sygmef.impots.bj/emcf/api/invoice';
    protected const TOKEN = 'fake-jwt-token';

    protected function createClient(
        HttpClientInterface $httpClient,
        ?LoggerInterface $logger = null
    ): Client {
        return new Client(
            self::BASE_URL,
            self::TOKEN,
            $httpClient,
            $logger ?? $this->createStub(LoggerInterface::class)
        );
    }

    protected function createResponse(int $statusCode, string $body, array $headers = []): HttpResponseInterface
    {
        $response = $this->createStub(HttpResponseInterface::class);
        $response->method('getStatusCode')->willReturn($statusCode);
        $response->method('getBody')->willReturn($body);
        $response->method('getHeaders')->willReturn($headers);

        return $response;
    }

    /**
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . self::TOKEN,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
