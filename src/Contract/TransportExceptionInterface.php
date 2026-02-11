<?php

declare(strict_types=1);

namespace Emecef\Core\Contract;

use Throwable;

/**
 * Thrown when HTTP transport fails (network, timeout, connection refused, etc.).
 * Core SDK uses this interface; adapters may map it to framework-specific exceptions.
 */
interface TransportExceptionInterface extends Throwable
{
}
