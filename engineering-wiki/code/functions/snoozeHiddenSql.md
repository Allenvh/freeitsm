# snoozeHiddenSql()

SQL fragment hiding sleeping tickets. Append to any WHERE (or LEFT JOIN ON) that lists or counts the working queue. No placeholders, so it never disturbs a caller's parameter ordering — the same property that lets the trashed-ticket predicate be appended to `$ttSql` in get_ticket_counts.php.

```php
function snoozeHiddenSql(PDO $conn, string $alias = 't'): string
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 121.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'t'` | the alias the calling query gives the tickets table |

**Returns:** `string`

## Calls

[snoozeSchemaReady()](/code/functions/snoozeSchemaReady.md)

## Called from

- [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md)
- [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md)
- [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)

