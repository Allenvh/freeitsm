# domainCustomerSupplierSearch()

Suppliers and supplier contacts matching `$q`, for the same picker (#162). A contact carries its supplier, so choosing one sets both.

```php
function domainCustomerSupplierSearch(PDO $conn, string $q, int $limit = 15): array
```

Defined in [includes/domains/customer.php](/code/files/includes/domains/customer.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$q` | `string` |  | what the user typed; matched anywhere in supplier names, contact names and e-mails |
| `$limit` | `int` | `15` | most suppliers, and separately most contacts, to return |

**Returns:** array<int,array{kind:string,id:int,name:string,email:?string,supplier_id:?int,supplier:?string}>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 75 | `domainCustomerSupplierSearch` | read [suppliers](/code/tables/suppliers.md) |  |
| 82 | `domainCustomerSupplierSearch` | read [contacts](/code/tables/contacts.md), read [suppliers](/code/tables/suppliers.md) |  |

## Called from

- [api/domains/people.php](/code/files/api/domains/people.php.md)

