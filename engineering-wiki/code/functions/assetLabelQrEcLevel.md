# assetLabelQrEcLevel()

Determine QR code error-correction level based on logo presence.

Invariants:
- No logo: standard Level M (15% redundancy) for maximum module clarity
- With logo: high Level H (30% redundancy) to guarantee reliable scanning with 22% center overlay

```php
function assetLabelQrEcLevel(bool $hasLogo): string
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 198.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$hasLogo` | `bool` |  | true when the label prints a logo in the middle of the QR code |

**Returns:** `string`

## Called from

- [asset-management/labels.php](/code/files/asset-management/labels.php.md)

