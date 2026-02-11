<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Api;

use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Dto\Request\InvoiceRequestDataDto;
use Emecef\Core\Dto\Request\ItemDto;
use Emecef\Core\Dto\Request\OperatorDto;
use Emecef\Core\Dto\Response\InvoiceResponseDataDto;
use Emecef\Core\Enum\InvoiceType;
use Emecef\Core\Enum\TaxGroupType;
use Emecef\Core\Exception\ApiException;

/**
 * Intent: Submit invoice API — request shape, success and error response.
 */
final class ClientSubmitInvoiceTest extends ClientApiTestCase
{
    public function testSendsPostWithJsonBodyAndReturnsDecodedResponse(): void
    {
        $requestBody = '{"ifu":"9999900000001","type":"FV","items":[],"operator":{"name":"Op"}}';
        $responseBody = '{"uid":"ac33f8fe-9735-4ed6-a9c3-df58a908ccd3","totalAmount":1000}';
        $response = $this->createResponse(200, $responseBody);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(self::once())
            ->method('request')
            ->with(
                'POST',
                self::BASE_URL . '/',
                $this->defaultHeaders(),
                $requestBody
            )
            ->willReturn($response);

        $client = $this->createClient($httpClient);
        $result = $client->submitInvoice($requestBody);

        self::assertSame('ac33f8fe-9735-4ed6-a9c3-df58a908ccd3', $result['uid']);
        self::assertSame(1000, $result['totalAmount']);
    }

    public function testThrowsApiExceptionOn4xx(): void
    {
        $body = '{"errorCode":"400","errorDesc":"Invalid payload"}';
        $response = $this->createResponse(400, $body);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($response);

        $client = $this->createClient($httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid payload');

        $client->submitInvoice('{}');
    }

    public function testSubmitInvoiceRequestSendsDtoAsJsonAndReturnsTypedDto(): void
    {
        $request = new InvoiceRequestDataDto(
            ifu: '9999900000001',
            type: InvoiceType::FV,
            items: [new ItemDto('Article', 1000, 2, TaxGroupType::B)],
            operator: new OperatorDto('Op')
        );
        $responseBody = '{"uid":"uid-123","ta":0,"tb":18,"tc":0,"td":0,"taa":0,"tab":0,"tac":0,"tad":0,"tae":0,"taf":0,'
            . '"hab":0,"had":0,"vab":0,"vad":0,"aib":0,"ts":0,"total":2000}';
        $response = $this->createResponse(200, $responseBody);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects(self::once())
            ->method('request')
            ->with(
                'POST',
                self::BASE_URL . '/',
                $this->defaultHeaders(),
                $request->toJson()
            )
            ->willReturn($response);

        $client = $this->createClient($httpClient);
        $dto = $client->submitInvoiceRequest($request);

        self::assertInstanceOf(InvoiceResponseDataDto::class, $dto);
        self::assertSame('uid-123', $dto->uid);
        self::assertSame(2000, $dto->total);
    }
}
