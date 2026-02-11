# emecef-core

**Framework-agnostic Core SDK** for the Benin Republic **e-MECeF** (Electronic Tax Invoice) system.

Part of the [e-MECeF SDK ecosystem](https://github.com/your-org/emecef). This package contains **all e-MECeF protocol logic**; framework adapters (Laravel, Symfony, etc.) and CMS plugins depend on it and must not reimplement business rules.

## Requirements

- PHP ≥ 8.2

## Installation

```bash
composer require tigamadou/emecef-core-php
```

Or from the monorepo (development):

```bash
cd php/packages/core/emecef-core
composer install
```

## Quick Start

The Core does **not** read configuration. You inject base URL, JWT token, an HTTP client implementation, and a logger:

```php
use Emecef\Core\Client;
use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Contract\LoggerInterface;

$client = new Client(
    baseUrl: 'https://sygmef.impots.bj/emcf/api/invoice',
    token: 'your-jwt-from-dgi',
    httpClient: $yourHttpClient,  // must implement HttpClientInterface
    logger: $yourLogger          // must implement LoggerInterface
);
```

For full configuration and usage, see the **docs** in this package:

- [Overview](docs/overview.md)
- [Configuration](docs/configuration.md)
- [Examples](docs/examples.md)
- [DTOs and API reference](docs/dto-and-api.md) — all DTOs, enums, fields, and request/response mapping

## Data objects (DTOs) and enums

The package models all request and response structures from the [e-MECeF API v1.0](https://github.com/tigamadou/emecef/blob/main/docs/e-mecef-api-v1.0.md) specification. For **field-by-field descriptions** and **request/return shapes**, see [DTOs and API reference](docs/dto-and-api.md):

- **Enums:** `Emecef\Core\Enum\InvoiceType`, `AibGroupType`, `TaxGroupType`, `PaymentType`
- **Request DTOs** (build payloads, call `toArray()` or `toJson()`): `Emecef\Core\Dto\Request\InvoiceRequestDataDto`, `ItemDto`, `ClientDto`, `OperatorDto`, `PaymentDto`
- **Response DTOs** (create from API array via `fromArray()`): `Emecef\Core\Dto\Response\StatusResponseDto`, `PendingRequestDto`, `InvoiceResponseDataDto`, `SecurityElementsDto`

You can use the client with raw JSON (array in/out) or with typed DTOs:

```php
// Array-based (unchanged)
$status = $client->getStatus();           // array
$result = $client->submitInvoice($json); // array
$client->confirm($uid);                   // array
$client->cancel($uid);                   // array

// DTO-based (typed request/response)
$statusDto = $client->getStatusResponse();                    // StatusResponseDto
$requestDto = new InvoiceRequestDataDto(...);                // build request
$responseDto = $client->submitInvoiceRequest($requestDto);   // InvoiceResponseDataDto
$securityDto = $client->confirmResponse($uid);               // SecurityElementsDto
$cancelDto = $client->cancelResponse($uid);                  // SecurityElementsDto
```

## Package Rules

- **Framework-agnostic:** No Laravel, Symfony, or CMS dependencies.
- **No env access:** Configuration is passed in by the caller.
- **Transport abstraction:** HTTP via `HttpClientInterface`; logging via `LoggerInterface`.

## Development

```bash
composer install
composer phpcs         # code style check (mandatory)
composer phpcbf       # code style fix (mandatory)
composer phpmd        # mess detector (mandatory)
composer test
composer phpstan
```

- **Code style:** PHP_CodeSniffer (phpcs + phpcbf) mandatory; PSR-12.
- **Mess detection:** PHPMD mandatory.
- **Tests:** PHPUnit, unit tests only; coverage target ≥ 95%.
- **Static analysis:** PHPStan (max level).

## License

Proprietary. See repository root for governance.
