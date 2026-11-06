# domainLinkPickDomains()

The picker on the OTHER side: domains that could be linked to this CI / service / ticket / article - visible, matching `$q`, not already linked, and for a company record in that record's company. At most 20.

```php
function domainLinkPickDomains(
    PDO $conn,
    int $analystId,
    string $kind,
    int $targetId,
    string $q
): array
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 334.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the kind of record on the other side - cmdb \| service \| ticket \| article |
| `$targetId` | `int` |  | that record's id |
| `$q` | `string` |  | what the user typed; matched anywhere in the domain name or display name |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 340 | `domainLinkPickDomains` | read [domains](/code/tables/domains.md) |  |

## Calls

[analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [domainLinkDomainRows()](/code/functions/domainLinkDomainRows.md), [domainLinkKindAllowed()](/code/functions/domainLinkKindAllowed.md), [domainLinkKinds()](/code/functions/domainLinkKinds.md), [domainLinksReady()](/code/functions/domainLinksReady.md), [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md), [domainLinkTenantOf()](/code/functions/domainLinkTenantOf.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)

