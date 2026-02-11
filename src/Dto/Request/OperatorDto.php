<?php

declare(strict_types=1);

namespace Emecef\Core\Dto\Request;

/**
 * Operator (POS) data (OperatorDto – e-MECeF API).
 */
final readonly class OperatorDto
{
    public function __construct(
        public string $name,
        public ?string $operatorId = null,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $data = ['name' => $this->name];
        if ($this->operatorId !== null) {
            $data['id'] = $this->operatorId;
        }

        return $data;
    }
}
