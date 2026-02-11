<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Dto;

use Emecef\Core\Dto\Request\PaymentDto;
use Emecef\Core\Enum\PaymentType;
use PHPUnit\Framework\TestCase;

final class PaymentDtoTest extends TestCase
{
    public function testToArrayContainsNameAndAmount(): void
    {
        $payment = new PaymentDto(PaymentType::ESPECES, 4950);

        $arr = $payment->toArray();

        self::assertSame('ESPECES', $arr['name']);
        self::assertSame(4950, $arr['amount']);
    }

    public function testToArrayWithMobileMoney(): void
    {
        $payment = new PaymentDto(PaymentType::MOBILEMONEY, 10000);

        $arr = $payment->toArray();

        self::assertSame('MOBILEMONEY', $arr['name']);
        self::assertSame(10000, $arr['amount']);
    }
}
