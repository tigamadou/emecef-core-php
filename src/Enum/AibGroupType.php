<?php

declare(strict_types=1);

namespace Emecef\Core\Enum;

/**
 * AIB group type: A (1%) or B (5%).
 */
enum AibGroupType: string
{
    case A = 'A';
    case B = 'B';
}
