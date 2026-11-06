# publicUrlWithAppPath()

Make sure the configured address carries the app's sub-path exactly once.

⚠️ THE TWO SETTINGS ARE DIFFERENT SHAPES, and this is where that is reconciled.
`messaging_public_base_url` is an ORIGIN by design — its own save endpoint strips
any path, because `messagingWebhookUrl()` adds the app root itself. `public_base_url`
is the whole root, sub-path included, because that is the natural thing to type
and what the integrations code has always appended paths to directly.

Left alone, an install at `https://example.com/freeitsm-app` that had only ever
configured the messaging setting would get `https://example.com/self-service/...`
— a link to a page that is not there. Appending unconditionally instead gives
`.../freeitsm-app/freeitsm-app/...` for anyone who typed the sub-path in. So the
sub-path is added only when it is not already on the end, which is the same guard
`messagingWebhookUrl()` arrived at for the same reason.

```php
function publicUrlWithAppPath(string $root): string
```

Defined in [includes/public_url.php](/code/files/includes/public_url.php.md), line 92.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$root` | `string` |  | the configured public address, with or without the app's sub-path |

**Returns:** `string`

## Called from

- [includes/public_url.php](/code/files/includes/public_url.php.md) - in [publicBaseUrl()](/code/functions/publicBaseUrl.md)

