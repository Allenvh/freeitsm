# api/system/global_search.php

> API endpoint - 720 lines

API: Global search for the command palette (⌘/Ctrl-K).

A single aggregator that fans out across the entity types worth jumping to
from anywhere — tickets, CMDB configuration items and assets — and returns a
flat, typed result list the palette renders directly.

Every source is gated two ways, so the palette can never surface something the
analyst couldn't otherwise reach:
  1. Module access — skipped entirely unless the module is in the analyst's
     allowed_modules (the same list the waffle launcher honours). Reads aren't
     capability-gated in FreeITSM, so module membership is the right gate here.
  2. Company scope — each query runs through the module's own tenancy filter
     (ticketTenantFilter / activeTenantFilter), exactly as that module's own
     list endpoint does, so results respect the active company.

Each source is wrapped in its own try/catch: on a part-migrated install a
missing table just yields no rows for that type rather than failing the whole
search.

Query params:
  q — text to match (required, min 2 chars); matched as a substring.

Returns: { success, results: [ { type, module, id, title, subtitle, url } ] }
where `url` is relative to BASE_URL (the client prefixes it).

## The endpoint

| | |
|---|---|
| URL | `api/system/global_search.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php) |
| Reads | `GET q` |
| Called from | [assets/js/command-palette.js](/code/files/assets/js/command-palette.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 |  | read [tickets](/code/tables/tickets.md) |  |
| 95 |  | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 134 |  | read [asset_fields](/code/tables/asset_fields.md) |  |
| 152 |  | read [assets](/code/tables/assets.md) |  |
| 167 |  | read [assets](/code/tables/assets.md), read [asset_field_values](/code/tables/asset_field_values.md) |  |
| 218 |  | read [changes](/code/tables/changes.md) |  |
| 243 |  | read [problems](/code/tables/problems.md) |  |
| 276 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 303 |  | read [contracts](/code/tables/contracts.md) |  |
| 329 |  | read [users](/code/tables/users.md) |  |
| 351 |  | read [tenants](/code/tables/tenants.md) |  |
| 369 |  | read [suppliers](/code/tables/suppliers.md) |  |
| 380 |  | read [contacts](/code/tables/contacts.md), read [suppliers](/code/tables/suppliers.md) |  |
| 401 |  | read [domains](/code/tables/domains.md) |  |
| 431 |  | read [projects](/code/tables/projects.md) |  |
| 468 |  | read [documents](/code/tables/documents.md) |  |
| 482 |  | read [document_links](/code/tables/document_links.md) |  |
| 567 |  | read [tickets](/code/tables/tickets.md) |  |
| 604 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 677 |  | read [documents](/code/tables/documents.md) |  |
| 685 |  | read [document_links](/code/tables/document_links.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/entity_links.php](/code/files/includes/entity_links.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), [includes/search/search.php](/code/files/includes/search/search.php.md), [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [documentAccessibleTypes()](/code/functions/documentAccessibleTypes.md), [documentCanViewParent()](/code/functions/documentCanViewParent.md), [documentEntityDef()](/code/functions/documentEntityDef.md), [documentParentName()](/code/functions/documentParentName.md), [documentVisibilityClause()](/code/functions/documentVisibilityClause.md), [entityLink()](/code/functions/entityLink.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [searchCorpusQuery()](/code/functions/searchCorpusQuery.md), [searchScopeForAnalyst()](/code/functions/searchScopeForAnalyst.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

