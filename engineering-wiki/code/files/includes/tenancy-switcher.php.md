# includes/tenancy-switcher.php

> PHP - 203 lines

Company (tenant) switcher for the shared header.

`renderTenantSwitcher()` emits NOTHING unless multi-tenancy is active (i.e. a
second company exists). So on a single-company install the header is exactly
as it was — this is the opt-in/invisible-at-N=1 principle in the UI.

Pure presentation; all logic lives in tenancy.php. Defensive throughout — it
must never break the header, so any error simply renders nothing.

This IS wired into the header and has been for some time — the note that used to
sit here said it wasn't, which is the kind of stale comment that makes a reviewer
discount a live control as dead code.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [renderTenantSwitcher()](/code/functions/renderTenantSwitcher.md) | 24 | Render the company switcher (button + dropdown) for the header. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 | `renderTenantSwitcher` | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md)

**Calls:** [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [getTenantById()](/code/functions/getTenantById.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

