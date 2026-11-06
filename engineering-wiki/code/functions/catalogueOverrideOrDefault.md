# catalogueOverrideOrDefault()

An overridden lookup id, or the install's default for that table.

The default is resolved in PHP rather than left as a subselect inside the
INSERT so that both branches produce a plain integer — a column that is
sometimes a value and sometimes a subquery is the kind of thing that works
until the day somebody adds a second caller.

```php
function catalogueOverrideOrDefault(PDO $conn, array $overrides, string $key, string $table): ?int
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 353.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$overrides` | `array` |  | the caller's ticket overrides, keyed by column name |
| `$key` | `string` |  | the override key to look for, e.g. 'status_id' |
| `$table` | `string` |  | the lookup table to take the default from (trusted - interpolated into the SQL), e.g. 'ticket_statuses'; needs is_active, is_default and display_order columns |

**Returns:** `?int`

## Called from

- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueCreateTicketFromSubmission()](/code/functions/catalogueCreateTicketFromSubmission.md)

