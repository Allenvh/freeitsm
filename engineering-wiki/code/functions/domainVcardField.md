# domainVcardField()

Returns one field's value (for example 'fn' or 'org') from an RDAP jCard, or null when absent or empty.

```php
function domainVcardField(array $vcard, string $field): ?string
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 308.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$vcard` | `array` |  | an RDAP entity's vcardArray (jCard) - ['vcard', [[name, params, type, value], ...]] |
| `$field` | `string` |  | the jCard property name to read, e.g. 'fn' or 'org' |

**Returns:** `?string`

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainRdapLookup()](/code/functions/domainRdapLookup.md)

