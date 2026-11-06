# systemAreaIcon()

Return the inline SVG markup for an area icon key. Unknown keys render a neutral square so a typo never breaks the page. Sizing/colour come from the .system-card svg CSS rule, so no width/height/stroke colour is hard-set here.

```php
function systemAreaIcon($key)
```

Defined in [system/includes/areas.php](/code/files/system/includes/areas.php.md), line 308.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` |  |  | the icon key from an area's entry in `getSystemAreas()`; unknown keys draw a square |

## Called from

- [system/help/index.php](/code/files/system/help/index.php.md)
- [system/index.php](/code/files/system/index.php.md)

