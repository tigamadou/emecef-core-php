<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Dto;

use Emecef\Core\Dto\Request\ItemDto;
use Emecef\Core\Enum\TaxGroupType;
use PHPUnit\Framework\TestCase;

final class ItemDtoTest extends TestCase
{
    public function testToArrayContainsRequiredFieldsOnlyWhenOptionalNull(): void
    {
        $item = new ItemDto(
            name: 'Jus d\'orange',
            price: 1800,
            quantity: 2.0,
            taxGroup: TaxGroupType::B
        );

        $arr = $item->toArray();

        self::assertSame('Jus d\'orange', $arr['name']);
        self::assertSame(1800, $arr['price']);
        self::assertSame(2.0, $arr['quantity']);
        self::assertSame('B', $arr['taxGroup']);
        self::assertArrayNotHasKey('code', $arr);
        self::assertArrayNotHasKey('taxSpecific', $arr);
        self::assertArrayNotHasKey('originalPrice', $arr);
        self::assertArrayNotHasKey('priceModification', $arr);
    }

    public function testToArrayIncludesOptionalFieldsWhenSet(): void
    {
        $item = new ItemDto(
            name: 'Article',
            price: 1000,
            quantity: 1.5,
            taxGroup: TaxGroupType::A,
            code: 'SKU-001',
            taxSpecific: 50,
            originalPrice: 1200,
            priceModification: 'Remise 10%'
        );

        $arr = $item->toArray();

        self::assertSame('SKU-001', $arr['code']);
        self::assertSame(50, $arr['taxSpecific']);
        self::assertSame(1200, $arr['originalPrice']);
        self::assertSame('Remise 10%', $arr['priceModification']);
    }
}
