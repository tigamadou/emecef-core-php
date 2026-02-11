<?php

declare(strict_types=1);

namespace Emecef\Core\Contract;

/**
 * Immutable HTTP response representation.
 */
interface HttpResponseInterface
{
    public function getStatusCode(): int;

    /**
     * @return array<string, list<string>>
     */
    public function getHeaders(): array;

    public function getBody(): string;
}
