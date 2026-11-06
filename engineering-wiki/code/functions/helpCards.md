# helpCards()

The help cards for the landing page: every System area that HAS a help page, in the same order as the System landing page itself.

Joining on the area registry (rather than restating icons/titles here) means
a card's title, description and icon always match the area it documents, and
an area with no help page yet is skipped rather than linked to a 404.

```php
function helpCards($multiTenant = false)
```

Defined in [system/help/_registry.php](/code/files/system/help/_registry.php.md), line 556.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$multiTenant` |  | `false` | Whether a 2nd company exists (gates 'multitenant' areas). |

**Returns:** array<int,array<string,mixed>> Cards with a resolved title/desc/keywords/sections.

## Calls

[getHelpTopics()](/code/functions/getHelpTopics.md), [getSystemAreas()](/code/functions/getSystemAreas.md), [t()](/code/functions/t.md)

## Called from

- [system/help/index.php](/code/files/system/help/index.php.md)

