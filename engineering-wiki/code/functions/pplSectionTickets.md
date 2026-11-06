# pplSectionTickets()

The Tickets card: number, subject, status, priority, then the analyst (person page) or requester (company page), and the open/total count.

```php
function pplSectionTickets(array $s, bool $person): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | the tickets section from includes/people.php: rows, total, open |
| `$person` | `bool` |  | true on a person's page (shows the analyst), false on a company page (shows the requester) |

**Returns:** `string`

## Calls

[fmt_date()](/code/functions/fmt_date.md), [pplCard()](/code/functions/pplCard.md), [pplE()](/code/functions/pplE.md), [pplLink()](/code/functions/pplLink.md), [pplPill()](/code/functions/pplPill.md), [t()](/code/functions/t.md)

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSections()](/code/functions/pplSections.md)

