<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Api;

use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Dto\Response\StatusResponseDto;

/**
 * Intent: Status API — request shape and success response handling.
 */
final class ClientGetStatusTest extends ClientApiTestCase
{
    public function testSendsGetToBaseUrlAndReturnsDecodedJson(): void
    {
        $body = '{"status":true,"version":"1.0","tokenValid":"2025-12-31T00:00:00+01:00",'
            . '"pendingRequestsCount":0,"pendingRequestsList":[]}';
        $response = $this->createResponse(200, $body);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                self::BASE_URL . '/',
                $this->defaultHeaders(),
                null
            )
            ->willReturn($response);

        $client = $this->createClient($httpClient);
        $result = $client->getStatus();

        self::assertTrue($result['status']);
        self::assertSame('1.0', $result['version']);
        self::assertSame(0, $result['pendingRequestsCount']);
        self::assertIsArray($result['pendingRequestsList']);
    }

    public function testReturnsEmptyArrayWhenResponseBodyIsEmpty(): void
    {
        $response = $this->createResponse(200, '');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);
        $result = $client->getStatus();

        self::assertSame([], $result);
    }

    public function testReturnsEmptyArrayWhenResponseBodyIsInvalidJson(): void
    {
        $response = $this->createResponse(200, 'not json');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);
        $result = $client->getStatus();

        self::assertSame([], $result);
    }

    public function testGetStatusResponseReturnsTypedDto(): void
    {
        $body = '{"status":true,"version":"1.0","ifu":"999","nim":"NIM01","tokenValid":"2025-12-31",'
            . '"serverDateTime":"2025-02-09","pendingRequestsCount":1,'
            . '"pendingRequestsList":[{"date":"2025-02-09","uid":"abc-123"}]}';
        $response = $this->createResponse(200, $body);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);
        $dto = $client->getStatusResponse();

        self::assertInstanceOf(StatusResponseDto::class, $dto);
        self::assertTrue($dto->status);
        self::assertSame('1.0', $dto->version);
        self::assertSame(1, $dto->pendingRequestsCount);
        self::assertSame('abc-123', $dto->pendingRequestsList[0]->uid);
    }
}
