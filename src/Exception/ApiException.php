<?php

declare(strict_types=1);

namespace Emecef\Core\Exception;

use Emecef\Core\Contract\ApiExceptionInterface;
use RuntimeException;

/**
 * Thrown when the e-MECeF API returns an error (4xx/5xx or errorCode in body).
 */
final class ApiException extends RuntimeException implements ApiExceptionInterface
{
    public function __construct(
        string $message,
        private readonly ?string $errorCode = null,
        private readonly ?string $errorDesc = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorDesc(): ?string
    {
        return $this->errorDesc;
    }
}
