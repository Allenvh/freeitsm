# ctLoadSitting()

Loads one candidate sitting with its test's title, or answers 404 "Candidate not found" and stops.

```php
function ctLoadSitting(PDO $conn, int $id): array
```

Defined in [api/lms/tests.php](/code/files/api/lms/tests.php.md), line 77.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the lms_ct_sittings id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 79 | `ctLoadSitting` | read [lms_ct_sittings](/code/tables/lms_ct_sittings.md), read [lms_ct_tests](/code/tables/lms_ct_tests.md) |  |

## Calls

[ctFail()](/code/functions/ctFail.md)

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)

