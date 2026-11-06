# ewListDir()

One folder of the page tree, for the sidebar (it opens folder by folder, because the code map runs to thousands of pages).

```php
function ewListDir(string $dir): array
```

Defined in [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md), line 154.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dir` | `string` |  | the folder's page path, e.g. "code/tables"; '' lists the top-level sections |

**Returns:** array{dirs:array<int,array{path:string,name:string}>, pages:array<int,array{page:string,title:string}>}

## Calls

[ewRoot()](/code/functions/ewRoot.md), [ewSafePath()](/code/functions/ewSafePath.md), [ewTitleOf()](/code/functions/ewTitleOf.md)

*Nothing calls this function by name.*

