# landingTargets()

The only place a landing key maps to a URL.

Paths are relative to the application root, because that is where index.php
performs the redirect from.

```php
function landingTargets(): array
```

Defined in [includes/landing.php](/code/files/includes/landing.php.md), line 39.

**Returns:** `array`

## Called from

- [api/system/save_branding.php](/code/files/api/system/save_branding.php.md)
- [includes/landing.php](/code/files/includes/landing.php.md) - in [landingIsValid()](/code/functions/landingIsValid.md), [landingResolve()](/code/functions/landingResolve.md)

