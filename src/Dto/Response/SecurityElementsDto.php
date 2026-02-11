<?php

declare(strict_types=1);

namespace Emecef\Core\Dto\Response;

/**
 * Security elements from confirm/cancel (SecurityElementsDto – e-MECeF API).
 * qrCode and codeMECeFDGI are empty when the invoice was cancelled.
 */
final readonly class SecurityElementsDto
{
    public function __construct(
        public string $dateTime,
        public string $qrCode,
        public string $codeMECeFDGI,
        public string $counters,
        public string $nim,
        public ?string $errorCode = null,
        public ?string $errorDesc = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            self::toString($data['dateTime'] ?? null),
            self::toString($data['qrCode'] ?? null),
            self::toString($data['codeMECeFDGI'] ?? null),
            self::toString($data['counters'] ?? null),
            self::toString($data['nim'] ?? null),
            isset($data['errorCode']) && is_string($data['errorCode']) ? $data['errorCode'] : null,
            isset($data['errorDesc']) && is_string($data['errorDesc']) ? $data['errorDesc'] : null
        );
    }

    private static function toString(mixed $value, string $default = ''): string
    {
        if (is_string($value)) {
            return $value;
        }
        if (is_int($value) || is_float($value) || is_bool($value)) {
            return (string) $value;
        }

        return $default;
    }
}
