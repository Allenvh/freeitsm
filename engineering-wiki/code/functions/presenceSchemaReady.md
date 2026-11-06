# presenceSchemaReady()

Does this database have the presence table yet?

Unlike snooze's column gate (#933), a missing table here is genuinely
harmless — presence is an additive strip in the reading pane, so the honest
degradation is "nobody is ever shown". Cached per request.

```php
function presenceSchemaReady(PDO $conn): bool
```

Defined in [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md), line 58.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Called from

- [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md) - in [presenceHeartbeat()](/code/functions/presenceHeartbeat.md), [presenceOthers()](/code/functions/presenceOthers.md), [presenceLeave()](/code/functions/presenceLeave.md), [presencePurge()](/code/functions/presencePurge.md)

