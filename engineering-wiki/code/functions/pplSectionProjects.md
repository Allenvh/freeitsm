# pplSectionProjects()

Projects (3.2.0): a person's memberships with their role and RACI duties, or a company's projects.

```php
function pplSectionProjects(array $s, bool $person): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 283.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | the projects section from includes/people.php: rows, total |
| `$person` | `bool` |  | true on a person's page, adding the role and RACI duties column |

**Returns:** `string`

## Calls

[peopleBareDate()](/code/functions/peopleBareDate.md), [pplCard()](/code/functions/pplCard.md), [pplE()](/code/functions/pplE.md), [pplLink()](/code/functions/pplLink.md), [pplPill()](/code/functions/pplPill.md), [pplRaciDuties()](/code/functions/pplRaciDuties.md), [t()](/code/functions/t.md)

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSections()](/code/functions/pplSections.md)

