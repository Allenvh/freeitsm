# pplUrl()

An app-relative path made absolute with BASE_URL; '#' when there is none.

```php
function pplUrl(?string $rel): string
```

Defined in [people/includes/render.php](/code/files/people/includes/render.php.md), line 26.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rel` | `?string` |  | a path relative to the app root, e.g. 'tickets/?id=5'; null gives '#' |

**Returns:** `string`

## Called from

- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplLink()](/code/functions/pplLink.md)

