# lmsWriteCmi()

Upsert one CMI element. The unique key on (progress_id, element) does the work.

```php
function lmsWriteCmi(PDO $conn, int $progressId, string $element, string $value): void
```

Defined in [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$progressId` | `int` |  | the lms_progress row the value belongs to |
| `$element` | `string` |  | the SCORM element name, e.g. cmi.interactions.0.id |
| `$value` | `string` |  | the value to store |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 69 | `lmsWriteCmi` | **insert** [lms_cmi_data](/code/tables/lms_cmi_data.md) (`progress_id`, `element`, `value`, `created_datetime`, `updated_datetime`) |  |

## Called from

- [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md)

