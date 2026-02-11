<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit;

use Emecef\Core\Client;
use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Contract\LoggerInterface;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testIsConfiguredReturnsTrueWhenBaseUrlAndTokenNonEmpty(): void
    {
        $client = new Client(
            'https://sygmef.impots.bj/emcf/api/invoice',
            'fake-jwt-token',
            $this->createStub(HttpClientInterface::class),
            $this->createStub(LoggerInterface::class)
        );

        self::assertTrue($client->isConfigured());
        self::assertSame('https://sygmef.impots.bj/emcf/api/invoice', $client->getBaseUrl());
    }

    public function testIsConfiguredReturnsFalseWhenBaseUrlEmpty(): void
    {
        $client = new Client(
            '',
            'token',
            $this->createStub(HttpClientInterface::class),
            $this->createStub(LoggerInterface::class)
        );

        self::assertFalse($client->isConfigured());
    }

    public function testIsConfiguredReturnsFalseWhenTokenEmpty(): void
    {
        $client = new Client(
            'https://sygmef.impots.bj/emcf/api/invoice',
            '',
            $this->createStub(HttpClientInterface::class),
            $this->createStub(LoggerInterface::class)
        );

        self::assertFalse($client->isConfigured());
    }

    public function testIsConfiguredReturnsFalseWhenBothBaseUrlAndTokenEmpty(): void
    {
        $client = new Client(
            '',
            '',
            $this->createStub(HttpClientInterface::class),
            $this->createStub(LoggerInterface::class)
        );

        self::assertFalse($client->isConfigured());
    }

    public function testGetBaseUrlReturnsConstructorValue(): void
    {
        $baseUrl = 'https://api.example.com/v1';
        $client = new Client(
            $baseUrl,
            'token',
            $this->createStub(HttpClientInterface::class),
            $this->createStub(LoggerInterface::class)
        );

        self::assertSame($baseUrl, $client->getBaseUrl());
    }

    public function testGetHttpClientReturnsInjectedInstance(): void
    {
        $httpClient = $this->createStub(HttpClientInterface::class);
        $client = new Client(
            'https://api.example.com',
            'token',
            $httpClient,
            $this->createStub(LoggerInterface::class)
        );

        self::assertSame($httpClient, $client->getHttpClient());
    }

    public function testGetLoggerReturnsInjectedInstance(): void
    {
        $logger = $this->createStub(LoggerInterface::class);
        $client = new Client(
            'https://api.example.com',
            'token',
            $this->createStub(HttpClientInterface::class),
            $logger
        );

        self::assertSame($logger, $client->getLogger());
    }
}
