# getHelpTopic()

One topic by slug, or null. Slug comes from the page (`$helpSlug`) or the URL.

```php
function getHelpTopic($slug)
```

Defined in [system/help/_registry.php](/code/files/system/help/_registry.php.md), line 540.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$slug` |  |  | the help page slug, e.g. 'sso-groups'; unknown slugs give null |

**Returns:** array<string,mixed>|null

## Calls

[getHelpTopics()](/code/functions/getHelpTopics.md)

## Called from

- [system/help/_top.php](/code/files/system/help/_top.php.md)

