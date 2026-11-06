# landingSetCookie()

Write (or clear) the cookie that caches an analyst's choice.

⚠️ Must be called before any output. Pass null to clear, which puts the
analyst back on whatever the administrator chose.

HttpOnly because nothing in the browser needs to read it — the decision is
made in PHP before a page exists. A year, because the whole point is that it
survives long enough to be useful on a machine somebody uses every day.

```php
function landingSetCookie(?string $key): void
```

Defined in [includes/landing.php](/code/files/includes/landing.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `?string` |  | 'analyst' or 'portal' to remember, or null to clear the cookie |

**Returns:** `void`

## Calls

[requestIsHttps()](/code/functions/requestIsHttps.md)

## Called from

- [includes/landing.php](/code/files/includes/landing.php.md) - in [landingRefreshCookieFromPreference()](/code/functions/landingRefreshCookieFromPreference.md), [landingOnPreferenceSaved()](/code/functions/landingOnPreferenceSaved.md)

