# catalogueFormActionLists()

A form's three action lists, loaded lazily.

The require is deliberately inside the function. `includes/services/forms.php`
requires the workflow engine at file level, and the engine lazily requires
THIS file — pulling forms.php in at the top would make that ring tighter than
it needs to be for one lookup.

```php
function catalogueFormActionLists(PDO $conn, int $formId): array
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 464.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$formId` | `int` |  | forms.id whose action lists to load |

**Returns:** array{submitted: ?array, approved: ?array, rejected: ?array}

## Called from

- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueApprovalDecide()](/code/functions/catalogueApprovalDecide.md)

