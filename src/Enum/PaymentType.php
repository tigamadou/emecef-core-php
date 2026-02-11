<?php

declare(strict_types=1);

namespace Emecef\Core\Enum;

/**
 * Payment type (e-MECeF API).
 */
enum PaymentType: string
{
    case ESPECES = 'ESPECES';
    case VIREMENT = 'VIREMENT';
    case CARTEBANCAIRE = 'CARTEBANCAIRE';
    case MOBILEMONEY = 'MOBILEMONEY';
    case CHEQUES = 'CHEQUES';
    case CREDIT = 'CREDIT';
    case AUTRE = 'AUTRE';
}
