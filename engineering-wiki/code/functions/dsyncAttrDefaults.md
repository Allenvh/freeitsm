# dsyncAttrDefaults()

Attribute defaults, per directory flavour. These are the names the fixture in docker/ldap-test uses, and the ones real installs overwhelmingly have.

⚠️ AD and OpenLDAP genuinely differ — sAMAccountName vs uid, and OpenLDAP has
no equivalent of most of the rest. Guessing is not possible, which is why every
one of these is configurable per provider.

```php
function dsyncAttrDefaults(string $flavour): array
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 58.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$flavour` | `string` |  | 'openldap' or 'ad'; anything other than 'openldap' gets the AD names |

**Returns:** `array`

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncAttr()](/code/functions/dsyncAttr.md)

