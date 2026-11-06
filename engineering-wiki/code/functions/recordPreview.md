# recordPreview()

A preview, or NULL when the type is unknown, the record does not exist, or this analyst may not see it. One answer for all three, deliberately.

```php
function recordPreview(PDO $conn, int $analystId, string $type, int $id): ?array
```

Defined in [includes/record_preview.php](/code/files/includes/record_preview.php.md), line 57.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$type` | `string` |  | the record type, a RECORD_PREVIEW_MODULES key such as 'ticket' or 'knowledge_article' |
| `$id` | `int` |  | the record's id; 0 or less gives null |

**Returns:** `?array`

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [entityLink()](/code/functions/entityLink.md)

## Called from

- [api/system/record_preview.php](/code/files/api/system/record_preview.php.md)

