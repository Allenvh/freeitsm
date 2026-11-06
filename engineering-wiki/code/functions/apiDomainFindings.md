# apiDomainFindings()

Findings with their English title and advice filled in — a machine consumer has no lang file.

```php
function apiDomainFindings(array $findings): array
```

Defined in [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), line 363.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$findings` | `array` |  | the 'findings' list from the domain's stored check_results JSON - each with key, area, level, weight and optional params for the text placeholders |

**Returns:** `array`

## Called from

- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsCheck()](/code/functions/apiDomainsCheck.md), [apiDomainsFindings()](/code/functions/apiDomainsFindings.md)

