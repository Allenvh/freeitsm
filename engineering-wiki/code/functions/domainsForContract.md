# domainsForContract()

Domains under one contract (domains.contract_id) that this analyst can see.

```php
function domainsForContract(PDO $conn, int $analystId, int $contractId): array
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 392.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$contractId` | `int` |  | the contract whose domains to list; 0 or less gives none |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 395 | `domainsForContract` | read [domains](/code/tables/domains.md) |  |

## Calls

[analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [domainLinkDomainRows()](/code/functions/domainLinkDomainRows.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)

