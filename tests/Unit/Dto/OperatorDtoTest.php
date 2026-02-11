<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Dto;

use Emecef\Core\Dto\Request\OperatorDto;
use PHPUnit\Framework\TestCase;

final class OperatorDtoTest extends TestCase
{
    public function testToArrayContainsOnlyNameWhenOperatorIdNull(): void
    {
        $operator = new OperatorDto('Jacques');

        $arr = $operator->toArray();

        self::assertSame('Jacques', $arr['name']);
        self::assertArrayNotHasKey('id', $arr);
    }

    public function testToArrayIncludesIdWhenOperatorIdSet(): void
    {
        $operator = new OperatorDto('Jacques', 'op-001');

        $arr = $operator->toArray();

        self::assertSame('Jacques', $arr['name']);
        self::assertSame('op-001', $arr['id']);
    }
}
