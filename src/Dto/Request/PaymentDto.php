<?php

declare(strict_types=1);

namespace Emecef\Core\Dto\Request;

use Emecef\Core\Enum\PaymentType;

/**
 * Payment entry (PaymentDto – e-MECeF API).
 */
final readonly class PaymentDto
{
    public function __construct(
        public PaymentType $name,
        public int $amount,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name->value,
            'amount' => $this->amount,
        ];
    }
}
