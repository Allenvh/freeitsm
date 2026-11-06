# projectsSchemaReady()

Has Database Verification created the Projects schema yet?

🔑 Tasks reads project names onto every card. On an install that has pulled
3.2.0 but not yet run Verification, `tasks.project_id` does not exist and a
query naming it would take the WHOLE Tasks board down. Callers outside the
Projects module (api/tasks/*) ask this first and simply leave projects out.
Cached per request.

```php
function projectsSchemaReady(PDO $conn): bool
```

Defined in [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), line 28.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Called from

- [api/tasks/get.php](/code/files/api/tasks/get.php.md)
- [api/tasks/list.php](/code/files/api/tasks/list.php.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleProjects()](/code/functions/peopleProjects.md)

