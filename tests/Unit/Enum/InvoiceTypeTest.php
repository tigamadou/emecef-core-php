<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Enum;

use Emecef\Core\Enum\InvoiceType;
use PHPUnit\Framework\TestCase;

final class InvoiceTypeTest extends TestCase
{
    public function testValuesMatchApiSpec(): void
    {
        self::assertSame('FV', InvoiceType::FV->value);
        self::assertSame('EV', InvoiceType::EV->value);
        self::assertSame('FA', InvoiceType::FA->value);
        self::assertSame('EA', InvoiceType::EA->value);
    }

    public function testTryFromAcceptsValidStrings(): void
    {
        self::assertSame(InvoiceType::FV, InvoiceType::tryFrom('FV'));
        self::assertSame(InvoiceType::FA, InvoiceType::tryFrom('FA'));
    }

    public function testTryFromReturnsNullForInvalid(): void
    {
        self::assertNull(InvoiceType::tryFrom('XX'));
    }
}
