# lmsCtFinalise()

Close a sitting and store its score. Only the first close counts.

```php
function lmsCtFinalise(PDO $conn, array $s, string $reason): bool
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 403.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$s` | `array` |  | the lms_ct_sittings row to close (snapshot_json, responses_json, id) |
| `$reason` | `string` |  | stored as finish_reason - 'submitted', or 'time_up' (dated at the deadline rather than now) |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 409 | `lmsCtFinalise` | **update** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) (`submitted_datetime`, `finish_reason`, `score_percent`, `skills_json`) |  |

## Calls

[lmsCtDeadline()](/code/functions/lmsCtDeadline.md), [lmsCtScore()](/code/functions/lmsCtScore.md)

## Called from

- [api/lms/test_public.php](/code/files/api/lms/test_public.php.md)
- [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) - in [lmsCtFinaliseOverdue()](/code/functions/lmsCtFinaliseOverdue.md)
- [lms/test.php](/code/files/lms/test.php.md)

