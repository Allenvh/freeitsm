# ssoParseEmailDomains()

Split what an admin typed into the "Email domains" box into clean domains.

One per line (commas and semicolons are accepted too, because people paste
lists). A leading "@" is dropped, so "@acme.com" works as well as "acme.com".
Returns ['domains' => [...unique, lowercase...], 'invalid' => [...as typed...]]
so the save can name what it refused rather than silently dropping it.

Matching is on the WHOLE domain: "acme.com" does not cover "uk.acme.com".
A subdomain is a separate line. Predictable beats clever here - a suffix
match would quietly route addresses nobody listed.

```php
function ssoParseEmailDomains($value): array
```

Defined in [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), line 142.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | what the admin typed (one domain per line, commas or semicolons), or an array of domains |

**Returns:** `array`

## Called from

- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)

