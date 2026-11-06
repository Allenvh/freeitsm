# includes/domains/links.php

> PHP - 402 lines

Domains, joined to the rest of FreeITSM (3.0.0) - the ONE place that decides who may see a link and who may make one, from either side.

Four kinds of link, each a plain join table:

  cmdb     domain_cmdb_objects        the configuration items that depend on it
  service  domain_status_services     the Service Status services that run on it
  ticket   ticket_domains             tickets about it
  article  domain_knowledge_articles  runbooks - knowledge articles pinned to it

plus the contract, which is the domain's own `contract_id` column (one per
domain, set in its edit dialog or from the contract's page).

🔑 THE RULE, for every kind and both directions: a link is only shown, made or
removed between two records the analyst can already open - Domains plus the
other module, and the record itself (its company, or for an article its
knowledge permissions). A link the reader cannot see both ends of does not
exist for them: it is left out, never shown as "hidden".

🔑 And a link never crosses companies. A CI or a ticket must be in the
domain's own company - the rule ticket <-> CMDB links already follow. Status
services and knowledge articles are not company records (services are
install-wide; articles have their own audience model), so they are judged by
their own permissions only.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainLinkKinds()](/code/functions/domainLinkKinds.md) | 37 | kind => table, the column naming the other record, and the module it belongs to. |
| [domainLinksReady()](/code/functions/domainLinksReady.md) | 48 | Before Database Verification the tables are not there: no links, never an error. |
| [domainLinkKind()](/code/functions/domainLinkKind.md) | 67 | Returns the definition (table, column, module...) of one link kind; throws a validation ServiceError for an unknown kind. |
| [domainLinkKindAllowed()](/code/functions/domainLinkKindAllowed.md) | 79 | May this analyst use links of this kind at all? (Domains AND the other module.) |
| [domainLinkTenantOf()](/code/functions/domainLinkTenantOf.md) | 93 | A record's company with NULL read as the Default company. |
| [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md) | 110 | Is the OTHER end of a link one this analyst may see, and (for company records) in the domain's company? `$domainTenant` null = do not compare. |
| [domainLinkDomainTenant()](/code/functions/domainLinkDomainTenant.md) | 140 | The domain's company (Default for NULL), or a not-found for a domain out of reach. |
| [domainLinkDescribe()](/code/functions/domainLinkDescribe.md) | 156 | One linked record, shaped for display: id, label, sub-line, link, and kind-specific extras. |
| [domainLinks()](/code/functions/domainLinks.md) | 196 | Everything linked to one domain, per kind, for this analyst: a kind they cannot use is left out entirely; within a kind, rows they cannot see are dropped. |
| [domainLinkAdd()](/code/functions/domainLinkAdd.md) | 219 | Links a record of one kind to a domain, when the analyst can reach both modules and both records in the domain's company; returns false when the link already existed. |
| [domainLinkRemove()](/code/functions/domainLinkRemove.md) | 239 | Removes a domain link, under the same access rules as adding one; returns false when there was nothing to remove. |
| [domainLinkSearch()](/code/functions/domainLinkSearch.md) | 262 | Records of one kind that could be linked to a domain: matching `$q`, in the domain's company, visible to the analyst, not already linked. At most 20. |
| [domainsLinkedTo()](/code/functions/domainsLinkedTo.md) | 313 | The other direction: the domains linked to one CI / service / ticket / article that this analyst can see. Empty (not an error) when they cannot open Domains or the other record, so a page can call it unconditionally. |
| [domainLinkPickDomains()](/code/functions/domainLinkPickDomains.md) | 334 | The picker on the OTHER side: domains that could be linked to this CI / service / ticket / article - visible, matching `$q`, not already linked, and for a company record in that record's company. At most 20. |
| [domainLinkDomainRows()](/code/functions/domainLinkDomainRows.md) | 360 | Domains by id, shaped for a list on another module's page. |
| [domainsForContract()](/code/functions/domainsForContract.md) | 392 | Domains under one contract (domains.contract_id) that this analyst can see. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 120 | `domainLinkTargetOk` | read [tickets](/code/tables/tickets.md) |  |
| 125 | `domainLinkTargetOk` | read [status_services](/code/tables/status_services.md) |  |
| 162 | `domainLinkDescribe` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 167 | `domainLinkDescribe` | read [status_services](/code/tables/status_services.md) |  |
| 173 | `domainLinkDescribe` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 181 | `domainLinkDescribe` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 274 | `domainLinkSearch` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 279 | `domainLinkSearch` | read [tickets](/code/tables/tickets.md) |  |
| 284 | `domainLinkSearch` | read [status_services](/code/tables/status_services.md) |  |
| 290 | `domainLinkSearch` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 340 | `domainLinkPickDomains` | read [domains](/code/tables/domains.md) |  |
| 365 | `domainLinkDomainRows` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 395 | `domainsForContract` | read [domains](/code/tables/domains.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/entity_links.php](/code/files/includes/entity_links.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Included by:** [api/domains/links.php](/code/files/api/domains/links.php.md), [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md)

**Calls:** [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [entityLink()](/code/functions/entityLink.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

