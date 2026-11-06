# ldapFlavourDefaults()

Sensible per-flavour defaults, offered by the admin UI as presets. 'ad' = Active Directory / Samba AD DC, 'openldap' = OpenLDAP / 389 / FreeIPA.

```php
function ldapFlavourDefaults(string $flavour): array
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 35.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$flavour` | `string` |  | 'openldap' for OpenLDAP-style defaults; anything else (normally 'ad') gives Active Directory's |

**Returns:** `array`

*Nothing calls this function by name.*

