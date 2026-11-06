# lmsCtInsertQuestion()

Insert a validated question into the bank.

```php
function lmsCtInsertQuestion(
    PDO $conn,
    array $q,
    string $source,
    ?string $role,
    ?int $analystId
): int
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 198.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$q` | `array` |  | the clean question from `lmsCtValidateQuestion()` |
| `$source` | `string` |  | where it came from - 'ai' or 'manual' |
| `$role` | `?string` |  | the role description the AI wrote it for, cut to 255 characters; null or '' for none |
| `$analystId` | `?int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 200 | `lmsCtInsertQuestion` | **insert** [lms_ct_questions](/code/tables/lms_ct_questions.md) (`skill`, `difficulty`, `format`, `question_text`, `answers_json`, `explanation`, `status`, `source`, `role_context`, `created_by_analyst_id`, `created_datetime`) |  |

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)

