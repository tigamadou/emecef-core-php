# DTOs and API reference

This document describes all **data transfer objects (DTOs)**, **enums**, and **API operations** in emecef-core-php, aligned with the [e-MECeF API v1.0](https://github.com/tigamadou/emecef/blob/main/docs/e-mecef-api-v1.0.md) specification.

---

## Table of contents

1. [Enums](#enums)
2. [Request DTOs](#request-dtos)
3. [Response DTOs](#response-dtos)
4. [API operations: requests and returns](#api-operations-requests-and-returns)

---

## Enums

All enums live in `Emecef\Core\Enum\`. Values match the e-MECeF API exactly.

### InvoiceType

Invoice type (2 characters). Used in **InvoiceRequestDataDto**.

| Value | Meaning |
|-------|--------|
| `FV` | Facture de vente (sales invoice) |
| `EV` | Vente à l'exportation (export sale) |
| `FA` | Facture d'avoir (credit note) |
| `EA` | Avoir à l'exportation (export credit note) |

For `FA` and `EA`, the **reference** field (24‑char MECeF/DGI code of the original invoice) is required.

### AibGroupType

AIB (Assurance Incendie Bâtiment) group: 1% or 5%. Optional on the invoice request.

| Value | Meaning |
|-------|--------|
| `A` | 1% |
| `B` | 5% |

### TaxGroupType

Tax group for an invoice **line item** (ItemDto). One letter per item.

| Value | Meaning |
|-------|--------|
| `A` | Tax group A |
| `B` | Tax group B |
| `C` | Tax group C |
| `D` | Tax group D |
| `E` | Tax group E |
| `F` | Tax group F |

Rates are defined by the API (tax groups endpoint); the SDK only sends the group letter.

### PaymentType

Payment method. Used in **PaymentDto**.

| Value | Meaning |
|-------|--------|
| `ESPECES` | Cash |
| `VIREMENT` | Transfer |
| `CARTEBANCAIRE` | Card |
| `MOBILEMONEY` | Mobile money |
| `CHEQUES` | Cheque |
| `CREDIT` | Credit |
| `AUTRE` | Other |

---

## Request DTOs

Used to **build** payloads sent to the API. Namespace: `Emecef\Core\Dto\Request\`. All provide **`toArray()`**; **InvoiceRequestDataDto** also provides **`toJson()`**.

### ItemDto

One line on the invoice (product or service).

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `name` | `string` | Yes | Line description (e.g. product name). |
| `price` | `int` | Yes | Unit price (integer, e.g. in local currency units). |
| `quantity` | `float` | Yes | Quantity. |
| `taxGroup` | `TaxGroupType` | Yes | Tax group for this line (`A`–`F`). |
| `code` | `?string` | No | Article/sku code. |
| `taxSpecific` | `?int` | No | Specific tax amount for the line (total for the quantity). |
| `originalPrice` | `?int` | No | Original price when modified (e.g. discount). |
| `priceModification` | `?string` | No | Description of the modification (e.g. "Remise 50 %"). |

**Example**

```php
use Emecef\Core\Dto\Request\ItemDto;
use Emecef\Core\Enum\TaxGroupType;

$item = new ItemDto(
    name: 'Jus d\'orange',
    price: 1800,
    quantity: 2.0,
    taxGroup: TaxGroupType::B
);
```

### ClientDto

Buyer/client. All fields are optional.

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `ifu` | `?string` | No | Client IFU (13 chars). |
| `name` | `?string` | No | Client name. |
| `contact` | `?string` | No | Phone or email. |
| `address` | `?string` | No | Address. |

**Example**

```php
use Emecef\Core\Dto\Request\ClientDto;

$client = new ClientDto(
    ifu: '99999000000002',
    name: 'Nom du client',
    contact: '45661122',
    address: 'Rue d\'ananas 23'
);
```

### OperatorDto

POS operator (cashier). Serialized with key `"id"` for the optional identifier (API field name).

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `name` | `string` | Yes | Operator name. |
| `operatorId` | `?string` | No | Operator identifier (sent as `"id"` in JSON). |

**Example**

```php
use Emecef\Core\Dto\Request\OperatorDto;

$operator = new OperatorDto('Jacques');
// or with id:
$operator = new OperatorDto('Jacques', 'op-001');
```

### PaymentDto

One payment entry (method + amount).

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `name` | `PaymentType` | Yes | Payment method (e.g. `ESPECES`, `MOBILEMONEY`). |
| `amount` | `int` | Yes | Amount (integer). |

**Example**

```php
use Emecef\Core\Dto\Request\PaymentDto;
use Emecef\Core\Enum\PaymentType;

$payment = new PaymentDto(PaymentType::ESPECES, 4950);
```

### InvoiceRequestDataDto

Full invoice request (submit payload). Sent as JSON body for **submit invoice**.

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `ifu` | `string` | Yes | Seller IFU (13 chars). |
| `type` | `InvoiceType` | Yes | Invoice type: `FV`, `EV`, `FA`, `EA`. |
| `items` | `list<ItemDto>` | Yes | At least one line item. |
| `operator` | `OperatorDto` | Yes | POS operator. |
| `client` | `?ClientDto` | No | Buyer; optional. |
| `payment` | `?list<PaymentDto>` | No | Payment(s). If null or empty, API defaults to ESPECES. |
| `aib` | `?AibGroupType` | No | AIB type `A` (1%) or `B` (5%). |
| `reference` | `?string` | For FA/EA | For credit notes (`FA`/`EA`), original invoice MECeF/DGI code (24 chars). |

**Example**

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
        new ItemDto('Jus d\'orange', 1800, 2.0, TaxGroupType::B),
        new ItemDto('Lait 1/1 EX', 450, 3.0, TaxGroupType::A),
    ],
    operator: new OperatorDto('Jacques'),
    client: new ClientDto(/* ... */),
    payment: [new PaymentDto(PaymentType::ESPECES, 4950)]
);
$json = $request->toJson();
```

---

## Response DTOs

Represent **responses** from the API. Namespace: `Emecef\Core\Dto\Response\`. All are created via **`fromArray(array $data): self`** from the decoded JSON.

### PendingRequestDto

One pending invoice (not yet confirmed or cancelled). Used inside **StatusResponseDto**.

| Property | Type | Description |
|----------|------|-------------|
| `date` | `string` | Datetime when the request was received. |
| `uid` | `string` | Transaction UID (use for confirm or cancel). |

### StatusResponseDto

Response of **get status** (GET invoicing API root).

| Property | Type | Description |
|----------|------|-------------|
| `status` | `bool` | `true` if the API is operational. |
| `version` | `string` | API version (e.g. `"1.0"`). |
| `ifu` | `string` | Taxpayer IFU. |
| `nim` | `string` | e-MCF NIM. |
| `tokenValid` | `string` | Datetime until the token is valid. |
| `serverDateTime` | `string` | Server datetime. |
| `pendingRequestsCount` | `int` | Number of pending invoice requests (max 10). |
| `pendingRequestsList` | `list<PendingRequestDto>` | Pending requests (each has `date` and `uid`). |

### InvoiceResponseDataDto

Response of **submit invoice** (POST). Contains computed totals and the **UID** used to confirm or cancel.

| Property | Type | Description |
|----------|------|-------------|
| `uid` | `?string` | Transaction UID (36 chars). **Use this for confirm/cancel.** Null on error. |
| `ta`, `tb`, `tc`, `td` | `int` | Tax group rates A–D (in %). |
| `taa`, `tab`, `tac`, `tad`, `tae`, `taf` | `int` | Total amounts per tax group A–F. |
| `hab`, `had` | `int` | Amounts excluding tax (HT) for groups B, D. |
| `vab`, `vad` | `int` | VAT amounts (TVA) for groups B, D. |
| `aib` | `int` | AIB amount. |
| `ts` | `int` | Specific tax amount. |
| `total` | `int` | **Total invoice amount.** Verify against your own calculation before confirming. |
| `errorCode` | `?string` | Present on error. |
| `errorDesc` | `?string` | Error description when the API returns an error. |

### SecurityElementsDto

Response of **confirm** and **cancel** (PUT). After **cancel**, `qrCode` and `codeMECeFDGI` are empty.

| Property | Type | Description |
|----------|------|-------------|
| `dateTime` | `string` | Invoice/cancellation datetime (e.g. `"23/11/2020 13:17:08"`). |
| `qrCode` | `string` | QR code content (up to 66 chars). Empty when cancelled. |
| `codeMECeFDGI` | `string` | MECeF/DGI code (29 chars). **Must appear on the printed invoice.** Empty when cancelled. |
| `counters` | `string` | Counters (e.g. `"64/64 FV"`). |
| `nim` | `string` | e-MCF NIM (10 chars). |
| `errorCode` | `?string` | Optional error code. |
| `errorDesc` | `?string` | Optional error description. |

---

## API operations: requests and returns

The **Client** exposes each operation in two forms: **array-based** (raw JSON in/out) and **DTO-based** (typed request/response). Both use the same HTTP calls; DTO methods delegate to the array methods and then parse/build DTOs.

### 1. Get status

**Purpose:** Check that the API is up, the token is valid, and how many invoices are pending.

| Aspect | Details |
|--------|---------|
| **HTTP** | `GET {baseUrl}/` |
| **Request body** | None |
| **Client method (array)** | `getStatus(): array` |
| **Client method (DTO)** | `getStatusResponse(): StatusResponseDto` |
| **Return (array)** | Decoded JSON: `status`, `version`, `ifu`, `nim`, `tokenValid`, `serverDateTime`, `pendingRequestsCount`, `pendingRequestsList` (array of `{ date, uid }`). |
| **Return (DTO)** | `StatusResponseDto` with the same data as above. |
| **Throws** | `ApiException` on API error (e.g. 401); `TransportExceptionInterface` on network failure. |

**Example**

```php
$data = $client->getStatus();
// or
$dto = $client->getStatusResponse();
$count = $dto->pendingRequestsCount;
foreach ($dto->pendingRequestsList as $pending) {
    $uid = $pending->uid;
}
```

---

### 2. Submit invoice

**Purpose:** Send invoice data; API returns computed totals and a **UID** for the next step (confirm or cancel).

| Aspect | Details |
|--------|---------|
| **HTTP** | `POST {baseUrl}/` |
| **Request body** | JSON **InvoiceRequestDataDto** (ifu, type, items, operator, client?, payment?, aib?, reference?). |
| **Client method (array)** | `submitInvoice(string $jsonBody): array` |
| **Client method (DTO)** | `submitInvoiceRequest(InvoiceRequestDataDto $request): InvoiceResponseDataDto` |
| **Return (array)** | Decoded JSON: `uid`, `ta`, `tb`, `tc`, `td`, `taa`–`taf`, `hab`, `had`, `vab`, `vad`, `aib`, `ts`, `total`, and optionally `errorCode`, `errorDesc`. |
| **Return (DTO)** | `InvoiceResponseDataDto` with the same fields. |
| **Throws** | `ApiException` on validation/API error; `TransportExceptionInterface` on network failure. |

**Important:** Compare the returned `total` (and line totals if needed) with your own calculation before calling **confirm**. Use the returned **`uid`** for `confirm($uid)` or `cancel($uid)`.

**Example**

```php
// Array: you build JSON yourself
$json = '{"ifu":"9999900000001","type":"FV","items":[...],"operator":{"name":"Op"}}';
$data = $client->submitInvoice($json);
$uid = $data['uid'];

// DTO: build request, get typed response
$request = new InvoiceRequestDataDto(/* ... */);
$response = $client->submitInvoiceRequest($request);
$uid = $response->uid;
$total = $response->total;
```

---

### 3. Confirm invoice

**Purpose:** Finalise a pending invoice; API returns security elements (QR code, MECeF/DGI code) to print on the invoice.

| Aspect | Details |
|--------|---------|
| **HTTP** | `PUT {baseUrl}/{uid}/confirmer` |
| **Request body** | None |
| **Client method (array)** | `confirm(string $uid): array` |
| **Client method (DTO)** | `confirmResponse(string $uid): SecurityElementsDto` |
| **Return (array)** | Decoded JSON: `dateTime`, `qrCode`, `codeMECeFDGI`, `counters`, `nim`, and optionally `errorCode`, `errorDesc`. |
| **Return (DTO)** | `SecurityElementsDto` with the same fields. |
| **Throws** | `ApiException` if UID invalid or already finalised/cancelled; `TransportExceptionInterface` on network failure. |

**Example**

```php
$data = $client->confirm($uid);
// or
$security = $client->confirmResponse($uid);
// Use on the printed invoice:
$qrContent = $security->qrCode;
$mecefCode = $security->codeMECeFDGI;
```

---

### 4. Cancel invoice

**Purpose:** Cancel a pending invoice. It is recorded as cancelled; no security elements are issued.

| Aspect | Details |
|--------|---------|
| **HTTP** | `PUT {baseUrl}/{uid}/annuler` |
| **Request body** | None |
| **Client method (array)** | `cancel(string $uid): array` |
| **Client method (DTO)** | `cancelResponse(string $uid): SecurityElementsDto` |
| **Return (array)** | Same shape as confirm: `dateTime`, `qrCode`, `codeMECeFDGI` (empty), `counters`, `nim`. |
| **Return (DTO)** | `SecurityElementsDto`; `qrCode` and `codeMECeFDGI` are empty strings. |
| **Throws** | `ApiException` if UID invalid or already finalised/cancelled; `TransportExceptionInterface` on network failure. |

**Example**

```php
$data = $client->cancel($uid);
// or
$result = $client->cancelResponse($uid);
// $result->qrCode === '' && $result->codeMECeFDGI === ''
```

---

### Summary table

| Operation      | Array method              | DTO method                       | Returns (array shape)     | Returns (DTO)            |
|----------------|---------------------------|----------------------------------|----------------------------|--------------------------|
| Get status     | `getStatus()`             | `getStatusResponse()`            | StatusResponseDto shape    | `StatusResponseDto`      |
| Submit invoice | `submitInvoice($json)`   | `submitInvoiceRequest($request)` | InvoiceResponseDataDto    | `InvoiceResponseDataDto`|
| Confirm        | `confirm($uid)`          | `confirmResponse($uid)`          | SecurityElementsDto        | `SecurityElementsDto`     |
| Cancel         | `cancel($uid)`           | `cancelResponse($uid)`           | SecurityElementsDto        | `SecurityElementsDto`     |

All operations can throw **`Emecef\Core\Exception\ApiException`** (API errors, with `getErrorCode()` / `getErrorDesc()`) and **`Emecef\Core\Contract\TransportExceptionInterface`** (network/transport errors).
