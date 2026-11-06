# endpointLine()

One discovery-endpoint line, printing the ADDRESS the provider published.

Three things are worth saying about each, and only the first was ever said:
  - present or missing;
  - RELATIVE, which is the failure that masquerades as a bug in this app —
    a bare "/oidc/authorize" is resolved by the browser against THIS host, so
    the user lands here, gets a 404, and nothing on screen implicates the IdP;
  - a host that differs from the issuer's. Perfectly legal (Entra and Okta
    both do it), so it is a note and never a failure — but when somebody is
    staring at an unexpected domain it is the line that explains it.

```php
function endpointLine(string $key, array $doc, string $issuerUrl, string $what): string
```

Defined in [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md), line 74.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the discovery document key, e.g. 'authorization_endpoint' |
| `$doc` | `array` |  | the provider's decoded OIDC discovery document |
| `$issuerUrl` | `string` |  | the provider's configured issuer URL, to compare hosts with |
| `$what` | `string` |  | a short note on what the endpoint is for, printed under the line |

**Returns:** `string`

## Calls

[maskGuids()](/code/functions/maskGuids.md)

## Called from

- [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md)

