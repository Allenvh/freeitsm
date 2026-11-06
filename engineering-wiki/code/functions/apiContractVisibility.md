# apiContractVisibility()

The key's view of customer contracts (#153): " AND (...)" + its args.

```php
function apiContractVisibility(PDO $conn, array $apiKey): array
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 80.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[contractVisibilitySqlForScope()](/code/functions/contractVisibilitySqlForScope.md)

## Called from

- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiLoadContract()](/code/functions/apiLoadContract.md), [apiContractsList()](/code/functions/apiContractsList.md)

