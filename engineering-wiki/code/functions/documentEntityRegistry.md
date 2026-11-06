# documentEntityRegistry()

Every kind of thing a document can be attached to.

⚠️ ADDING A MODULE IS ADDING AN ENTRY HERE, AND NOTHING ELSE. That is the
whole point — the document system never learns any module's rules.

Each entry:
  module  — the key in the analyst's allowed_modules (the read gate)
  table   — where the parent lives
  label   — what to call it in the UI
  title   — column to show as the parent's name
  url     — sprintf pattern taking the parent id, for linking back to the
            record. Relative to the install root, as the palette's urls are.
  can     — fn(PDO,int `$analystId`,int `$id`): bool   exact check, or null for
            "module + filter is the whole rule"
  filter  — fn(PDO,int `$analystId`,string `$alias`): [sql,params]  the SET form,
            or null when the entity has no per-row scoping at all
  alive   — extra SQL keeping deleted parents out (a deleted parent must not
            keep a document visible)

```php
function documentEntityRegistry(): array
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 70.

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 109 | `documentEntityRegistry` | read [tickets](/code/tables/tickets.md) |  |
| 114 | `documentEntityRegistry` | read [tickets](/code/tables/tickets.md) |  |
| 120 | `documentEntityRegistry` | read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 128 | `documentEntityRegistry` | read [tickets](/code/tables/tickets.md) |  |
| 136 | `documentEntityRegistry` | read [tickets](/code/tables/tickets.md) |  |
| 295 | `documentEntityRegistry` | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessProject()](/code/functions/analystCanAccessProject.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [contractCanView()](/code/functions/contractCanView.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [leaseCanView()](/code/functions/leaseCanView.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

## Called from

- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityTypes()](/code/functions/documentEntityTypes.md), [documentEntityDef()](/code/functions/documentEntityDef.md), [documentAccessibleTypes()](/code/functions/documentAccessibleTypes.md), [documentsCollectOrphans()](/code/functions/documentsCollectOrphans.md)

