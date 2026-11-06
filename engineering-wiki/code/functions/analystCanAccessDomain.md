# analystCanAccessDomain()

May this analyst access this *domain* (#154)? The Domains-module twin of `analystCanAccessAsset()` below: single-company → always true; NULL tenant = the Default company; unknown id → false; part-migrated table → true. A domain is SCOPED DATA, never shared between companies.

```php
function analystCanAccessDomain(PDO $conn, int $analystId, $domainId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 521.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` |  |  | the domain (domains.id), any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 530 | `analystCanAccessDomain` | read [domains](/code/tables/domains.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [api/domains/get.php](/code/files/api/domains/get.php.md)
- [api/domains/people.php](/code/files/api/domains/people.php.md)
- [api/domains/save.php](/code/files/api/domains/save.php.md)
- [domains/view.php](/code/files/domains/view.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkDomainTenant()](/code/functions/domainLinkDomainTenant.md), [domainsLinkedTo()](/code/functions/domainsLinkedTo.md), [domainLinkPickDomains()](/code/functions/domainLinkPickDomains.md), [domainsForContract()](/code/functions/domainsForContract.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewDomain()](/code/functions/recordPreviewDomain.md)

