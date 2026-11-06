# publicBaseUrl()

The root every outbound link should be built on, without a trailing slash.

Resolution order, most trustworthy first:
  1. the configured address — the only one that is right from cron;
  2. the current request's scheme + host, when somebody clicked something;
  3. BASE_URL alone — a path, which is no worse than what a caller would have
     done unaided, so an install that has configured nothing is not made worse.

```php
function publicBaseUrl(PDO $conn): string
```

Defined in [includes/public_url.php](/code/files/includes/public_url.php.md), line 126.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[publicBaseUrlSetting()](/code/functions/publicBaseUrlSetting.md), [publicUrlWithAppPath()](/code/functions/publicUrlWithAppPath.md), [requestIsHttps()](/code/functions/requestIsHttps.md)

## Called from

- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [api/tickets/get_public_base_url.php](/code/files/api/tickets/get_public_base_url.php.md)
- [includes/asset_labels.php](/code/files/includes/asset_labels.php.md) - in [assetPublicBaseUrl()](/code/functions/assetPublicBaseUrl.md)
- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainPublicLink()](/code/functions/domainPublicLink.md)
- [includes/public_url.php](/code/files/includes/public_url.php.md) - in [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md)

