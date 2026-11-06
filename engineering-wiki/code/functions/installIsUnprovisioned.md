# installIsUnprovisioned()

True only when no analyst account exists, i.e. nobody can possibly log in and an unauthenticated bootstrap is the only way to build the schema.

Fails CLOSED. A missing `analysts` table is the one error that really does
mean "fresh install"; everything else — connection dropped, lock timeout,
permission denied — returns false and the caller demands an administrator.
Defaulting the other way would turn any transient database blip into an
unauthenticated migration endpoint, which is the same fail-open shape this
whole change exists to remove.

```php
function installIsUnprovisioned(PDO $conn): bool
```

Defined in [includes/setup_state.php](/code/files/includes/setup_state.php.md), line 38.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 | `installIsUnprovisioned` | read [analysts](/code/tables/analysts.md) |  |

## Calls

[dbErrorIsMissingTable()](/code/functions/dbErrorIsMissingTable.md)

## Called from

- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)
- [api/system/db_verify_preview.php](/code/files/api/system/db_verify_preview.php.md)
- [setup/index.php](/code/files/setup/index.php.md)

