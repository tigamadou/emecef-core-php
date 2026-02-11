<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Enum;

use Emecef\Core\Enum\PaymentType;
use PHPUnit\Framework\TestCase;

final class PaymentTypeTest extends TestCase
{
    public function testValuesMatchApiSpec(): void
    {
        self::assertSame('ESPECES', PaymentType::ESPECES->value);
        self::assertSame('VIREMENT', PaymentType::VIREMENT->value);
        self::assertSame('CARTEBANCAIRE', PaymentType::CARTEBANCAIRE->value);
        self::assertSame('MOBILEMONEY', PaymentType::MOBILEMONEY->value);
        self::assertSame('CHEQUES', PaymentType::CHEQUES->value);
        self::assertSame('CREDIT', PaymentType::CREDIT->value);
        self::assertSame('AUTRE', PaymentType::AUTRE->value);
    }
}
