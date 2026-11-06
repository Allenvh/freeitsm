# filesViewKind()

Which viewer draws a file, by extension. Anything else gets "no preview".

```php
function filesViewKind(string $name): string
```

Defined in [api/files/view.php](/code/files/api/files/view.php.md), line 47.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the file name; only its extension is looked at, case-insensitively |

**Returns:** `string`

## Called from

- [api/files/view.php](/code/files/api/files/view.php.md)

