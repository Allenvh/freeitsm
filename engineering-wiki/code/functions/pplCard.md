# pplCard()

One section card: a heading with its count, then a table or "Nothing yet".

```php
function pplCard(
    string $key,
    string $title,
    int $count,
    array $head,
    array $rows,
    bool $more = false,
    string $extra = ''
): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 67.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the section key, used for the card's id (sec-<key>) the stats strip links to |
| `$title` | `string` |  | the card heading |
| `$count` | `int` |  | the total shown beside the heading |
| `$head` | `array` |  | the column headings, plain text |
| `$rows` | `array` |  | one array of cells per row, each cell ALREADY-ESCAPED HTML; rows past PPL_VISIBLE_ROWS start hidden |
| `$more` | `bool` | `false` | true to add the "only the first N are shown" note |
| `$extra` | `string` | `''` | extra HTML after the count, e.g. an open/total line |

**Returns:** `string`

## Calls

[pplE()](/code/functions/pplE.md), [t()](/code/functions/t.md)

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSectionTickets()](/code/functions/pplSectionTickets.md), [pplSectionAssets()](/code/functions/pplSectionAssets.md), [pplSectionContracts()](/code/functions/pplSectionContracts.md), [pplSectionDomains()](/code/functions/pplSectionDomains.md), [pplSectionCoursesPerson()](/code/functions/pplSectionCoursesPerson.md), [pplSectionCoursesCompany()](/code/functions/pplSectionCoursesCompany.md), [pplSectionForms()](/code/functions/pplSectionForms.md), [pplSectionProjects()](/code/functions/pplSectionProjects.md), [pplSectionContractorTasks()](/code/functions/pplSectionContractorTasks.md)

