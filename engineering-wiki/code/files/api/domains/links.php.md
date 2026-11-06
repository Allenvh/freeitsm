# api/domains/links.php

> API endpoint - 74 lines

Domains joined to the rest of FreeITSM (3.0.0) - one endpoint for both sides. Every rule lives in includes/domains/links.php; this only routes.

GET  ?domain_id=N                     everything linked to a domain, per kind
GET  ?domain_id=N&search=KIND&q=      records that could be linked to it
GET  ?for=KIND&id=N                   the domains linked to a CI/service/ticket/article
GET  ?for=KIND&id=N&pick=1&q=         domains that could be linked to it
GET  ?contract_id=N                   the domains under a contract
POST {action:'add'|'remove', domain_id, kind, target_id}
POST {action:'set_contract', domain_id, contract_id|null}
                                      through DomainsService, so it is audited and
                                      the contract's visibility rule is checked
POST {action:'raise_incident', domain_id}
                                      Service Status: raise for the linked services
                                      now (needs the Service Status module too)

Needs the Domains module (the bootstrap) - from another module's page an
analyst without Domains simply gets no domain section at all.

## The endpoint

| | |
|---|---|
| URL | `api/domains/links.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |
| Reads | `GET contract_id`, `GET for`, `GET id`, `GET pick`, `GET q`, `GET domain_id`, `GET search` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 |  | read [domains](/code/tables/domains.md) |  |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/links.php](/code/files/includes/domains/links.php.md), [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [domainApiBody()](/code/functions/domainApiBody.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainLinkAdd()](/code/functions/domainLinkAdd.md), [domainLinkPickDomains()](/code/functions/domainLinkPickDomains.md), [domainLinkRemove()](/code/functions/domainLinkRemove.md), [domainLinks()](/code/functions/domainLinks.md), [domainLinkSearch()](/code/functions/domainLinkSearch.md), [domainLinksReady()](/code/functions/domainLinksReady.md), [domainSetting()](/code/functions/domainSetting.md), [domainsForContract()](/code/functions/domainsForContract.md), [domainsLinkedTo()](/code/functions/domainsLinkedTo.md), [domainStatusRaise()](/code/functions/domainStatusRaise.md), [domainStatusState()](/code/functions/domainStatusState.md)

