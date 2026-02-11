# emecef-core – Examples

For full field descriptions and request/response shapes, see [DTOs and API reference](dto-and-api.md).

## Minimal Instantiation (with stubs)

The Core does not ship with an HTTP client. In tests or when building an adapter, you provide implementations:

```php
use Emecef\Core\Client;
use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Contract\LoggerInterface;

// Stub or real implementation of HttpClientInterface
$httpClient = new class implements HttpClientInterface {
    public function request(string $method, string $url, array $headers = [], ?string $body = null): HttpResponseInterface {
        // ... perform request, return HttpResponseInterface implementation
    }
};

// Stub or real implementation of LoggerInterface
$logger = new class implements LoggerInterface {
    public function debug(string $message, array $context = []): void {}
    public function info(string $message, array $context = []): void {}
    public function warning(string $message, array $context = []): void {}
    public function error(string $message, array $context = []): void {}
};

$client = new Client(
    baseUrl: 'https://developper.impots.bj/sygmef-emcf/api/invoice',
    token: 'your-jwt-from-dgi',
    httpClient: $httpClient,
    logger: $logger
);

if ($client->isConfigured()) {
    // Ready for getStatus(), submitInvoice(), confirm(), cancel(), and their DTO variants
}
```

## Using from a Laravel Adapter (future)

The Laravel adapter will:

1. Read config (e.g. `config('emecef.base_url')`, `config('emecef.token')`).
2. Resolve an HTTP client (e.g. Guzzle) and a logger (e.g. Monolog) from the container.
3. Wrap them in implementations of `HttpClientInterface` and `LoggerInterface`.
4. Instantiate `Emecef\Core\Client` and expose it via a facade or service.

## DTO-based flow (submit → confirm)

```php
use Emecef\Core\Dto\Request\InvoiceRequestDataDto;
use Emecef\Core\Dto\Request\ItemDto;
use Emecef\Core\Dto\Request\OperatorDto;
use Emecef\Core\Enum\InvoiceType;
use Emecef\Core\Enum\TaxGroupType;

$request = new InvoiceRequestDataDto(
    ifu: '9999900000001',
    type: InvoiceType::FV,
    items: [
        new ItemDto('Article A', 1000, 2.0, TaxGroupType::A),
        new ItemDto('Article B', 500, 1.0, TaxGroupType::B),
    ],
    operator: new OperatorDto('Opérateur 1')
);
$response = $client->submitInvoiceRequest($request);
if ($response->uid !== null) {
    $security = $client->confirmResponse($response->uid);
    // Use $security->qrCode, $security->codeMECeFDGI on the printed invoice
}
```

## Error Handling

```php
use Emecef\Core\Exception\ApiException;
use Emecef\Core\Contract\TransportExceptionInterface;

try {
    $client->submitInvoice($json);
} catch (ApiException $e) {
    // e-MECeF API error (e.g. errorCode 20 = facture déjà finalisée)
    $code = $e->getErrorCode();
    $desc = $e->getErrorDesc();
} catch (TransportExceptionInterface $e) {
    // Network or transport failure
}
```
