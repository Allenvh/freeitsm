# debugToolIcon()

Inline SVG markup for a debug-tool icon key. Unknown keys render a neutral wrench so a typo never breaks the page. Sizing/colour come from CSS.

```php
function debugToolIcon($key)
```

Defined in [system/debug-tools/includes/tools.php](/code/files/system/debug-tools/includes/tools.php.md), line 511.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` |  |  | the icon key from a tool's entry, e.g. 'ticket', 'sso'; unknown keys draw a wrench |

## Called from

- [system/debug-tools/index.php](/code/files/system/debug-tools/index.php.md)

