# ewFile()

The file behind a safe page path, if it exists and really is inside the wiki.

```php
function ewFile(string $page): ?string
```

Defined in [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$page` | `string` |  | a page path already passed through `ewSafePath()`, e.g. "handbook/start" |

**Returns:** `?string`

## Calls

[ewRoot()](/code/functions/ewRoot.md)

## Called from

- [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md) - in [ewReadPage()](/code/functions/ewReadPage.md)

