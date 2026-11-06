# apiLoadContract()

Loads one contract row the key may see (apiContractVisibility), or answers 404 and stops.

```php
function apiLoadContract(PDO $conn, int $contractId, array $apiKey): array
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 144.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$contractId` | `int` |  | the contracts id |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[apiContractSelect()](/code/functions/apiContractSelect.md), [apiContractVisibility()](/code/functions/apiContractVisibility.md), [apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractsGet()](/code/functions/apiContractsGet.md), [apiContractsCreate()](/code/functions/apiContractsCreate.md), [apiContractsUpdate()](/code/functions/apiContractsUpdate.md), [apiContractTermsGet()](/code/functions/apiContractTermsGet.md)

