# includes/services/analysts.php

> PHP - 452 lines

AnalystsService — creating analysts and setting what they may do (3.5.0).

Built for the REST API's analyst provisioning (PR #174, from @Kraleemil's
design): an external identity source - an HR feed, an OIDC provider that owns
staff membership - keeps analysts and their access in step with it.

ACCESS has four parts, each with an "all" switch and a specific list:
  - admin         analysts.is_admin (System module, everything administrative)
  - modules       analysts.can_access_all_modules + analyst_modules
  - companies     analysts.can_access_all_tenants + analyst_tenant_access
  - capabilities  RBAC settings capabilities, held on ONE role per analyst that
                  this service owns (rbac_roles.managed_for_analyst_id). Roles
                  assigned by hand in System -> Roles and team grants are never
                  touched; System -> Roles shows the managed one read-only.

🔴 WHO MAY CALL THIS is the caller's decision, not this file's. In the app only
administrators manage analysts (admin_api_guard.php), and the REST adapter
(api/v1/resources/analysts.php) holds keys to the same rule. The rules below
are the ones that hold whoever calls:
  - never remove or deactivate the LAST active administrator (the same guard
    System -> Analysts has), which would lock everyone out of System;
  - an actor never deactivates or demotes themselves;
  - module, company and capability keys are validated - a typo is refused,
    never silently dropped;
  - a new analyst starts with NOTHING: no modules, no companies, no admin, and a
    local password nobody knows (they sign in through their provider).

## Classes

- [AnalystsService](/code/classes/AnalystsService.md) (class, line 35)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `AnalystsService::load` | read [analysts](/code/tables/analysts.md) |  |
| 77 | `AnalystsService::create` | **insert** [analysts](/code/tables/analysts.md) (`username`, `password_hash`, `full_name`, `email`, `is_active`, `auth_provider_id`, `is_admin`, `can_access_all_modules`, `can_access_all_tenants`, `created_datetime`, `last_modified_datetime`) |  |
| 139 | `AnalystsService::update` | **update** [analysts](/code/tables/analysts.md) |  |
| 157 | `AnalystsService::access` | read [analyst_modules](/code/tables/analyst_modules.md) |  |
| 159 | `AnalystsService::access` | read [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 163 | `AnalystsService::access` | read [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) |  |
| 219 | `AnalystsService::setAccess` | **update** [analysts](/code/tables/analysts.md) (`is_admin`, `can_access_all_modules`, `can_access_all_tenants`, `last_modified_datetime`) |  |
| 224 | `AnalystsService::setAccess` | **delete** [analyst_modules](/code/tables/analyst_modules.md) |  |
| 225 | `AnalystsService::setAccess` | **insert** [analyst_modules](/code/tables/analyst_modules.md) (`analyst_id`, `module_key`) |  |
| 229 | `AnalystsService::setAccess` | **delete** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 230 | `AnalystsService::setAccess` | **insert** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) (`analyst_id`, `tenant_id`) |  |
| 247 | `AnalystsService::managedRoleId` | read [rbac_roles](/code/tables/rbac_roles.md) |  |
| 264 | `AnalystsService::assertNotLastAdmin` | read [analysts](/code/tables/analysts.md) |  |
| 289 | `AnalystsService::writeManagedRole` | **insert** [rbac_roles](/code/tables/rbac_roles.md) (`name`, `description`, `is_active`, `created_by_id`, `managed_for_analyst_id`, `created_datetime`, `updated_datetime`) |  |
| 299 | `AnalystsService::writeManagedRole` | **delete** [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) |  |
| 300 | `AnalystsService::writeManagedRole` | **insert** [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) (`role_id`, `capability_key`) |  |
| 302 | `AnalystsService::writeManagedRole` | **insert** [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md) (`analyst_id`, `role_id`, `created_datetime`) |  |
| 304 | `AnalystsService::writeManagedRole` | **update** [rbac_roles](/code/tables/rbac_roles.md) (`updated_datetime`) |  |
| 411 | `AnalystsService::assertEmailFree` | read [analysts](/code/tables/analysts.md) |  |
| 427 | `AnalystsService::providerId` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 445 | `AnalystsService::uniqueUsername` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Included by:** [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md), [includes/sso_claims.php](/code/files/includes/sso_claims.php.md)

**Calls:** [capFromKey()](/code/functions/capFromKey.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getAllTenants()](/code/functions/getAllTenants.md), [getAnalystAllowedModules()](/code/functions/getAnalystAllowedModules.md), [getAnalystCapabilities()](/code/functions/getAnalystCapabilities.md), [getModuleRegistry()](/code/functions/getModuleRegistry.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

