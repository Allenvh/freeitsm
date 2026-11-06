# pplSectionAssets()

The Assets card: asset, type, model, status, then the date assigned (person page) or who holds it (company page).

```php
function pplSectionAssets(array $s, bool $person, ?string $title = null): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 142.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | the assets section from includes/people.php: rows, total |
| `$person` | `bool` |  | true on a person's page (shows the date assigned), false elsewhere (shows who holds it) |
| `$title` | `?string` | `null` | a heading to use instead of the standard one; null for the standard |

**Returns:** `string`

## Calls

[fmt_date()](/code/functions/fmt_date.md), [pplCard()](/code/functions/pplCard.md), [pplE()](/code/functions/pplE.md), [pplLink()](/code/functions/pplLink.md), [t()](/code/functions/t.md)

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSections()](/code/functions/pplSections.md)
- [people/supplier.php](/code/files/people/supplier.php.md)

