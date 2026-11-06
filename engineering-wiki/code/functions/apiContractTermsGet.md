# apiContractTermsGet()

Contract terms — GET + bulk upsert (mirrors save_contract_terms.php)

```php
function apiContractTermsGet(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 403.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 406 | `apiContractTermsGet` | read [contract_term_tabs](/code/tables/contract_term_tabs.md), read [contract_term_values](/code/tables/contract_term_values.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiLoadContract()](/code/functions/apiLoadContract.md), [apiRespond()](/code/functions/apiRespond.md)

## Called from

- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractTermsSave()](/code/functions/apiContractTermsSave.md)

