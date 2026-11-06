# snoozeOnlySql()

The complement: only sleeping tickets. Drives the Snoozed folder and its count. Note the pre-upgrade answer is `1=0` and NOT the empty string — an empty fragment here would turn "only the sleeping ones" into "every ticket you own", which is the one failure mode worse than the folder simply being empty.

```php
function snoozeOnlySql(PDO $conn, string $alias = 't'): string
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 136.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'t'` | the alias the calling query gives the tickets table |

**Returns:** `string`

## Calls

[snoozeSchemaReady()](/code/functions/snoozeSchemaReady.md)

## Called from

- [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md)
- [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)

