# lmsRemindersRunOpportunistic()

The opportunistic run: called when a manager opens the LMS console.

⚠️ Throttled to once an hour and silent about everything. This runs inside a
request somebody is waiting on, so it must never be the reason a page is slow
and must never be the reason a page errors.

```php
function lmsRemindersRunOpportunistic(PDO $conn): void
```

Defined in [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md), line 447.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[lmsReminderSettings()](/code/functions/lmsReminderSettings.md), [lmsRemindersRun()](/code/functions/lmsRemindersRun.md)

## Called from

- [api/lms/assignments.php](/code/files/api/lms/assignments.php.md)

