# apiChangesGet()

GET /changes/{id} — detail incl. bodies, PIR, attachments, linked problems

```php
function apiChangesGet(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), line 283.

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
| 302 | `apiChangesGet` | read [change_attachments](/code/tables/change_attachments.md) |  |
| 320 | `apiChangesGet` | read [change_relations](/code/tables/change_relations.md), read [problems](/code/tables/problems.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiLoadChange()](/code/functions/apiLoadChange.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeChange()](/code/functions/apiSerializeChange.md)

*Nothing calls this function by name.*

