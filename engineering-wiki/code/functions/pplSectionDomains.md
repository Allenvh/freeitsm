# pplSectionDomains()

The Domains card: domain, the roles it plays when the rows carry them, status, expiry and grade.

```php
function pplSectionDomains(array $s, bool $person, ?string $title = null): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 186.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | the domains section from includes/people.php: rows (with roles on supplier and contact pages), total |
| `$person` | `bool` |  | true on a person's page, for the heading only |
| `$title` | `?string` | `null` | a heading to use instead of the standard one; null for the standard |

**Returns:** `string`

## Calls

[peopleBareDate()](/code/functions/peopleBareDate.md), [pplCard()](/code/functions/pplCard.md), [pplE()](/code/functions/pplE.md), [pplLink()](/code/functions/pplLink.md), [pplPill()](/code/functions/pplPill.md), [t()](/code/functions/t.md)

## Called from

- [people/contact.php](/code/files/people/contact.php.md)
- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSections()](/code/functions/pplSections.md)
- [people/supplier.php](/code/files/people/supplier.php.md)

