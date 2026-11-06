# projectAiReady()

Is AI set up for Projects?

```php
function projectAiReady(PDO $conn): bool
```

Defined in [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), line 363.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[aiSettingsLoad()](/code/functions/aiSettingsLoad.md)

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)
- [api/projects/reports.php](/code/files/api/projects/reports.php.md)
- [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) - in `ProjectReportsService::draftScheduled`

