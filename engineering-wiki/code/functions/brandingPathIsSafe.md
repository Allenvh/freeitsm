# brandingPathIsSafe()

A stored upload pointer is only ever a file inside the branding directory.

```php
function brandingPathIsSafe(string $rel): bool
```

Defined in [includes/branding.php](/code/files/includes/branding.php.md), line 99.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rel` | `string` |  | the stored upload path, relative to the app root |

**Returns:** `bool`

## Called from

- [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md)
- [api/system/save_branding.php](/code/files/api/system/save_branding.php.md)
- [includes/asset_labels.php](/code/files/includes/asset_labels.php.md) - in [assetLabelSettings()](/code/functions/assetLabelSettings.md)
- [includes/branding.php](/code/files/includes/branding.php.md) - in [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [brandingLoginValidate()](/code/functions/brandingLoginValidate.md)

