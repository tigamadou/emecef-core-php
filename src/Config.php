<?php

declare(strict_types=1);

namespace Emecef\Core;

/**
 * e-MECeF API configuration. Loads base URLs from config file.
 * Token and environment are provided as parameters by the caller (no env vars).
 */
final class Config
{
    public const DEFAULT_ENV = 'test';

    /**
     * @param array{
     *     base_url: array{production: string, test: string},
     *     base_url_info: array{production: string, test: string},
     * } $data
     */
    public function __construct(
        private readonly array $data
    ) {
    }

    /**
     * Load configuration from the package default config file.
     */
    public static function fromDefault(): self
    {
        $path = dirname(__DIR__) . '/config/emecef.php';
        /** @var array{base_url: array{production: string, test: string}, base_url_info: array{production: string, test: string}} $data */
        $data = require $path;

        return new self($data);
    }

    /**
     * Load configuration from a custom array (e.g. from framework config).
     *
     * @param array{
     *     base_url: array{production: string, test: string},
     *     base_url_info: array{production: string, test: string}
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    /**
     * Invoicing API base URL for the given environment (no trailing slash).
     */
    public function getBaseUrl(string $environment = self::DEFAULT_ENV): string
    {
        return $this->data['base_url'][$environment] ?? $this->data['base_url'][self::DEFAULT_ENV];
    }

    /**
     * Information API base URL for the given environment (no trailing slash).
     */
    public function getBaseUrlInfo(string $environment = self::DEFAULT_ENV): string
    {
        return $this->data['base_url_info'][$environment]
            ?? $this->data['base_url_info'][self::DEFAULT_ENV];
    }
}
