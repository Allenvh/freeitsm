# pplRaciDuties()

A person's RACI duties on one project, one line per letter: "Accountable for Phones working on day one, Every desk patched and 2 more". The full list is the line's tooltip.

```php
function pplRaciDuties(array $duties): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 265.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$duties` | `array` |  | RACI letter ('R', 'A', 'C', 'I') => list of item titles |

**Returns:** `string`

## Calls

[pplE()](/code/functions/pplE.md), [t()](/code/functions/t.md)

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSectionProjects()](/code/functions/pplSectionProjects.md)

