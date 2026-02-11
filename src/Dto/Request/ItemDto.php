<?php

declare(strict_types=1);

namespace Emecef\Core\Dto\Request;

use Emecef\Core\Enum\TaxGroupType;

/**
 * Invoice line item (ItemDto – e-MECeF API).
 */
final readonly class ItemDto
{
    public function __construct(
        public string $name,
        public int $price,
        public float $quantity,
        public TaxGroupType $taxGroup,
        public ?string $code = null,
        public ?int $taxSpecific = null,
        public ?int $originalPrice = null,
        public ?string $priceModification = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'taxGroup' => $this->taxGroup->value,
        ];
        if ($this->code !== null) {
            $data['code'] = $this->code;
        }
        if ($this->taxSpecific !== null) {
            $data['taxSpecific'] = $this->taxSpecific;
        }
        if ($this->originalPrice !== null) {
            $data['originalPrice'] = $this->originalPrice;
        }
        if ($this->priceModification !== null) {
            $data['priceModification'] = $this->priceModification;
        }

        return $data;
    }
}
