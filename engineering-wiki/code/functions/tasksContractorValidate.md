# tasksContractorValidate()

Check a supplier / contact pair: the supplier exists; the contact (if any) works for it. A contact alone brings their supplier. Returns [supplierId, contactId].

```php
function tasksContractorValidate(PDO $conn, $supplierId, $contactId): array
```

Defined in [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), line 98.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$supplierId` |  |  | a supplier id as sent; null or '' for none |
| `$contactId` |  |  | a contact id as sent; null or '' for none - a contact alone brings their supplier |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 103 | `tasksContractorValidate` | read [contacts](/code/tables/contacts.md) |  |
| 112 | `tasksContractorValidate` | read [suppliers](/code/tables/suppliers.md) |  |

## Called from

- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatTurn()](/code/functions/projectChatTurn.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::addContractorMember`
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::createTask`, `TasksService::updateTask`

