# analystCanAccessCmdbObject()

May this analyst access this *CMDB configuration item* (by its owning company)? The CMDB twin of `analystCanAccessAsset()` — same rules (single-company → always true; NULL tenant treated as Default-owned; unknown id → false; part-migrated table → true).

A CI belongs to exactly one company (there are no shared CIs), so this is
also the gate for the same-company invariant on parent/child links,
relationships and object_ref properties: both ends must pass.

```php
function analystCanAccessCmdbObject(PDO $conn, int $analystId, $objectId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 427.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$objectId` |  |  | the CMDB item (cmdb_objects.id), any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 436 | `analystCanAccessCmdbObject` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [api/cmdb/create_impact_diagram.php](/code/files/api/cmdb/create_impact_diagram.php.md)
- [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md)
- [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md)
- [api/cmdb/get_object_impact.php](/code/files/api/cmdb/get_object_impact.php.md)
- [api/cmdb/get_object_tickets.php](/code/files/api/cmdb/get_object_tickets.php.md)
- [api/network-mapper/get_related_objects.php](/code/files/api/network-mapper/get_related_objects.php.md)
- [api/tickets/save_ticket_cmdb_object.php](/code/files/api/tickets/save_ticket_cmdb_object.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)

