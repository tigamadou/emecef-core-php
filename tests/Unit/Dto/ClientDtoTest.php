<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Dto;

use Emecef\Core\Dto\Request\ClientDto;
use PHPUnit\Framework\TestCase;

final class ClientDtoTest extends TestCase
{
    public function testToArrayReturnsEmptyWhenAllNull(): void
    {
        $client = new ClientDto();

        self::assertSame([], $client->toArray());
    }

    public function testToArrayIncludesOnlySetFields(): void
    {
        $client = new ClientDto(
            ifu: '99999000000002',
            name: 'Nom du client',
            contact: '45661122',
            address: 'Rue d\'ananas 23'
        );

        $arr = $client->toArray();

        self::assertSame('99999000000002', $arr['ifu']);
        self::assertSame('Nom du client', $arr['name']);
        self::assertSame('45661122', $arr['contact']);
        self::assertSame('Rue d\'ananas 23', $arr['address']);
    }

    public function testToArrayOmitsNullFields(): void
    {
        $client = new ClientDto(ifu: '99999000000002', name: null, contact: null, address: null);

        $arr = $client->toArray();

        self::assertSame('99999000000002', $arr['ifu']);
        self::assertArrayNotHasKey('name', $arr);
        self::assertArrayNotHasKey('contact', $arr);
        self::assertArrayNotHasKey('address', $arr);
    }
}
