<?php

declare(strict_types=1);

/**
 * e-MECeF API configuration (base URLs only).
 * Token and environment are provided as parameters when creating the Client.
 *
 * @return array{
 *     base_url: array{production: string, test: string},
 *     base_url_info: array{production: string, test: string},
 * }
 */
return [
    /*
     * Invoicing API base URL (no trailing slash).
     * Used for status, submit invoice, confirm, cancel.
     */
    'base_url' => [
        'production' => 'https://sygmef.impots.bj/emcf/api/invoice',
        'test' => 'https://developper.impots.bj/sygmef-emcf/api/invoice',
    ],

    /*
     * Information API base URL (no trailing slash).
     * Used for status, tax groups, invoice types, etc.
     */
    'base_url_info' => [
        'production' => 'https://sygmef.impots.bj/emcf/api/info',
        'test' => 'https://developper.impots.bj/sygmef-emcf/api/info',
    ],
];
