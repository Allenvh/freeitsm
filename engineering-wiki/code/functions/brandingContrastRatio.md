# brandingContrastRatio()

Is this pair of colours legible together? WCAG relative-luminance contrast.

Not a gate — an administrator may have a reason — but the designer says so
before it is saved, because the failure mode is people who cannot read the
login screen and cannot tell you why.

```php
function brandingContrastRatio(string $hexA, string $hexB): float
```

Defined in [includes/branding.php](/code/files/includes/branding.php.md), line 415.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$hexA` | `string` |  | the first colour as #rrggbb |
| `$hexB` | `string` |  | the second colour as #rrggbb |

**Returns:** `float`

*Nothing calls this function by name.*

