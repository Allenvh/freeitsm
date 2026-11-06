# recentTrailLabelsForType()

One query for the labels of one type, then the record gate on each.

```php
function recentTrailLabelsForType(PDO $conn, int $analystId, string $type, array $ids): array
```

Defined in [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), line 437.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$type` | `string` |  | the record type, a RECENT_TRAIL_MODULES key such as 'ticket' |
| `$ids` | `array` |  | the distinct record ids of that type to label |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 446 | `recentTrailLabelsForType` | read [tickets](/code/tables/tickets.md) |  |
| 452 | `recentTrailLabelsForType` | read [tasks](/code/tables/tasks.md) |  |
| 457 | `recentTrailLabelsForType` | read [problems](/code/tables/problems.md) |  |
| 463 | `recentTrailLabelsForType` | read [changes](/code/tables/changes.md) |  |
| 470 | `recentTrailLabelsForType` | read [assets](/code/tables/assets.md) |  |
| 479 | `recentTrailLabelsForType` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 484 | `recentTrailLabelsForType` | read [domains](/code/tables/domains.md) |  |
| 490 | `recentTrailLabelsForType` | read [projects](/code/tables/projects.md) |  |
| 492 | `recentTrailLabelsForType` | read [projects](/code/tables/projects.md) |  |
| 503 | `recentTrailLabelsForType` | read [users](/code/tables/users.md) |  |
| 508 | `recentTrailLabelsForType` | read [tenants](/code/tables/tenants.md) |  |
| 515 | `recentTrailLabelsForType` | read [suppliers](/code/tables/suppliers.md) |  |
| 520 | `recentTrailLabelsForType` | read [contacts](/code/tables/contacts.md) |  |
| 528 | `recentTrailLabelsForType` | read [contracts](/code/tables/contracts.md) |  |
| 541 | `recentTrailLabelsForType` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [contractCanView()](/code/functions/contractCanView.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [projectIdVisibleTo()](/code/functions/projectIdVisibleTo.md)

## Called from

- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabels()](/code/functions/recentTrailLabels.md)

