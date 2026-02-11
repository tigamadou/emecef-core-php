<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Dto;

use Emecef\Core\Dto\Request\ClientDto;
use Emecef\Core\Dto\Request\InvoiceRequestDataDto;
use Emecef\Core\Dto\Request\ItemDto;
use Emecef\Core\Dto\Request\OperatorDto;
use Emecef\Core\Dto\Request\PaymentDto;
use Emecef\Core\Enum\AibGroupType;
use Emecef\Core\Enum\InvoiceType;
use Emecef\Core\Enum\PaymentType;
use Emecef\Core\Enum\TaxGroupType;
use PHPUnit\Framework\TestCase;

final class InvoiceRequestDataDtoTest extends TestCase
{
    public function testToArrayAndToJsonContainRequiredFields(): void
    {
        $request = new InvoiceRequestDataDto(
            ifu: '9999900000001',
            type: InvoiceType::FV,
            items: [
                new ItemDto('Article', 1000, 2.0, TaxGroupType::B),
            ],
            operator: new OperatorDto('Jacques')
        );

        $arr = $request->toArray();
        self::assertSame('9999900000001', $arr['ifu']);
        self::assertSame('FV', $arr['type']);
        self::assertCount(1, $arr['items']);
        self::assertSame('Article', $arr['items'][0]['name']);
        self::assertSame(1000, $arr['items'][0]['price']);
        self::assertSame('B', $arr['items'][0]['taxGroup']);
        self::assertSame('Jacques', $arr['operator']['name']);

        $json = $request->toJson();
        self::assertJson($json);
        $decoded = json_decode($json, true);
        self::assertSame('FV', $decoded['type']);
    }

    public function testToArrayIncludesClientPaymentAibAndReferenceWhenSet(): void
    {
        $request = new InvoiceRequestDataDto(
            ifu: '9999900000001',
            type: InvoiceType::FA,
            items: [new ItemDto('Article', 1000, 1.0, TaxGroupType::A)],
            operator: new OperatorDto('Op'),
            client: new ClientDto(ifu: '99999000000002', name: 'Client'),
            payment: [new PaymentDto(PaymentType::ESPECES, 1000), new PaymentDto(PaymentType::MOBILEMONEY, 500)],
            aib: AibGroupType::A,
            reference: 'X537-E4DB-AJUU-HHXN-FEKJ-12345'
        );

        $arr = $request->toArray();

        self::assertSame('99999000000002', $arr['client']['ifu']);
        self::assertSame('Client', $arr['client']['name']);
        self::assertCount(2, $arr['payment']);
        self::assertSame('ESPECES', $arr['payment'][0]['name']);
        self::assertSame(1000, $arr['payment'][0]['amount']);
        self::assertSame('MOBILEMONEY', $arr['payment'][1]['name']);
        self::assertSame('A', $arr['aib']);
        self::assertSame('X537-E4DB-AJUU-HHXN-FEKJ-12345', $arr['reference']);
    }

    public function testToArrayOmitsClientPaymentAibReferenceWhenNull(): void
    {
        $request = new InvoiceRequestDataDto(
            ifu: '9999900000001',
            type: InvoiceType::FV,
            items: [new ItemDto('Article', 1000, 1.0, TaxGroupType::B)],
            operator: new OperatorDto('Op')
        );

        $arr = $request->toArray();

        self::assertArrayNotHasKey('client', $arr);
        self::assertArrayNotHasKey('payment', $arr);
        self::assertArrayNotHasKey('aib', $arr);
        self::assertArrayNotHasKey('reference', $arr);
    }
}
