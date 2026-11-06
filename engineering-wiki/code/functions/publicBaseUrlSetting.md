# publicBaseUrlSetting()

The address an administrator has explicitly configured, or '' if none.

Separate from `publicBaseUrl()` on purpose: the UI needs to distinguish "nothing is
set, so a link sent from cron will be broken" from "something is set". Those are
different facts and only this function can tell them apart — `publicBaseUrl()`
always returns *something*, which is what makes it safe to build a link with and
useless for deciding whether to warn.

`messaging_public_base_url` is read as a fallback because it already holds exactly
this fact on installs that configured web chat; `public_base_url` is preferred so
the general setting supersedes it without needing a migration.

```php
function publicBaseUrlSetting(PDO $conn): string
```

Defined in [includes/public_url.php](/code/files/includes/public_url.php.md), line 50.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 | `publicBaseUrlSetting` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/tickets/get_public_base_url.php](/code/files/api/tickets/get_public_base_url.php.md)
- [includes/csrf.php](/code/files/includes/csrf.php.md) - in [csrfOwnHosts()](/code/functions/csrfOwnHosts.md)
- [includes/public_url.php](/code/files/includes/public_url.php.md) - in [publicBaseUrl()](/code/functions/publicBaseUrl.md)

