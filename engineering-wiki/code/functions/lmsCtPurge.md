# lmsCtPurge()

Retention: candidates' details are personal data. A sitting that is over - submitted, cancelled, or a link never used and out of date - is deleted once it is older than the retention setting. At most once a day; 0 keeps everything.

```php
function lmsCtPurge(PDO $conn, array $settings, bool $force = false): int
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 436.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$settings` | `array` |  | the competency test settings from `lmsCtSettings()` (retention days, last purge date) |
| `$force` | `bool` | `false` | true to purge even if it already ran today |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 442 | `lmsCtPurge` | **delete** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) |  |

## Calls

[lmsCtSaveSetting()](/code/functions/lmsCtSaveSetting.md)

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)

