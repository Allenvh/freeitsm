# pplSectionCoursesCompany()

A company's Courses card: each course with how many of its people have finished it and how many are part-way.

```php
function pplSectionCoursesCompany(array $s): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 230.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | the company's courses section from includes/people.php: rows (finished, in_progress), total |

**Returns:** `string`

## Calls

[pplCard()](/code/functions/pplCard.md), [pplE()](/code/functions/pplE.md), [t()](/code/functions/t.md)

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSections()](/code/functions/pplSections.md)

