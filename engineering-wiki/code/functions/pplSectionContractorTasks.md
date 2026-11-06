# pplSectionContractorTasks()

Contractors (3.3.0): the tasks given to a supplier, or to one person there.

```php
function pplSectionContractorTasks(array $s, bool $byContact): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 309.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | the contractor tasks section from includes/people.php: rows, total, open |
| `$byContact` | `bool` |  | true on a contact's page; false (supplier page) adds the contact column |

**Returns:** `string`

## Calls

[peopleBareDate()](/code/functions/peopleBareDate.md), [pplCard()](/code/functions/pplCard.md), [pplE()](/code/functions/pplE.md), [pplLink()](/code/functions/pplLink.md), [pplPill()](/code/functions/pplPill.md), [t()](/code/functions/t.md)

## Called from

- [people/contact.php](/code/files/people/contact.php.md)
- [people/supplier.php](/code/files/people/supplier.php.md)

