<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit;

use Emecef\Core\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public function testFromDefaultLoadsPackageConfig(): void
    {
        $config = Config::fromDefault();

        self::assertSame('https://sygmef.impots.bj/emcf/api/invoice', $config->getBaseUrl('production'));
        self::assertSame('https://developper.impots.bj/sygmef-emcf/api/invoice', $config->getBaseUrl('test'));
        self::assertSame('https://sygmef.impots.bj/emcf/api/info', $config->getBaseUrlInfo('production'));
        self::assertSame('https://developper.impots.bj/sygmef-emcf/api/info', $config->getBaseUrlInfo('test'));
    }

    public function testGetBaseUrlDefaultsToTest(): void
    {
        $config = Config::fromDefault();

        self::assertSame($config->getBaseUrl('test'), $config->getBaseUrl());
    }

    public function testFromArrayReturnsConfiguredBaseUrls(): void
    {
        $config = Config::fromArray([
            'base_url' => ['production' => 'https://custom-invoice', 'test' => 'https://custom-test'],
            'base_url_info' => ['production' => 'https://custom-info', 'test' => 'https://custom-info-test'],
        ]);

        self::assertSame('https://custom-invoice', $config->getBaseUrl('production'));
        self::assertSame('https://custom-test', $config->getBaseUrl('test'));
        self::assertSame('https://custom-info', $config->getBaseUrlInfo('production'));
        self::assertSame('https://custom-info-test', $config->getBaseUrlInfo('test'));
    }
}
