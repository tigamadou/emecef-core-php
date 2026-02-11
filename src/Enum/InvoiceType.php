<?php

declare(strict_types=1);

namespace Emecef\Core\Enum;

/**
 * Invoice type (e-MECeF API).
 * FV = Facture de vente, EV = Vente à l'exportation, FA = Facture d'avoir, EA = Avoir à l'exportation.
 */
enum InvoiceType: string
{
    case FV = 'FV';
    case EV = 'EV';
    case FA = 'FA';
    case EA = 'EA';
}
