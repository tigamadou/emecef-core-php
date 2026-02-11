<?php

declare(strict_types=1);

namespace Emecef\Core\Dto\Response;

/**
 * API status response (StatusResponseDto – e-MECeF API).
 *
 * @param list<PendingRequestDto> $pendingRequestsList
 */
final readonly class StatusResponseDto
{
    /**
     * @param list<PendingRequestDto> $pendingRequestsList
     */
    public function __construct(
        public bool $status,
        public string $version,
        public string $ifu,
        public string $nim,
        public string $tokenValid,
        public string $serverDateTime,
        public int $pendingRequestsCount,
        public array $pendingRequestsList,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Named constructor pattern
     */
    public static function fromArray(array $data): self
    {
        $raw = $data['pendingRequestsList'] ?? [];
        $list = [];
        if (is_array($raw)) {
            foreach ($raw as $item) {
                if (is_array($item)) {
                    $list[] = PendingRequestDto::fromArray($item);
                }
            }
        }

        return new self(
            (bool) ($data['status'] ?? false),
            self::toString($data['version'] ?? null),
            self::toString($data['ifu'] ?? null),
            self::toString($data['nim'] ?? null),
            self::toString($data['tokenValid'] ?? null),
            self::toString($data['serverDateTime'] ?? null),
            self::toInt($data['pendingRequestsCount'] ?? null),
            $list
        );
    }

    private static function toInt(mixed $value, int $default = 0): int
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_string($value) && is_numeric($value)) {
            return (int) $value;
        }
        if (is_float($value)) {
            return (int) $value;
        }

        return $default;
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
