# integrationsKnownCommentIds()

Comment ids already mapped for one link, in either direction.

```php
function integrationsKnownCommentIds(PDO $conn, int $linkId): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1263.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$linkId` | `int` |  | the integration_links id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1267 | `integrationsKnownCommentIds` | read [integration_comment_map](/code/tables/integration_comment_map.md) |  |

## Calls

[integrationsCommentSchemaReady()](/code/functions/integrationsCommentSchemaReady.md)

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsApplyCommentEvent()](/code/functions/integrationsApplyCommentEvent.md)

