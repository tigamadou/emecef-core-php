<?php

declare(strict_types=1);

namespace Emecef\Core\Contract;

use Throwable;

/**
 * Marker for exceptions that represent e-MECeF API errors (errorCode/errorDesc).
 */
interface ApiExceptionInterface extends Throwable
{
    public function getErrorCode(): ?string;

    public function getErrorDesc(): ?string;
}
