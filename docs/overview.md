# emecef-core – Overview

## What This Package Does

**emecef-core** is the **framework-agnostic Core SDK** for the Benin Republic **e-MECeF** (Electronic Tax Invoice) system. It implements:

- e-MECeF protocol (invoicing API: status, submit, confirm/cancel)
- Authentication (JWT Bearer)
- Payload construction and validation
- Error normalization (API error codes → exceptions)
- Transport abstraction (no concrete HTTP client)

It is the **single source of truth** for e-MECeF business logic. Framework adapters (Laravel, Symfony, etc.) and CMS plugins **depend on** this package and must **not** reimplement its logic.

## What This Package Does NOT Do

- **Does not** read environment variables (adapters do that)
- **Does not** depend on any framework or CMS
- **Does not** perform I/O except through injected transport and logger interfaces
- **Does not** provide UI, persistence, or database logic

## Design Principles

- **Core-first:** All e-MECeF domain logic lives here.
- **Interface-driven:** HTTP and logging are abstracted via `HttpClientInterface` and `LoggerInterface`.
- **Testable:** Unit tests only; no framework bootstrapping; mocked transport and logger.

## Dependencies

- **PHP** ≥ 8.2
- **Runtime:** None (standard library only)
- **Dev:** PHPUnit, PHPStan

## Related Documentation

- [Configuration](configuration.md)
- [Examples](examples.md)
- [DTOs and API reference](dto-and-api.md) — all DTOs, enums, fields, and request/response mapping
- Repository root: `ARCHITECTURE.md`, `PACKAGES.md`, `docs/e-mecef-api-v1.0.md`
