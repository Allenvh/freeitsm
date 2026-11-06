# ewReadPage()

A page, rendered.

```php
function ewReadPage(string $page): ?array
```

Defined in [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md), line 100.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$page` | `string` |  | a safe page path (from `ewSafePath()`), e.g. "code/tables/leases" |

**Returns:** ?array{page:string,title:string,html:string,toc:array,modified:int,section:string,generated:bool}

## Calls

[ewFile()](/code/functions/ewFile.md), [ewNiceName()](/code/functions/ewNiceName.md), [ewResolveLink()](/code/functions/ewResolveLink.md)

*Nothing calls this function by name.*

