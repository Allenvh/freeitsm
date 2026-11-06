# apiDomainRefuseAuthCode()

Answers 422 and stops when a request body carries auth_code - it can only be read or set on the Domains screen, where it is permission-controlled and audited.

```php
function apiDomainRefuseAuthCode(array $body): void
```

Defined in [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), line 161.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsCreate()](/code/functions/apiDomainsCreate.md), [apiDomainsUpdate()](/code/functions/apiDomainsUpdate.md)

