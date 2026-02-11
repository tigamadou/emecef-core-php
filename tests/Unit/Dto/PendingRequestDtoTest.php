<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Dto;

use Emecef\Core\Dto\Response\PendingRequestDto;
use PHPUnit\Framework\TestCase;

final class PendingRequestDtoTest extends TestCase
{
    public function testFromArrayHydratesDateAndUid(): void
    {
        $data = [
            'date' => '2025-02-09T11:45:00+01:00',
            'uid' => 'ac33f8fe-9735-4ed6-a9c3-df58a908ccd3',
        ];

        $dto = PendingRequestDto::fromArray($data);

        self::assertSame('2025-02-09T11:45:00+01:00', $dto->date);
        self::assertSame('ac33f8fe-9735-4ed6-a9c3-df58a908ccd3', $dto->uid);
    }

    public function testFromArrayWithMissingKeysUsesEmptyStringDefaults(): void
    {
        $dto = PendingRequestDto::fromArray([]);

        self::assertSame('', $dto->date);
        self::assertSame('', $dto->uid);
    }

    public function testFromArrayWithPartialDataUsesDefaultsForMissing(): void
    {
        $dto = PendingRequestDto::fromArray(['uid' => 'only-uid']);

        self::assertSame('', $dto->date);
        self::assertSame('only-uid', $dto->uid);
    }

    public function testFromArrayWithScalarValuesCastsToString(): void
    {
        $data = [
            'date' => 20250209,
            'uid' => 12345.67,
        ];

        $dto = PendingRequestDto::fromArray($data);

        self::assertSame('20250209', $dto->date);
        self::assertSame('12345.67', $dto->uid);
    }

    public function testFromArrayWithNonScalarValuesUsesDefaultEmptyString(): void
    {
        $data = [
            'date' => [],
            'uid' => new \stdClass(),
        ];

        $dto = PendingRequestDto::fromArray($data);

        self::assertSame('', $dto->date);
        self::assertSame('', $dto->uid);
    }
}
