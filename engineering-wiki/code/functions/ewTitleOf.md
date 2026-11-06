# ewTitleOf()

The title of a page without rendering it: the first "# " line, else its file name.

```php
function ewTitleOf(string $file, string $page): string
```

Defined in [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md), line 135.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$file` | `string` |  | the absolute path of the page's .md file (only its first 8 lines are read) |
| `$page` | `string` |  | the page path, used for the fallback name |

**Returns:** `string`

## Calls

[ewNiceName()](/code/functions/ewNiceName.md)

## Called from

- [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md) - in [ewListDir()](/code/functions/ewListDir.md), [ewSearch()](/code/functions/ewSearch.md)

