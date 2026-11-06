# pplSectionContracts()

The Contracts card: contract, the other party (unless `$withParty` is false), status and end date, with inactive contracts marked.

```php
function pplSectionContracts(array $s, bool $withParty = true, ?string $title = null): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 163.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | the contracts section from includes/people.php: rows, total |
| `$withParty` | `bool` | `true` | false on a supplier's own page, to leave out the "With" column |
| `$title` | `?string` | `null` | a heading to use instead of the standard one; null for the standard |

**Returns:** `string`

## Calls

[peopleBareDate()](/code/functions/peopleBareDate.md), [pplCard()](/code/functions/pplCard.md), [pplE()](/code/functions/pplE.md), [pplLink()](/code/functions/pplLink.md), [pplPill()](/code/functions/pplPill.md), [t()](/code/functions/t.md)

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSections()](/code/functions/pplSections.md)
- [people/supplier.php](/code/files/people/supplier.php.md)

