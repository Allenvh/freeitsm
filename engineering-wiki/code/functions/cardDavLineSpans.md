# cardDavLineSpans()

Split a card into logical lines, remembering where each one physically began and ended.

This is the counterpart to `cardDavUnfold()`, which throws that away. Editing
needs it: to replace the logical line `TITLE:Head of Sales` we must know which
physical lines it occupied, continuations included, so the replacement lands
in the right place and takes the fold with it.

```php
function cardDavLineSpans(string $raw): array
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 287.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the card exactly as the server returned it |

**Returns:** array of ['logical' => string, 'start' => int, 'end' => int] where the offsets are byte positions into `$raw` and `end` is exclusive of the line ending.

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavInsertLine()](/code/functions/cardDavInsertLine.md), [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md)

