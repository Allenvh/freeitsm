# ldapStringifyId()

Turn a raw attribute value into something storable as text.

AD's objectGUID is BINARY; OpenLDAP's entryUUID is already a readable
string. Hex-encode anything that is not clean printable text so the
`subject` column holds a stable, comparable value either way.

```php
function ldapStringifyId(string $raw): string
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 105.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the raw GUID attribute value - binary objectGUID on AD, text entryUUID on OpenLDAP |

**Returns:** `string`

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncMapPerson()](/code/functions/dsyncMapPerson.md)
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapAuthenticate()](/code/functions/ldapAuthenticate.md)

