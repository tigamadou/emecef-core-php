<?php

declare(strict_types=1);

namespace Emecef\Core\Enum;

/**
 * Tax group for invoice items: A–F.
 */
enum TaxGroupType: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';
    case E = 'E';
    case F = 'F';
}
