# domainLinkTenantOf()

A record's company with NULL read as the Default company.

```php
function domainLinkTenantOf(PDO $conn, string $table, int $id): ?int
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the table holding the record - interpolated into SQL, so only literals from this file |
| `$id` | `int` |  | the record's id; a missing record gives null |

**Returns:** `?int`

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md), [domainLinkDomainTenant()](/code/functions/domainLinkDomainTenant.md), [domainLinkPickDomains()](/code/functions/domainLinkPickDomains.md)

