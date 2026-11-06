# apiDomainsFindings()

GET /domains/{id}/findings — the last check's findings, with English text

```php
function apiDomainsFindings(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), line 351.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiDomainFindings()](/code/functions/apiDomainFindings.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiLoadDomain()](/code/functions/apiLoadDomain.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

