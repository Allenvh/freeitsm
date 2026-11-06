# apiContractsCreate()

POST /contracts - creates a contract through ContractsService in the company named (or the key's default) and returns it.

```php
function apiContractsCreate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 371.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiContractCompany()](/code/functions/apiContractCompany.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiLoadContract()](/code/functions/apiLoadContract.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeContract()](/code/functions/apiSerializeContract.md)

*Nothing calls this function by name.*

