# apiDomainAccountsList()

GET /domain-registrar-accounts - the registrar accounts in the key's companies, with registrar, owner, company and how many domains each holds.

```php
function apiDomainAccountsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), line 420.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 423 | `apiDomainAccountsList` | read [domains](/code/tables/domains.md), read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

