# ewResolveLink()

Turn a link written on page `$from` into a page path (+ #anchor), or null when it does not lead to a wiki page. "../tables/leases.md#columns" on code/files/x -> "code/tables/leases#columns". A leading "/" means "from the wiki root" ([[wiki links]] arrive like that).

```php
function ewResolveLink(string $from, string $href): ?string
```

Defined in [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md), line 70.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$from` | `string` |  | the page path the link is written on, e.g. "code/files/x" |
| `$href` | `string` |  | the link target as written in the Markdown, optionally with a #fragment |

**Returns:** `?string`

## Calls

[ewSafePath()](/code/functions/ewSafePath.md)

## Called from

- [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md) - in [ewReadPage()](/code/functions/ewReadPage.md)

