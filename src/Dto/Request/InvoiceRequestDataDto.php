<?php

declare(strict_types=1);

namespace Emecef\Core\Dto\Request;

use Emecef\Core\Enum\AibGroupType;
use Emecef\Core\Enum\InvoiceType;

/**
 * Invoice request payload (InvoiceRequestDataDto – e-MECeF API).
 * Reference (24 chars) required for FA/EA (credit note) types.
 */
final readonly class InvoiceRequestDataDto
{
    /**
     * @param list<ItemDto> $items
     * @param list<PaymentDto>|null $payment Null = default ESPECES
     */
    public function __construct(
        public string $ifu,
        public InvoiceType $type,
        public array $items,
        public OperatorDto $operator,
        public ?ClientDto $client = null,
        public ?array $payment = null,
        public ?AibGroupType $aib = null,
        public ?string $reference = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'ifu' => $this->ifu,
            'type' => $this->type->value,
            'items' => array_map(static fn (ItemDto $item) => $item->toArray(), $this->items),
            'operator' => $this->operator->toArray(),
        ];
        if ($this->client !== null) {
            $data['client'] = $this->client->toArray();
        }
        if ($this->payment !== null && $this->payment !== []) {
            $data['payment'] = array_map(static fn (PaymentDto $payment) => $payment->toArray(), $this->payment);
        }
        if ($this->aib !== null) {
            $data['aib'] = $this->aib->value;
        }
        if ($this->reference !== null) {
            $data['reference'] = $this->reference;
        }

        return $data;
    }

    public function toJson(): string
    {
        $json = json_encode($this->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        return $json;
    }
}
