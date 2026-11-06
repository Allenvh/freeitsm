# settingKeyPrefixes()

Prefix -> owner, for keys generated at runtime rather than named in code.

Only the System colours page needs this: it posts one 'module_color_<module>' key per
module in the waffle, so the key set is a pattern, not a list. Keep this map tiny —
a prefix is a blunter instrument than an exact key, and every entry widens what the
generic writer can reach.

```php
function settingKeyPrefixes(): array
```

Defined in [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), line 180.

**Returns:** array<string,array{module:string,cap:?string,tab:string}>

## Called from

- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [settingKeyOwner()](/code/functions/settingKeyOwner.md)

