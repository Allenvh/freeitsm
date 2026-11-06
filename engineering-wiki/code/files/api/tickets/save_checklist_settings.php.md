# api/tickets/save_checklist_settings.php

> API endpoint - 82 lines

API Endpoint: save the two checklist closure settings (PR #141).

POST { mode: "per_template" | "block_all", empty_mode: "off" | "warn" | "block" }

  mode        what to do about outstanding mandatory steps. 'per_template'
              honours the gate on each checklist; 'block_all' overrides them
              and refuses every close with steps outstanding.
  empty_mode  what to do about a ticket with no checklist attached at all.

Install-wide for now. Both values are read through `tenantSetting()`, which
already resolves a per-company override over this default, so a Companies
column can be added to the tab later without touching the reading side or
migrating anything — the same shape the three classification switches use.

⚠️ These decide whether a close is ALLOWED. Neither decides whether it is
RECORDED: ChecklistsService writes its audit note either way, so loosening a
gate here loses no audit trail.

🔴 'mode' was "warn"|"block" before per-template gating. The stored values
still read correctly — `ticketChecklistClosureMode()` maps a legacy 'block' to
'block_all' and anything else to 'per_template' — but this endpoint only
accepts the new pair, so anything posting the old words must be updated.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_checklist_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_CHECKLISTS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 64 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketChecklistClosureMode()](/code/functions/ticketChecklistClosureMode.md), [ticketChecklistEmptyClosureMode()](/code/functions/ticketChecklistEmptyClosureMode.md)

