# lmsCtFindSitting()

The sitting a raw token opens, or null. Malformed tokens never reach the database.

```php
function lmsCtFindSitting(PDO $conn, string $raw): ?array
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 328.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$raw` | `string` |  | the raw token from the link - untrusted; anything but 64 hex characters gives null |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 331 | `lmsCtFindSitting` | read [lms_ct_sittings](/code/tables/lms_ct_sittings.md) |  |

## Called from

- [api/lms/test_public.php](/code/files/api/lms/test_public.php.md)
- [lms/test.php](/code/files/lms/test.php.md)

