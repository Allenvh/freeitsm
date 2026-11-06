# peopleContactName()

The name to show for a supplier contact: first name and surname, else email, else #id.

```php
function peopleContactName(array $r): string
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 648.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a contacts row - first_name, surname, email and id are read |

**Returns:** `string`

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [supplierDetail()](/code/functions/supplierDetail.md), [supplierContactDetail()](/code/functions/supplierContactDetail.md)

