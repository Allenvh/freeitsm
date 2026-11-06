# projectUnapprovedChanges()

The project's linked changes that are still open and NOT approved - what a stage gate warns about (3.2.0, "going live safely").

Approved is the recorded fact (changes.approval_datetime), the same test
Watchtower's "awaiting approval" uses, so an extra approval stage in the
change workflow needs no list here. Unlike Watchtower it KEEPS drafts (a
change still in its starting status): at a gate, a change nobody has even
submitted is the bigger worry, and the list says which ones are drafts.
Closed changes (done, failed, cancelled, rejected) are not waiting on anyone.

Changes have no stage, so this is the project's whole list; the gate shows
each one's planned start so the board can see what falls in the next stage.

```php
function projectUnapprovedChanges(PDO $conn, int $projectId): array
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 499.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 503 | `projectUnapprovedChanges` | read [project_changes](/code/tables/project_changes.md), read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistOverview()](/code/functions/projectAssistOverview.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::stageClosed`

