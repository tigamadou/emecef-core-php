<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Enum;

use Emecef\Core\Enum\TaxGroupType;
use PHPUnit\Framework\TestCase;

final class TaxGroupTypeTest extends TestCase
{
    public function testValuesMatchApiSpec(): void
    {
        self::assertSame('A', TaxGroupType::A->value);
        self::assertSame('B', TaxGroupType::B->value);
        self::assertSame('C', TaxGroupType::C->value);
        self::assertSame('D', TaxGroupType::D->value);
        self::assertSame('E', TaxGroupType::E->value);
        self::assertSame('F', TaxGroupType::F->value);
    }
}
