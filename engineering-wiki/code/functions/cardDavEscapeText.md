# cardDavEscapeText()

The inverse of `cardDavUnescapeText()` — a plain string as a vCard TEXT value.

🔴 The backslash MUST be escaped first, and it is the only one where order
matters: doing it after the comma would find the backslash that the comma rule
just inserted and double it.

⚠️ CR is dropped rather than escaped. A lone `\r` has no representation in a
vCard text value, and emitting a bare carriage return inside one would be read
by the next parser as a line fold.

```php
function cardDavEscapeText(string $v): string
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 482.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` | `string` |  | the plain text to write into a vCard |

**Returns:** `string`

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md)

