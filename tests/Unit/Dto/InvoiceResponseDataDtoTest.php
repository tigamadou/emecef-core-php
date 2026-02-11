<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Dto;

use Emecef\Core\Dto\Response\InvoiceResponseDataDto;
use PHPUnit\Framework\TestCase;

final class InvoiceResponseDataDtoTest extends TestCase
{
    public function testFromArrayHydratesTotalsAndUid(): void
    {
        $data = [
            'uid' => 'ac33f8fe-9735-4ed6-a9c3-df58a908ccd3',
            'ta' => 0, 'tb' => 18, 'tc' => 0, 'td' => 18,
            'taa' => 1350, 'tab' => 3600, 'tac' => 0, 'tad' => 0, 'tae' => 0, 'taf' => 0,
            'hab' => 3051, 'had' => 0, 'vab' => 549, 'vad' => 0,
            'total' => 4950, 'aib' => 0, 'ts' => 0,
        ];

        $dto = InvoiceResponseDataDto::fromArray($data);

        self::assertSame('ac33f8fe-9735-4ed6-a9c3-df58a908ccd3', $dto->uid);
        self::assertSame(4950, $dto->total);
        self::assertSame(18, $dto->tb);
    }

    public function testFromArrayWithEmptyArrayUsesZeroAndNullDefaults(): void
    {
        $dto = InvoiceResponseDataDto::fromArray([]);

        self::assertNull($dto->uid);
        self::assertSame(0, $dto->ta);
        self::assertSame(0, $dto->total);
        self::assertNull($dto->errorCode);
        self::assertNull($dto->errorDesc);
    }

    public function testFromArrayWithErrorCodeAndErrorDesc(): void
    {
        $data = [
            'uid' => null,
            'errorCode' => '20',
            'errorDesc' => 'Demande introuvable',
        ];

        $dto = InvoiceResponseDataDto::fromArray($data);

        self::assertNull($dto->uid);
        self::assertSame('20', $dto->errorCode);
        self::assertSame('Demande introuvable', $dto->errorDesc);
    }

    public function testFromArrayWithNumericStringsCastsToInt(): void
    {
        $data = [
            'ta' => '18',
            'total' => '4950',
        ];

        $dto = InvoiceResponseDataDto::fromArray($data);

        self::assertSame(18, $dto->ta);
        self::assertSame(4950, $dto->total);
    }

    public function testFromArrayWithFloatValuesCastsToInt(): void
    {
        $data = [
            'total' => 4950.7,
            'taa' => 1350.0,
        ];

        $dto = InvoiceResponseDataDto::fromArray($data);

        self::assertSame(4950, $dto->total);
        self::assertSame(1350, $dto->taa);
    }

    public function testFromArrayWithInvalidNumericValuesUsesDefaultZero(): void
    {
        $data = [
            'total' => [],
            'ta' => 'not-numeric',
        ];

        $dto = InvoiceResponseDataDto::fromArray($data);

        self::assertSame(0, $dto->total);
        self::assertSame(0, $dto->ta);
    }
}
