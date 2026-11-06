# supplierContactDetail()

Everything about one supplier contact this analyst may see, or null.

```php
function supplierContactDetail(PDO $conn, int $analystId, int $contactId): ?array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 745.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$contactId` | `int` |  | the contacts id (a supplier contact) |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 749 | `supplierContactDetail` | read [contacts](/code/tables/contacts.md), read [suppliers](/code/tables/suppliers.md) |  |

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [peopleCanSeeSuppliers()](/code/functions/peopleCanSeeSuppliers.md), [peopleContactName()](/code/functions/peopleContactName.md), [peopleContractorTasks()](/code/functions/peopleContractorTasks.md), [peopleSupplierDomains()](/code/functions/peopleSupplierDomains.md), [peopleSupplierName()](/code/functions/peopleSupplierName.md)

## Called from

- [people/contact.php](/code/files/people/contact.php.md)

