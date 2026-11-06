# selfServicePortalLogoUrl()

The portal logo as a URL the browser can actually fetch, or '' when there is none (the caller then falls back to the main branding logo).

🔴 The setting stores a path relative to the APP ROOT. Writing it straight
into src="" works only from a page at the root; from /self-service/ it
resolves one directory too deep and 404s. Everything that renders this logo
must come through here.

Mirrors `brandingLogoUrl()`, including the existence check: a pointer left
behind by a deleted file would render a broken image in the portal header.

```php
function selfServicePortalLogoUrl(PDO $conn): string
```

Defined in [includes/self_service_settings.php](/code/files/includes/self_service_settings.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[selfServicePortalSettings()](/code/functions/selfServicePortalSettings.md)

## Called from

- [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)

