# domainLinkDomainRows()

Domains by id, shaped for a list on another module's page.

```php
function domainLinkDomainRows(PDO $conn, array $ids): array
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 360.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ids` | `array` |  | domain ids, already checked as visible to the analyst |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 365 | `domainLinkDomainRows` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md)

## Called from

- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainsLinkedTo()](/code/functions/domainsLinkedTo.md), [domainLinkPickDomains()](/code/functions/domainLinkPickDomains.md), [domainsForContract()](/code/functions/domainsForContract.md)

