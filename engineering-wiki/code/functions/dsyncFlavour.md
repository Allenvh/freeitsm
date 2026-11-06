# dsyncFlavour()

Which flavour is this provider?

There is no column recording it: the settings screen applies the flavour's
defaults into the real ldap_attr_* columns when the provider is saved, so the
row only ever holds concrete attribute names. The sign-in attributes are
therefore the evidence — `uid` means OpenLDAP, `sAMAccountName` means AD.

Inferring beats adding a column: the existing values are what the directory is
actually being queried with, so they cannot disagree with reality the way a
separate flavour field could after somebody hand-edits an attribute.

```php
function dsyncFlavour(array $provider): string
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$provider` | `array` |  | the auth_providers row - ldap_attr_username and ldap_attr_guid are read |

**Returns:** `string`

## Called from

- [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncAttr()](/code/functions/dsyncAttr.md)

