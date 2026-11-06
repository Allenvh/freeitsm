# landingOnPreferenceSaved()

Hook for api/system/set_user_preference.php.

The preference row is the source of truth; the cookie is a cache of it that
exists purely so PHP can read the value before anyone has authenticated.
Saving the two together keeps them from drifting apart.

```php
function landingOnPreferenceSaved(string $key, $value): void
```

Defined in [includes/landing.php](/code/files/includes/landing.php.md), line 178.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the preference key just saved; anything but the landing key is ignored |
| `$value` |  |  | the value saved; an unrecognised one clears the cookie |

**Returns:** `void`

## Calls

[landingIsValid()](/code/functions/landingIsValid.md), [landingSetCookie()](/code/functions/landingSetCookie.md)

## Called from

- [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md)

