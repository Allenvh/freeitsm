# apiNmValidateObjectExists()

Answers 422 and stops unless the CMDB object exists and, when a key is given, belongs to a company the key can reach - the same message either way, so another company's object is never revealed.

```php
function apiNmValidateObjectExists(PDO $conn, int $objectId, ?array $apiKey = null): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 321.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$objectId` | `int` |  | the cmdb_objects id the caller wants on a diagram (untrusted) |
| `$apiKey` | `?array` | `null` | the calling API key; null skips the company check and only tests existence |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 322 | `apiNmValidateObjectExists` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmValidateNodeInput()](/code/functions/apiNmValidateNodeInput.md)

