<?php

declare(strict_types=1);

namespace Emecef\Core;

use Emecef\Core\Contract\HttpClientInterface;
use Emecef\Core\Contract\HttpResponseInterface;
use Emecef\Core\Contract\LoggerInterface;
use Emecef\Core\Contract\TransportExceptionInterface;
use Emecef\Core\Dto\Request\InvoiceRequestDataDto;
use Emecef\Core\Dto\Response\InvoiceResponseDataDto;
use Emecef\Core\Dto\Response\SecurityElementsDto;
use Emecef\Core\Dto\Response\StatusResponseDto;
use Emecef\Core\Exception\ApiException;

/**
 * Main e-MECeF API client. Framework-agnostic; depends only on transport and logger interfaces.
 *
 * Responsibilities:
 * - Authentication (JWT Bearer)
 * - Status request (GET invoice API)
 * - Invoice request (POST), confirm/cancel (PUT)
 * - Error normalization (ApiException with errorCode/errorDesc)
 */
final class Client
{
    private const CONTENT_TYPE_JSON = 'application/json';

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $token,
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Base URL of the e-MECeF invoicing API (without trailing slash).
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Whether the client is configured (base URL and token set).
     * Does not perform network calls or read environment.
     */
    public function isConfigured(): bool
    {
        return $this->baseUrl !== '' && $this->token !== '';
    }

    /**
     * HTTP client (used by status, submitInvoice, confirm, cancel, etc.).
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * Logger (used for request/response diagnostics).
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Request API status (GET {baseUrl}/). Returns decoded JSON (StatusResponseDto shape).
     *
     * @return array<string, mixed>
     *
     * @throws ApiException On API error (4xx/5xx or errorCode in body)
     * @throws TransportExceptionInterface On network/transport failure
     */
    public function getStatus(): array
    {
        $response = $this->send('GET', $this->baseUrl . '/', null);
        return $this->decodeJson($response->getBody());
    }

    /**
     * Request API status and return typed DTO (StatusResponseDto).
     *
     * @throws ApiException On API error (4xx/5xx or errorCode in body)
     * @throws TransportExceptionInterface On network/transport failure
     * @SuppressWarnings(PHPMD.StaticAccess) DTO named constructor
     */
    public function getStatusResponse(): StatusResponseDto
    {
        return StatusResponseDto::fromArray($this->getStatus());
    }

    /**
     * Submit invoice (POST {baseUrl}/). Request body must be JSON.
     * Returns decoded response (InvoiceResponseDataDto shape).
     *
     * @return array<string, mixed>
     *
     * @throws ApiException On API error (4xx/5xx or errorCode in body)
     * @throws TransportExceptionInterface On network/transport failure
     */
    public function submitInvoice(string $jsonBody): array
    {
        $response = $this->send('POST', $this->baseUrl . '/', $jsonBody);
        return $this->decodeJson($response->getBody());
    }

    /**
     * Submit invoice using request DTO. Returns typed InvoiceResponseDataDto.
     *
     * @throws ApiException On API error (4xx/5xx or errorCode in body)
     * @throws TransportExceptionInterface On network/transport failure
     * @SuppressWarnings(PHPMD.StaticAccess) DTO named constructor
     */
    public function submitInvoiceRequest(InvoiceRequestDataDto $request): InvoiceResponseDataDto
    {
        $data = $this->submitInvoice($request->toJson());
        return InvoiceResponseDataDto::fromArray($data);
    }

    /**
     * Confirm a pending invoice (PUT {baseUrl}/{uid}/confirmer). Returns decoded response (SecurityElementsDto shape).
     *
     * @return array<string, mixed>
     *
     * @throws ApiException On API error (4xx/5xx or errorCode in body)
     * @throws TransportExceptionInterface On network/transport failure
     */
    public function confirm(string $uid): array
    {
        $response = $this->send('PUT', $this->baseUrl . '/' . $uid . '/confirmer', null);
        return $this->decodeJson($response->getBody());
    }

    /**
     * Confirm a pending invoice and return typed SecurityElementsDto.
     *
     * @throws ApiException On API error (4xx/5xx or errorCode in body)
     * @throws TransportExceptionInterface On network/transport failure
     * @SuppressWarnings(PHPMD.StaticAccess) DTO named constructor
     */
    public function confirmResponse(string $uid): SecurityElementsDto
    {
        return SecurityElementsDto::fromArray($this->confirm($uid));
    }

    /**
     * Cancel a pending invoice (PUT {baseUrl}/{uid}/annuler). Returns decoded response (SecurityElementsDto shape).
     *
     * @return array<string, mixed>
     *
     * @throws ApiException On API error (4xx/5xx or errorCode in body)
     * @throws TransportExceptionInterface On network/transport failure
     */
    public function cancel(string $uid): array
    {
        $response = $this->send('PUT', $this->baseUrl . '/' . $uid . '/annuler', null);
        return $this->decodeJson($response->getBody());
    }

    /**
     * Cancel a pending invoice and return typed SecurityElementsDto.
     *
     * @throws ApiException On API error (4xx/5xx or errorCode in body)
     * @throws TransportExceptionInterface On network/transport failure
     * @SuppressWarnings(PHPMD.StaticAccess) DTO named constructor
     */
    public function cancelResponse(string $uid): SecurityElementsDto
    {
        return SecurityElementsDto::fromArray($this->cancel($uid));
    }

    /**
     * @throws ApiException On non-2xx response
     * @throws TransportExceptionInterface On transport failure
     */
    private function send(string $method, string $url, ?string $body): HttpResponseInterface
    {
        $response = $this->httpClient->request($method, $url, $this->defaultHeaders(), $body);

        $statusCode = $response->getStatusCode();
        if ($statusCode >= 200 && $statusCode < 300) {
            return $response;
        }

        $responseBody = $response->getBody();
        $data = $this->decodeJson($responseBody);
        $errorCode = isset($data['errorCode']) && is_string($data['errorCode']) ? $data['errorCode'] : null;
        $errorDesc = isset($data['errorDesc']) && is_string($data['errorDesc']) ? $data['errorDesc'] : null;
        $message = $errorDesc ?? ('HTTP ' . $statusCode);

        throw new ApiException($message, $errorCode, $errorDesc);
    }

    /**
     * @return array<string, string>
     */
    private function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => self::CONTENT_TYPE_JSON,
            'Accept' => self::CONTENT_TYPE_JSON,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeJson(string $body): array
    {
        $decoded = json_decode($body, true);
        if (!\is_array($decoded)) {
            return [];
        }

        return $decoded;
    }
}
