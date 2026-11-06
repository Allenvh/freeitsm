# brandingLoginValidate()

One value, validated against its declared type. Anything that does not fit becomes the default — never an error, because this runs on the login page.

```php
function brandingLoginValidate(string $field, $raw, array $spec)
```

Defined in [includes/branding.php](/code/files/includes/branding.php.md), line 252.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$field` | `string` |  | the field's name; not used by the check itself |
| `$raw` |  |  | the stored or posted value (untrusted); null or '' gives the default |
| `$spec` | `array` |  | the field's entry from `brandingLoginFields()` - type, default, and values/min/max as the type needs |

## Calls

[brandingPathIsSafe()](/code/functions/brandingPathIsSafe.md)

## Called from

- [api/system/save_branding.php](/code/files/api/system/save_branding.php.md)
- [includes/branding.php](/code/files/includes/branding.php.md) - in [brandingLoginDesign()](/code/functions/brandingLoginDesign.md)

