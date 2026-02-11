<?php

declare(strict_types=1);

namespace Emecef\Core\Tests\Unit\Exception;

use Emecef\Core\Exception\ApiException;
use PHPUnit\Framework\TestCase;

final class ApiExceptionTest extends TestCase
{
    public function testGettersReturnConstructorValues(): void
    {
        $e = new ApiException('Something failed', '20', 'La facture n\'existe pas');

        self::assertSame('Something failed', $e->getMessage());
        self::assertSame('20', $e->getErrorCode());
        self::assertSame('La facture n\'existe pas', $e->getErrorDesc());
    }

    public function testErrorCodeAndDescCanBeNull(): void
    {
        $e = new ApiException('Generic error');

        self::assertNull($e->getErrorCode());
        self::assertNull($e->getErrorDesc());
    }
}
