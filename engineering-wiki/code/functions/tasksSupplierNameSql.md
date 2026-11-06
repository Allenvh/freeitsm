# tasksSupplierNameSql()

The name a supplier goes by.

```php
function tasksSupplierNameSql(string $alias = 'sp'): string
```

Defined in [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), line 46.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$alias` | `string` | `'sp'` | the alias the calling query gives the suppliers table |

**Returns:** `string`

## Called from

- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatRead()](/code/functions/projectChatRead.md), [projectChatTurn()](/code/functions/projectChatTurn.md)
- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)
- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md)
- [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) - in [tasksContractors()](/code/functions/tasksContractors.md), [tasksContractorChoices()](/code/functions/tasksContractorChoices.md), [tasksContractorEmail()](/code/functions/tasksContractorEmail.md)

