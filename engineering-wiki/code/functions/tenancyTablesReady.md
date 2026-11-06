# tenancyTablesReady()

Does the tenants table exist? (Cached.) Lets every other function degrade gracefully on an install that hasn't been migrated yet.

⚠️ THIS IS THE MASTER SWITCH, SO IT FAILS CLOSED. Every guard below begins with
`if (!isMultiTenant(`$conn`)) return true;`, and `isMultiTenant()` is this function
two calls down. A bare `catch { `$ready` = false; }` therefore did not degrade one
guard — it turned off ALL of them, for the whole request, from a single failed
`SELECT 1 FROM tenants`. That is the exact fail-open F9 set out to remove, sitting
one frame above the seven catches F9 actually fixed; found by Erlend Volden when
he re-reviewed those fixes.

So: only a genuinely missing table means "single-company install". Anything else —
lock-wait timeout, dropped connection, permissions — is not evidence about how many
companies exist, so we say "ready" and let the real queries fail into
`tenancyDegradeAllowed()`, which denies. Not cached in that case: a transient error
must not pin the answer for the rest of the request.

```php
function tenancyTablesReady(PDO $conn): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 80.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 84 | `tenancyTablesReady` | read [tenants](/code/tables/tenants.md) |  |

## Calls

[dbErrorIsMissingTable()](/code/functions/dbErrorIsMissingTable.md)

## Called from

- [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md) - in `CostCentresService::assertCompany`
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [tenantCount()](/code/functions/tenantCount.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [getTenantById()](/code/functions/getTenantById.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md)
- [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md) - in [tenantSetting()](/code/functions/tenantSetting.md)

