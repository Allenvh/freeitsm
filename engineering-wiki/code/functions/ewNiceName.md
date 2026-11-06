# ewNiceName()

"code/tables/lease_assets" -> "lease_assets"; used when a page has no # title.

```php
function ewNiceName(string $page): string
```

Defined in [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md), line 123.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$page` | `string` |  | a page path; an "index" page is named after its folder |

**Returns:** `string`

## Called from

- [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md) - in [ewReadPage()](/code/functions/ewReadPage.md), [ewTitleOf()](/code/functions/ewTitleOf.md)

