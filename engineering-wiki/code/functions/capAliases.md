# capAliases()

Retired keys -> their current replacement.

A capability key appears in exactly one place in code (its Cap:: constant), so renaming
one is a one-line edit — but grants already in rbac_role_capabilities still hold the OLD
string. Map it here and those grants keep working; omit it and they are silently dropped
(which is safe, but surprising). Applied in `capFromKey()`.

```php
function capAliases(): array
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 405.

**Returns:** `array`

## Called from

- [includes/capabilities.php](/code/files/includes/capabilities.php.md) - in [capFromKey()](/code/functions/capFromKey.md), [capSelfCheck()](/code/functions/capSelfCheck.md)

