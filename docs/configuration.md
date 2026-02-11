# emecef-core – Configuration

## Config File and Parameters

The package provides a **config file** with base URLs only. **Token and environment are provided as parameters** by the caller (no env vars in the core).

- **Config file:** `config/emecef.php` — defines base URLs (invoicing and info API) per environment.
- **Token:** Pass when creating the Client (from your config, env, or secrets).

### Using Config

```php
use Emecef\Core\Config;
use Emecef\Core\Client;

// Provide token and environment as params
$token = 'your-jwt-from-dgi';  // from your config, env, or secrets
$environment = 'test';          // or 'production'

$config = Config::fromDefault();
$client = new Client(
    baseUrl: $config->getBaseUrl($environment),
    token: $token,
    httpClient: $httpClient,
    logger: $logger
);
```

- **Config::fromDefault()** — loads `config/emecef.php` (base URLs only).
- **Config::fromArray($data)** — build config from an array.
- **getBaseUrl('production'|'test')** — invoicing API base URL.
- **getBaseUrlInfo('production'|'test')** — information API base URL.

## Constructor Parameters

The main entry point is `Emecef\Core\Client`:

```php
new Client(
    baseUrl: string,      // e.g. from Config::getBaseUrl() or https://sygmef.impots.bj/emcf/api/invoice
    token: string,        // provided as parameter when creating the Client
    httpClient: HttpClientInterface,
    logger: LoggerInterface
);
```

- **baseUrl:** Base URL of the invoicing API (no trailing slash). Use test or production URL depending on environment.
- **token:** The secret JWT from the DGI e-MCF management interface (one per e-MCF). Provided as parameter when creating the Client.
- **httpClient:** Any implementation that can perform GET/POST/PUT with JSON and Bearer auth.
- **logger:** Any implementation for debug/info/warning/error (optional usage inside Core for diagnostics).

## API Base URLs (Reference)

Defined in `config/emecef.php`:

| Environment | Invoicing API | Info API |
|-------------|----------------|----------|
| Production  | `https://sygmef.impots.bj/emcf/api/invoice` | `https://sygmef.impots.bj/emcf/api/info` |
| Test        | `https://developper.impots.bj/sygmef-emcf/api/invoice` | `https://developper.impots.bj/sygmef-emcf/api/info` |

## Error Behavior

- **Transport failures** (network, timeout): thrown as `TransportExceptionInterface`.
- **API errors** (4xx/5xx or `errorCode` in JSON): thrown as `Emecef\Core\Exception\ApiException` (implements `ApiExceptionInterface`), with optional `errorCode` and `errorDesc` from the API.

Adapters are responsible for mapping these to framework-specific exceptions or user-facing messages if desired.
