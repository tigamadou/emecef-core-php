<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Dto;

use Emecef\Core\Dto\Response\SecurityElementsDto;
use PHPUnit\Framework\TestCase;

final class SecurityElementsDtoTest extends TestCase
{
    public function testFromArrayHydratesSecurityFields(): void
    {
        $data = [
            'dateTime' => '23/11/2020 13:17:08',
            'qrCode' => 'F;IN01000005;X537...',
            'codeMECeFDGI' => 'X537-E4DB-AJUU-HHXN-FWIS-FEKJ',
            'counters' => '64/64 FV',
            'nim' => 'IN01000005',
        ];

        $dto = SecurityElementsDto::fromArray($data);

        self::assertSame('23/11/2020 13:17:08', $dto->dateTime);
        self::assertSame('X537-E4DB-AJUU-HHXN-FWIS-FEKJ', $dto->codeMECeFDGI);
        self::assertSame('64/64 FV', $dto->counters);
    }

    public function testFromArrayWithEmptyArrayUsesEmptyStringDefaults(): void
    {
        $dto = SecurityElementsDto::fromArray([]);

        self::assertSame('', $dto->dateTime);
        self::assertSame('', $dto->qrCode);
        self::assertSame('', $dto->codeMECeFDGI);
        self::assertSame('', $dto->counters);
        self::assertSame('', $dto->nim);
        self::assertNull($dto->errorCode);
        self::assertNull($dto->errorDesc);
    }

    public function testFromArrayWithErrorCodeAndErrorDesc(): void
    {
        $data = [
            'dateTime' => '23/11/2020 14:00:00',
            'qrCode' => '',
            'codeMECeFDGI' => '',
            'counters' => '',
            'nim' => '',
            'errorCode' => '21',
            'errorDesc' => 'Demande expirée',
        ];

        $dto = SecurityElementsDto::fromArray($data);

        self::assertSame('21', $dto->errorCode);
        self::assertSame('Demande expirée', $dto->errorDesc);
    }

    public function testFromArrayWithScalarValuesCastsToString(): void
    {
        $data = [
            'dateTime' => 1698765432,
            'nim' => 10000005.0,
        ];

        $dto = SecurityElementsDto::fromArray($data);

        self::assertSame('1698765432', $dto->dateTime);
        self::assertSame('10000005', $dto->nim);
    }

    public function testFromArrayWithNonScalarValuesUsesDefaultEmptyString(): void
    {
        $data = [
            'qrCode' => [],
            'codeMECeFDGI' => null,
        ];

        $dto = SecurityElementsDto::fromArray($data);

        self::assertSame('', $dto->qrCode);
        self::assertSame('', $dto->codeMECeFDGI);
    }
}
