<?php

declare(strict_types=1);

namespace Emecef\Core\Contract;

/**
 * Abstraction for HTTP transport. Core SDK does not depend on any concrete HTTP client.
 * Adapters inject the implementation (Guzzle, Symfony HttpClient, etc.).
 */
interface HttpClientInterface
{
    /**
     * Send an HTTP request and return the response.
     *
     * @param string $method  HTTP method (GET, POST, PUT, etc.)
     * @param string $url     Full URL
     * @param array<string, string> $headers Headers (e.g. Authorization, Content-Type)
     * @param string|null $body Request body (for POST/PUT)
     *
     * @return HttpResponseInterface Response with status, headers, and body
     *
     * @throws TransportExceptionInterface On network or transport failure
     */
    public function request(
        string $method,
        string $url,
        array $headers = [],
        ?string $body = null
    ): HttpResponseInterface;
}
