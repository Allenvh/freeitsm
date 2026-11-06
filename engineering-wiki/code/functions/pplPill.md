# pplPill()

A status pill, optionally in a colour (passed as the --pill CSS variable); '' for empty text.

```php
function pplPill(?string $text, ?string $colour = null, string $class = ''): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 105.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `?string` |  | the pill text, escaped here; null or '' gives '' |
| `$colour` | `?string` | `null` | a CSS colour for the pill; null for the default |
| `$class` | `string` | `''` | extra CSS classes, e.g. 'muted', 'good', 'bad' |

**Returns:** `string`

## Calls

[pplE()](/code/functions/pplE.md)

## Called from

- [people/companies.php](/code/files/people/companies.php.md)
- [people/company.php](/code/files/people/company.php.md)
- [people/contact.php](/code/files/people/contact.php.md)
- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSectionTickets()](/code/functions/pplSectionTickets.md), [pplSectionContracts()](/code/functions/pplSectionContracts.md), [pplSectionDomains()](/code/functions/pplSectionDomains.md), [pplSectionCoursesPerson()](/code/functions/pplSectionCoursesPerson.md), [pplSectionProjects()](/code/functions/pplSectionProjects.md), [pplSectionContractorTasks()](/code/functions/pplSectionContractorTasks.md)
- [people/person.php](/code/files/people/person.php.md)
- [people/supplier.php](/code/files/people/supplier.php.md)
- [people/suppliers.php](/code/files/people/suppliers.php.md)

