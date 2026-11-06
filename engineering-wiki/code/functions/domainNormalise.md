# domainNormalise()

Turns a typed or pasted URL, e-mail address or name into the one stored domain name - lower case, no scheme, path, port, root dot or leading www., internationalised names as punycode with the Unicode form as the display name.

```php
function domainNormalise(string $raw): array
```

Defined in [includes/domains/names.php](/code/files/includes/domains/names.php.md), line 25.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | what somebody typed or pasted (untrusted) - a name, URL or e-mail address, in any script |

**Returns:** array{ok:bool, name?:string, display?:?string, error?:string}

## Called from

- [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsList()](/code/functions/apiDomainsList.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::createDomain`, `DomainsService::updateDomain`, `DomainsService::bulkAdd`

