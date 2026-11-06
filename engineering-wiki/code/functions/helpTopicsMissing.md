# helpTopicsMissing()

System areas that still have no help page — the gap this registry exists to make visible. Shown as a quiet footnote on the landing page rather than hidden, so a new area doesn't quietly ship undocumented.

```php
function helpTopicsMissing($multiTenant = false)
```

Defined in [system/help/_registry.php](/code/files/system/help/_registry.php.md), line 609.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$multiTenant` |  | `false` | true when a second company exists, so 'multitenant' areas count too |

**Returns:** array<int,string> Area titles (translated).

## Calls

[getHelpTopics()](/code/functions/getHelpTopics.md), [getSystemAreas()](/code/functions/getSystemAreas.md), [t()](/code/functions/t.md)

## Called from

- [system/help/index.php](/code/files/system/help/index.php.md)

