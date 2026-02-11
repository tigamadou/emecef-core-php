<?php

declare(strict_types=1);

namespace Emecef\Core\Dto\Response;

/**
 * Pending invoice request (PendingRequestDto – e-MECeF API).
 */
final readonly class PendingRequestDto
{
    public function __construct(
        public string $date,
        public string $uid,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            self::toString($data['date'] ?? null),
            self::toString($data['uid'] ?? null)
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
