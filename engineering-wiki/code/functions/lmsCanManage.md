# lmsCanManage()

May this analyst manage the LMS? (is_admin bypasses, via analystHasCapability.)

```php
function lmsCanManage(PDO $conn, int $analystId): bool
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[analystHasCapability()](/code/functions/analystHasCapability.md)

## Called from

- [includes/lms_access.php](/code/files/includes/lms_access.php.md) - in [lmsCanAccessCourse()](/code/functions/lmsCanAccessCourse.md)

