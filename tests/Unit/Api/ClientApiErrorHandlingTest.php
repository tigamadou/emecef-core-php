<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Api;

use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Contract\TransportExceptionInterface;
use Emecef\Core\Exception\ApiException;

/**
 * Intent: Shared API error and transport behavior — ApiException from non-2xx, transport propagation.
 */
final class ClientApiErrorHandlingTest extends ClientApiTestCase
{
    public function testThrowsApiExceptionOnNon2xxWithErrorCodeAndDescInBody(): void
    {
        $body = '{"errorCode":"20","errorDesc":"La facture n\'existe pas"}';
        $response = $this->createResponse(404, $body);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage("La facture n'existe pas");
        $this->expectExceptionCode(0);

        $client->getStatus();
    }

    public function testApiExceptionExposesErrorCodeAndDescGetters(): void
    {
        $body = '{"errorCode":"401","errorDesc":"Token expiré"}';
        $response = $this->createResponse(401, $body);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);

        try {
            $client->getStatus();
            self::fail('Expected ApiException');
        } catch (ApiException $e) {
            self::assertSame('401', $e->getErrorCode());
            self::assertSame('Token expiré', $e->getErrorDesc());
        }
    }

    public function testThrowsApiExceptionOnNon2xxWithNonJsonBody(): void
    {
        $response = $this->createResponse(500, 'Internal Server Error');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('HTTP 500');

        $client->getStatus();
    }

    public function testTransportExceptionIsPropagated(): void
    {
        $transportException = new class extends \RuntimeException implements TransportExceptionInterface {
        };

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willThrowException($transportException);

        $client = $this->createClient($httpClient);

        $this->expectException(TransportExceptionInterface::class);

        $client->getStatus();
    }
}
