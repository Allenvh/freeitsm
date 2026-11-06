# cardDavSetComponent()

Set one component of a structured value, leaving its siblings alone.

`ORG:Acme Ltd;Finance` and `ADR:;;12 High St;Norwich;;NR1 1AA;UK` both hold
several fields in one line, separated by unescaped semicolons, and FreeITSM
imports exactly one component from each — the department from ORG, the town
from ADR. Writing the whole value back would erase the company name and the
entire street address respectively.

⚠️ Pads with empty components when the stored value is shorter than the index
being written. A card carrying only `ADR:;;12 High St` has no town slot at
all, and appending one requires the two empty separators in between or the
town lands in `street`.

```php
function cardDavSetComponent(string $value, int $index, int $arity, string $escapedNew): string
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 433.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` | `string` |  | the property's current value after the colon, still escaped; '' for a new line |
| `$index` | `int` |  | which component to set, from 0 (ORG department is 1, ADR town is 3) |
| `$arity` | `int` |  | how many components the property is defined to have, so a padded value is not left shorter than parsers expect |
| `$escapedNew` | `string` |  | the new component, already escaped with `cardDavEscapeText()`; '' blanks it |

**Returns:** `string`

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md)

