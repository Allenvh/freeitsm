# assetLabelUrl()

The URL a label's QR encodes.

Absolute, because the code is scanned by a phone that has no idea what the
app's base path is. Built on `publicBaseUrl()` - the install's ONE answer to
"how does the outside world reach this install?" - so a configured public
address wins over the current request: a label is printed once and lives on a
laptop for years, and deriving it from whichever hostname the printing
analyst happened to be using would bake that in permanently.

This used to reuse the messaging-only setting, flagged at the time as wanting
a generic key; `publicBaseUrl()` reads `public_base_url` and still falls back to
`messaging_public_base_url`, so labels printed before PR #164 encode the same
address. It also copes with a configured address that already carries the
app's folder (publicUrlWithAppPath()), which would otherwise print
…/freeitsm-app/freeitsm-app/a/<token> onto physical labels.

```php
function assetLabelUrl(string $token, ?PDO $conn = null): string
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 166.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$token` | `string` |  | the asset's qr_token, appended to the public base as a/<token> |
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md)

## Called from

- [asset-management/labels.php](/code/files/asset-management/labels.php.md)

