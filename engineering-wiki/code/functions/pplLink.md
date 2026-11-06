# pplLink()

A link to an app-relative URL, or just the escaped text when the reader has nowhere to go.

```php
function pplLink(?string $url, string $text): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `?string` |  | a path relative to the app root; null for plain text with no link |
| `$text` | `string` |  | the link text, escaped here |

**Returns:** `string`

## Calls

[pplE()](/code/functions/pplE.md), [pplUrl()](/code/functions/pplUrl.md)

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSectionTickets()](/code/functions/pplSectionTickets.md), [pplSectionAssets()](/code/functions/pplSectionAssets.md), [pplSectionContracts()](/code/functions/pplSectionContracts.md), [pplSectionDomains()](/code/functions/pplSectionDomains.md), [pplSectionForms()](/code/functions/pplSectionForms.md), [pplSectionProjects()](/code/functions/pplSectionProjects.md), [pplSectionContractorTasks()](/code/functions/pplSectionContractorTasks.md)

