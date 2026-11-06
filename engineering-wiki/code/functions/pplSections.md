# pplSections()

Every section present, in a fixed order.

```php
function pplSections(array $sections, bool $person): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 331.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sections` | `array` |  | the page's sections from includes/people.php, keyed by section |
| `$person` | `bool` |  | true on a person's page, false on a company page |

**Returns:** `string`

## Calls

[pplSectionAssets()](/code/functions/pplSectionAssets.md), [pplSectionContracts()](/code/functions/pplSectionContracts.md), [pplSectionCoursesCompany()](/code/functions/pplSectionCoursesCompany.md), [pplSectionCoursesPerson()](/code/functions/pplSectionCoursesPerson.md), [pplSectionDomains()](/code/functions/pplSectionDomains.md), [pplSectionForms()](/code/functions/pplSectionForms.md), [pplSectionProjects()](/code/functions/pplSectionProjects.md), [pplSectionTickets()](/code/functions/pplSectionTickets.md)

## Called from

- [people/company.php](/code/files/people/company.php.md)
- [people/person.php](/code/files/people/person.php.md)

