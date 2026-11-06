# apiLoadChange()

Loads one change row (apiChangeSelect), or answers 404 and stops - including for a change in a company the key cannot reach.

```php
function apiLoadChange(PDO $conn, array $apiKey, int $changeId): array
```

Defined in [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), line 138.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the changes id |

**Returns:** `array`

## Calls

[apiChangeSelect()](/code/functions/apiChangeSelect.md), [apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md)

## Called from

- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesGet()](/code/functions/apiChangesGet.md), [apiChangesCreate()](/code/functions/apiChangesCreate.md), [apiChangesUpdate()](/code/functions/apiChangesUpdate.md), [apiChangeCommentsList()](/code/functions/apiChangeCommentsList.md), [apiChangeAuditList()](/code/functions/apiChangeAuditList.md), [apiChangeCabGet()](/code/functions/apiChangeCabGet.md)

