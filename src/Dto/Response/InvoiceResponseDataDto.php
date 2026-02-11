<?php

declare(strict_types=1);

namespace Emecef\Core\Dto\Response;

/**
 * Invoice submit response (InvoiceResponseDataDto – e-MECeF API).
 * Field names (ta, tb, tc, td, ts) match the API specification.
 *
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
final readonly class InvoiceResponseDataDto
{
    public function __construct(
        public ?string $uid,
        public int $ta,
        public int $tb,
        public int $tc,
        public int $td,
        public int $taa,
        public int $tab,
        public int $tac,
        public int $tad,
        public int $tae,
        public int $taf,
        public int $hab,
        public int $had,
        public int $vab,
        public int $vad,
        public int $aib,
        public int $ts,
        public int $total,
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
            isset($data['uid']) && is_string($data['uid']) ? $data['uid'] : null,
            self::toInt($data['ta'] ?? null),
            self::toInt($data['tb'] ?? null),
            self::toInt($data['tc'] ?? null),
            self::toInt($data['td'] ?? null),
            self::toInt($data['taa'] ?? null),
            self::toInt($data['tab'] ?? null),
            self::toInt($data['tac'] ?? null),
            self::toInt($data['tad'] ?? null),
            self::toInt($data['tae'] ?? null),
            self::toInt($data['taf'] ?? null),
            self::toInt($data['hab'] ?? null),
            self::toInt($data['had'] ?? null),
            self::toInt($data['vab'] ?? null),
            self::toInt($data['vad'] ?? null),
            self::toInt($data['aib'] ?? null),
            self::toInt($data['ts'] ?? null),
            self::toInt($data['total'] ?? null),
            isset($data['errorCode']) && is_string($data['errorCode']) ? $data['errorCode'] : null,
            isset($data['errorDesc']) && is_string($data['errorDesc']) ? $data['errorDesc'] : null
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
}
