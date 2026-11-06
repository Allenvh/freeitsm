# tasksContractorChoices()

Suppliers to choose from, each with its active contacts.

```php
function tasksContractorChoices(PDO $conn): array
```

Defined in [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), line 120.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 122 | `tasksContractorChoices` | read [suppliers](/code/tables/suppliers.md) |  |
| 123 | `tasksContractorChoices` | read [contacts](/code/tables/contacts.md) |  |

## Calls

[tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md)

## Called from

- [api/tasks/contractors.php](/code/files/api/tasks/contractors.php.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatRead()](/code/functions/projectChatRead.md)

