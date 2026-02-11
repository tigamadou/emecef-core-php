<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Dto;

use Emecef\Core\Dto\Response\StatusResponseDto;
use PHPUnit\Framework\TestCase;

final class StatusResponseDtoTest extends TestCase
{
    public function testFromArrayHydratesAllFields(): void
    {
        $data = [
            'status' => true,
            'version' => '1.0',
            'ifu' => '99999000000001',
            'nim' => 'XX01000001',
            'tokenValid' => '2025-12-31T00:00:00+01:00',
            'serverDateTime' => '2025-02-09T12:00:00+01:00',
            'pendingRequestsCount' => 2,
            'pendingRequestsList' => [
                ['date' => '2025-02-09T11:45:00+01:00', 'uid' => 'uid-1'],
                ['date' => '2025-02-09T11:50:00+01:00', 'uid' => 'uid-2'],
            ],
        ];

        $dto = StatusResponseDto::fromArray($data);

        self::assertTrue($dto->status);
        self::assertSame('1.0', $dto->version);
        self::assertSame('99999000000001', $dto->ifu);
        self::assertSame('XX01000001', $dto->nim);
        self::assertSame(2, $dto->pendingRequestsCount);
        self::assertCount(2, $dto->pendingRequestsList);
        self::assertSame('uid-1', $dto->pendingRequestsList[0]->uid);
        self::assertSame('uid-2', $dto->pendingRequestsList[1]->uid);
    }

    public function testFromArrayWithEmptyPendingList(): void
    {
        $data = [
            'status' => true,
            'version' => '1.0',
            'ifu' => '999',
            'nim' => 'NIM',
            'tokenValid' => '2025-12-31',
            'serverDateTime' => '2025-02-09',
            'pendingRequestsCount' => 0,
            'pendingRequestsList' => [],
        ];

        $dto = StatusResponseDto::fromArray($data);

        self::assertCount(0, $dto->pendingRequestsList);
    }

    public function testFromArrayWithNonArrayPendingListTreatsAsEmpty(): void
    {
        $data = [
            'status' => true,
            'version' => '1.0',
            'ifu' => '999',
            'nim' => 'NIM',
            'tokenValid' => '2025-12-31',
            'serverDateTime' => '2025-02-09',
            'pendingRequestsCount' => 0,
            'pendingRequestsList' => 'not-an-array',
        ];

        $dto = StatusResponseDto::fromArray($data);

        self::assertCount(0, $dto->pendingRequestsList);
    }

    public function testFromArrayWithMissingPendingListUsesEmptyArray(): void
    {
        $data = [
            'status' => false,
            'version' => '',
            'ifu' => '',
            'nim' => '',
            'tokenValid' => '',
            'serverDateTime' => '',
        ];

        $dto = StatusResponseDto::fromArray($data);

        self::assertCount(0, $dto->pendingRequestsList);
    }

    public function testFromArrayWithFloatPendingRequestsCountCastsToInt(): void
    {
        $data = [
            'status' => true,
            'version' => '1.0',
            'ifu' => '999',
            'nim' => 'NIM',
            'tokenValid' => '2025-12-31',
            'serverDateTime' => '2025-02-09',
            'pendingRequestsCount' => 3.0,
            'pendingRequestsList' => [],
        ];

        $dto = StatusResponseDto::fromArray($data);

        self::assertSame(3, $dto->pendingRequestsCount);
    }

    public function testFromArrayWithScalarStringFieldsCastsToString(): void
    {
        $data = [
            'status' => true,
            'version' => 1,
            'ifu' => 99999000000001,
            'nim' => 1000001.0,
            'tokenValid' => '2025-12-31',
            'serverDateTime' => '2025-02-09',
            'pendingRequestsCount' => 0,
            'pendingRequestsList' => [],
        ];

        $dto = StatusResponseDto::fromArray($data);

        self::assertSame('1', $dto->version);
        self::assertSame('99999000000001', $dto->ifu);
        self::assertSame('1000001', $dto->nim);
    }

    public function testFromArrayWithInvalidPendingRequestsCountUsesDefaultZero(): void
    {
        $data = [
            'status' => true,
            'version' => '1.0',
            'ifu' => '999',
            'nim' => 'NIM',
            'tokenValid' => '2025-12-31',
            'serverDateTime' => '2025-02-09',
            'pendingRequestsCount' => 'not-a-number',
            'pendingRequestsList' => [],
        ];

        $dto = StatusResponseDto::fromArray($data);

        self::assertSame(0, $dto->pendingRequestsCount);
    }

    public function testFromArrayWithNumericStringPendingRequestsCountCastsToInt(): void
    {
        $data = [
            'status' => true,
            'version' => '1.0',
            'ifu' => '999',
            'nim' => 'NIM',
            'tokenValid' => '2025-12-31',
            'serverDateTime' => '2025-02-09',
            'pendingRequestsCount' => '5',
            'pendingRequestsList' => [],
        ];

        $dto = StatusResponseDto::fromArray($data);

        self::assertSame(5, $dto->pendingRequestsCount);
    }

    public function testFromArrayWithBoolAndNullStringFieldsCastsOrDefaults(): void
    {
        $data = [
            'status' => true,
            'version' => true,
            'ifu' => null,
            'nim' => 'NIM',
            'tokenValid' => '2025-12-31',
            'serverDateTime' => [],
            'pendingRequestsCount' => 0,
            'pendingRequestsList' => [],
        ];

        $dto = StatusResponseDto::fromArray($data);

        self::assertSame('1', $dto->version);
        self::assertSame('', $dto->ifu);
        self::assertSame('', $dto->serverDateTime);
    }
}
