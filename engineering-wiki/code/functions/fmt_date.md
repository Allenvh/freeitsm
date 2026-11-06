# fmt_date()

'25 Aug 2026'

```php
function fmt_date(?string $utc): string
```

Defined in [includes/timezone.php](/code/files/includes/timezone.php.md), line 454.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$utc` | `?string` |  | a UTC datetime as stored; null or '' gives '', an unparseable value comes back as it is |

**Returns:** `string`

## Calls

[_fmt_dt()](/code/functions/_fmt_dt.md)

## Called from

- [people/contact.php](/code/files/people/contact.php.md)
- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSectionTickets()](/code/functions/pplSectionTickets.md), [pplSectionAssets()](/code/functions/pplSectionAssets.md), [pplSectionCoursesPerson()](/code/functions/pplSectionCoursesPerson.md)
- [people/person.php](/code/files/people/person.php.md)

