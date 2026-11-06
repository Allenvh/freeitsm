# pplStats()

The coloured strip of counts across the top of a page.

```php
function pplStats(array $sections, bool $person, array $labels = []): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 35.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sections` | `array` |  | the page's sections from includes/people.php, keyed by section ('tickets', 'assets'...) |
| `$person` | `bool` |  | true on a person's page (person wording for assets, domains, projects), false elsewhere |
| `$labels` | `array` | `[]` | section key => label, replacing the standard label for that figure |

**Returns:** `string`

## Calls

[pplE()](/code/functions/pplE.md), [t()](/code/functions/t.md)

## Called from

- [people/company.php](/code/files/people/company.php.md)
- [people/contact.php](/code/files/people/contact.php.md)
- [people/person.php](/code/files/people/person.php.md)
- [people/supplier.php](/code/files/people/supplier.php.md)

