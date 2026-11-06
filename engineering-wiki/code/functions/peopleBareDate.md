# peopleBareDate()

A bare date (a contract end, a domain expiry, a deadline) - shown as stored, never shifted by a timezone.

```php
function peopleBareDate(?string $d): string
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 928.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` | `?string` |  | a 'Y-m-d' date (anything after the first 10 characters is ignored); null or '' gives '' |

**Returns:** `string`

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSectionContracts()](/code/functions/pplSectionContracts.md), [pplSectionDomains()](/code/functions/pplSectionDomains.md), [pplSectionCoursesPerson()](/code/functions/pplSectionCoursesPerson.md), [pplSectionProjects()](/code/functions/pplSectionProjects.md), [pplSectionContractorTasks()](/code/functions/pplSectionContractorTasks.md)

