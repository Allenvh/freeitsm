# warbotLike()

Escape the LIKE wildcards themselves, or a search for "50%" matches everything.

```php
function warbotLike(string $s): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 648.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | the search text; trimmed and wrapped in % for a LIKE ... ESCAPE '\\' match |

**Returns:** `string`

## Called from

- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolKnownErrors()](/code/functions/warbotToolKnownErrors.md), [warbotToolSupplierContact()](/code/functions/warbotToolSupplierContact.md)

