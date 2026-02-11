<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Enum;

use Emecef\Core\Enum\AibGroupType;
use PHPUnit\Framework\TestCase;

final class AibGroupTypeTest extends TestCase
{
    public function testValuesMatchApiSpec(): void
    {
        self::assertSame('A', AibGroupType::A->value);
        self::assertSame('B', AibGroupType::B->value);
    }
}
