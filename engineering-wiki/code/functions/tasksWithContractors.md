# tasksWithContractors()

Add the contractor fields to task rows (each with an 'id').

```php
function tasksWithContractors(PDO $conn, array $rows): array
```

Defined in [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), line 79.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rows` | `array` |  | task rows, each with an 'id'; supplier_id, supplier_name, contact_id and contact_name are added |

**Returns:** `array`

## Calls

[tasksContractors()](/code/functions/tasksContractors.md)

## Called from

- [api/tasks/get.php](/code/files/api/tasks/get.php.md)
- [api/tasks/list.php](/code/files/api/tasks/list.php.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectDetail()](/code/functions/projectDetail.md)

