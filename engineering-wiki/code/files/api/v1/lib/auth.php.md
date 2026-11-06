# api/v1/lib/auth.php

> API endpoint - 377 lines

FreeITSM REST API v1 — key authentication, permissions and company scoping.

Keys live in the api_keys table (separate from the legacy plaintext
`apikeys` table used by api/external/). Only a SHA-256 hash of the key is
stored; the full key (fitsm_...) is shown once at creation.

Every key acts as an analyst (api_keys.analyst_id) so that audit rows,
notes and workflow events keep a real author, and carries:
  - a granular permission map (see lib/permissions.php)
  - an optional company scope (api_keys.company_ids JSON; NULL = all)
  - an optional expiry and per-minute rate-limit override

## The endpoint

| | |
|---|---|
| URL | `api/v1/lib/auth.php` |
| Protected by | REST API key |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiAuthenticate()](/code/functions/apiAuthenticate.md) | 20 | Authenticate the request and return the key row (with decoded permissions and company scope). Emits 401/403/429 and stops on failure. |
| [apiExtractKey()](/code/functions/apiExtractKey.md) | 73 | Pull the key from Authorization: Bearer <key>, a raw Authorization value, or X-Api-Key. |
| [apiRequirePermission()](/code/functions/apiRequirePermission.md) | 107 | Enforce the per-key permission map. 403s (with the missing permission named) on failure. |
| [apiRateLimit()](/code/functions/apiRateLimit.md) | 123 | Rate limiting — fixed one-minute windows in api_key_rate_limits, same mechanism as the proven api_rate_limits implementation. Default limit comes from system_settings.api_rate_limit_per_minute (fallback 60); a key can carry its own override. Fails open if the rate-limit table errors. |
| [apiEffectiveCompanyScope()](/code/functions/apiEffectiveCompanyScope.md) | 186 | The companies a key may really reach: its own list, narrowed to its analyst's. |
| [apiKeyCheckCompanies()](/code/functions/apiKeyCheckCompanies.md) | 207 | For System -> API: refuse a company list the key's analyst cannot reach, so a key is never saved promising access it will not have. Throws Exception. |
| [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md) | 226 | May this key access this company? |
| [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md) | 245 | May this key access this row of a tenant-scoped table (by its owning company)? Unknown id => false. The generic by-id gate behind apiKeyCanAccessTicket / apiKeyCanAccessProblem. `$table` is a developer literal, never user input. |
| [apiKeyCanAccessTicket()](/code/functions/apiKeyCanAccessTicket.md) | 263 | May this key access this ticket (by its owning company)? Unknown id => false. |
| [apiKeyCanAccessProblem()](/code/functions/apiKeyCanAccessProblem.md) | 272 | May this key access this problem (by its owning company)? Unknown id => false. |
| [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md) | 285 | SQL predicate scoping any tenant_id-carrying list query to the key's companies. Mirrors `ticketTenantFilter()`: NULL tenant_id rows belong to the Default company. Works for tickets ('t'), problems ('p'), and any future tenant-scoped module. |
| [apiKeyTicketFilter()](/code/functions/apiKeyTicketFilter.md) | 306 | Back-compat name used by the tickets resource — delegates to the generic filter. |
| [apiKeyKnowledgeFilter()](/code/functions/apiKeyKnowledgeFilter.md) | 330 | The Knowledge counterpart of `apiKeyTenantFilter()`. |
| [apiKeyCanAccessArticle()](/code/functions/apiKeyCanAccessArticle.md) | 348 | May this key act on this article? Shared (NULL) articles belong to everyone. |
| [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md) | 365 | The company a NEW ticket created by this key belongs to when the request doesn't say: the Default company if the key may access it, otherwise the key's first scoped company. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 | `apiAuthenticate` | read [api_keys](/code/tables/api_keys.md), read [analysts](/code/tables/analysts.md) |  |
| 56 | `apiAuthenticate` | **update** [api_keys](/code/tables/api_keys.md) (`last_used_at`, `last_used_ip`) |  |
| 129 | `apiRateLimit` | read [system_settings](/code/tables/system_settings.md) |  |
| 142 | `apiRateLimit` | **insert** [api_key_rate_limits](/code/tables/api_key_rate_limits.md) (`api_key_id`, `request_count`, `window_start`) |  |
| 148 | `apiRateLimit` | read [api_key_rate_limits](/code/tables/api_key_rate_limits.md) |  |
| 152 | `apiRateLimit` | **delete** [api_key_rate_limits](/code/tables/api_key_rate_limits.md) |  |
| 191 | `apiEffectiveCompanyScope` | read [tenants](/code/tables/tenants.md) |  |
| 211 | `apiKeyCheckCompanies` | read [tenants](/code/tables/tenants.md) |  |
| 219 | `apiKeyCheckCompanies` | read [tenants](/code/tables/tenants.md) |  |
| 352 | `apiKeyCanAccessArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Included by:** [api/mcp/index.php](/code/files/api/mcp/index.php.md), [api/system/api_keys/create_key.php](/code/files/api/system/api_keys/create_key.php.md), [api/system/api_keys/update_key.php](/code/files/api/system/api_keys/update_key.php.md), [api/v1/lib/bootstrap.php](/code/files/api/v1/lib/bootstrap.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiV1NormalisePermissions()](/code/functions/apiV1NormalisePermissions.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

