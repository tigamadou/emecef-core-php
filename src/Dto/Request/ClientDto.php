<?php

declare(strict_types=1);

namespace Emecef\Core\Dto\Request;

/**
 * Client (buyer) data (ClientDto – e-MECeF API). All fields optional.
 */
final readonly class ClientDto
{
    public function __construct(
        public ?string $ifu = null,
        public ?string $name = null,
        public ?string $contact = null,
        public ?string $address = null,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $data = [];
        if ($this->ifu !== null) {
            $data['ifu'] = $this->ifu;
        }
        if ($this->name !== null) {
            $data['name'] = $this->name;
        }
        if ($this->contact !== null) {
            $data['contact'] = $this->contact;
        }
        if ($this->address !== null) {
            $data['address'] = $this->address;
        }

        return $data;
    }
}
