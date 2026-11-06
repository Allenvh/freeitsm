# pplSectionCoursesPerson()

A person's Courses card: each course with its status pill, progress (or completion date) and deadline, overdue ones marked.

```php
function pplSectionCoursesPerson(array $s): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 207.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | the person's courses section from includes/people.php: rows, total |

**Returns:** `string`

## Calls

[fmt_date()](/code/functions/fmt_date.md), [peopleBareDate()](/code/functions/peopleBareDate.md), [pplCard()](/code/functions/pplCard.md), [pplE()](/code/functions/pplE.md), [pplPill()](/code/functions/pplPill.md), [t()](/code/functions/t.md)

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSections()](/code/functions/pplSections.md)

