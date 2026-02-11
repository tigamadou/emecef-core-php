<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Api;

use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Dto\Response\SecurityElementsDto;
use Emecef\Core\Exception\ApiException;

/**
 * Intent: Confirm invoice API — request shape, success and error response.
 */
final class ClientConfirmTest extends ClientApiTestCase
{
    public function testSendsPutToConfirmerEndpointAndReturnsDecodedResponse(): void
    {
        $uid = 'ac33f8fe-9735-4ed6-a9c3-df58a908ccd3';
        $responseBody = '{"dateTime":"23/11/2020 13:17:08","qrCode":"F;IN01000005;X537",'
            . '"codeMECeFDGI":"X537-E4DB-AJUU","counters":"64/64 FV","nim":"IN01000005"}';
        $response = $this->createResponse(200, $responseBody);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(self::once())
            ->method('request')
            ->with(
                'PUT',
                self::BASE_URL . '/' . $uid . '/confirmer',
                $this->defaultHeaders(),
                null
            )
            ->willReturn($response);

        $client = $this->createClient($httpClient);
        $result = $client->confirm($uid);

        self::assertSame('23/11/2020 13:17:08', $result['dateTime']);
        self::assertSame('X537-E4DB-AJUU', $result['codeMECeFDGI']);
        self::assertSame('IN01000005', $result['nim']);
    }

    public function testThrowsApiExceptionOnError(): void
    {
        $uid = 'invalid-uid';
        $response = $this->createResponse(404, '{"errorCode":"20","errorDesc":"Demande introuvable"}');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Demande introuvable');

        $client->confirm($uid);
    }

    public function testConfirmResponseReturnsTypedSecurityElementsDto(): void
    {
        $uid = 'ac33f8fe-9735-4ed6-a9c3-df58a908ccd3';
        $responseBody = '{"dateTime":"23/11/2020 13:17:08","qrCode":"QR","codeMECeFDGI":"CODE",'
            . '"counters":"64/64 FV","nim":"IN01000005"}';
        $response = $this->createResponse(200, $responseBody);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);
        $dto = $client->confirmResponse($uid);

        self::assertInstanceOf(SecurityElementsDto::class, $dto);
        self::assertSame('CODE', $dto->codeMECeFDGI);
        self::assertSame('QR', $dto->qrCode);
    }
}
