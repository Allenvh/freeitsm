# apiSerializeDomain()

Shapes a domain row for the API - names, company, status, registrar and account, dates with days to expiry, and the rest of the record. The auth code itself is never included - only auth_code_set.

```php
function apiSerializeDomain(array $r): array
```

Defined in [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the domains row as `apiDomainSelect()` loads it, with the lookup, contact and company names |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsGet()](/code/functions/apiDomainsGet.md), [apiDomainsCreate()](/code/functions/apiDomainsCreate.md), [apiDomainsUpdate()](/code/functions/apiDomainsUpdate.md), [apiDomainsLookup()](/code/functions/apiDomainsLookup.md), [apiDomainsCheck()](/code/functions/apiDomainsCheck.md)

