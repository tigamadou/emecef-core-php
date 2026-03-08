<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Api;

use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Dto\Response\SecurityElementsDto;
use Emecef\Core\Exception\ApiException;

/**
 * Intent: Cancel invoice API — request shape, success and error response.
 */
final class ClientCancelTest extends ClientApiTestCase
{
    public function testSendsPutToAnnulerEndpointAndReturnsDecodedResponse(): void
    {
        $uid = '437261A6-41BD-4D7B-B61B-E60C2D8089AA';
        $responseBody = '{"dateTime":"23/11/2020 14:00:00","codeMECeFDGI":"","qrCode":""}';
        $response = $this->createResponse(200, $responseBody);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(self::once())
            ->method('request')
            ->with(
                'PUT',
                self::BASE_URL . '/' . $uid . '/cancel',
                $this->defaultHeaders(),
                null
            )
            ->willReturn($response);

        $client = $this->createClient($httpClient);
        $result = $client->cancel($uid);

        self::assertSame('23/11/2020 14:00:00', $result['dateTime']);
        self::assertSame('', $result['codeMECeFDGI']);
    }

    public function testThrowsApiExceptionOnError(): void
    {
        $uid = 'expired-uid';
        $response = $this->createResponse(410, '{"errorCode":"21","errorDesc":"Demande expirée"}');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Demande expirée');

        $client->cancel($uid);
    }

    public function testCancelResponseReturnsTypedSecurityElementsDto(): void
    {
        $uid = '437261A6-41BD-4D7B-B61B-E60C2D8089AA';
        $responseBody = '{"dateTime":"23/11/2020 14:00:00","qrCode":"","codeMECeFDGI":"",'
            . '"counters":"64/64 FV","nim":"IN01000005"}';
        $response = $this->createResponse(200, $responseBody);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);
        $dto = $client->cancelResponse($uid);

        self::assertInstanceOf(SecurityElementsDto::class, $dto);
        self::assertSame('', $dto->qrCode);
        self::assertSame('', $dto->codeMECeFDGI);
    }
}
