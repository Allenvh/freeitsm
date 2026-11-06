# includes/tenant_settings.php

> PHP - 293 lines

Per-company settings, falling back to the install-wide value.

⚠️ WHY THIS EXISTS. `system_settings` is a flat key/value table for the WHOLE
install, and `getTenantConfigRows()` handles per-company LOOKUP LISTS (statuses,
ticket types). Neither can express "this company bills for time and that one
does not" — so there was no way to answer a per-company yes/no question at all.

The shape is deliberately the only sensible one:

    tenant_settings   the answer for ONE company, when it has been given
         ↓ falls back to
    system_settings   the install-wide default
         ↓ falls back to
    the caller's default

🔑 A COMPANY WITH NO ROW IS NOT "OFF". It is "whatever the install says", which
is what makes the default meaningful and what keeps this invisible on a
single-company install: one toggle, no company column, nothing new to learn.

⚠️ Built for ONE setting and expected to serve many. "Per company, defaulting to
the install-wide value" is the shape most settings take once an install has more
than one company in it, so this is deliberately generic rather than a
time-tracking flag with a table around it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [tenantSetting()](/code/functions/tenantSetting.md) | 37 | The value of a setting for one company. |
| [tenantSettingForget()](/code/functions/tenantSettingForget.md) | 90 | Forget every cached setting - after a write, or between test cases. |
| [tenantSettingOn()](/code/functions/tenantSettingOn.md) | 102 | Convenience for a yes/no setting. Anything but '0' is on. |
| [setTenantSetting()](/code/functions/setTenantSetting.md) | 119 | Set (or clear) one company's answer. |
| [tenantSettingsForKey()](/code/functions/tenantSettingsForKey.md) | 142 | Every company's answer for one key, for rendering a settings table. |
| [timeTrackingUiOn()](/code/functions/timeTrackingUiOn.md) | 165 | Should the time-recording UI appear for a ticket belonging to this company? |
| [timeTrackingApiOn()](/code/functions/timeTrackingApiOn.md) | 171 | Should the REST API serve time entries for a ticket belonging to this company? |
| [ticketChecklistClosureMode()](/code/functions/ticketChecklistClosureMode.md) | 211 | 'warn' (default) or 'block' — how closure with outstanding mandatory steps behaves. |
| [ticketChecklistEmptyClosureMode()](/code/functions/ticketChecklistEmptyClosureMode.md) | 221 | 'off' (default), 'warn', or 'block' — when closing a ticket with no SOP attached. |
| [ticketCategoryOn()](/code/functions/ticketCategoryOn.md) | 247 | Should the "reported as" category field show on a ticket for this company? |
| [ticketClosureCategoryOn()](/code/functions/ticketClosureCategoryOn.md) | 253 | Should the "turned out to be" category field show when closing a ticket? |
| [ticketResolutionCodeOn()](/code/functions/ticketResolutionCodeOn.md) | 259 | Should the resolution code field show when closing a ticket? |
| [ticketClassificationSettings()](/code/functions/ticketClassificationSettings.md) | 272 | All three answers at once, for a page that is about to render a ticket. |
| [ticketTenantId()](/code/functions/ticketTenantId.md) | 282 | The company a ticket belongs to, or null. Used to resolve both of the above. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 | `tenantSetting` | read [tenant_settings](/code/tables/tenant_settings.md) |  |
| 63 | `tenantSetting` | read [system_settings](/code/tables/system_settings.md) |  |
| 123 | `setTenantSetting` | **delete** [tenant_settings](/code/tables/tenant_settings.md) |  |
| 128 | `setTenantSetting` | **insert** [tenant_settings](/code/tables/tenant_settings.md) (`tenant_id`, `setting_key`, `setting_value`) |  |
| 146 | `tenantSettingsForKey` | read [tenant_settings](/code/tables/tenant_settings.md) |  |
| 285 | `ticketTenantId` | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/assets/get_asset_label_settings.php](/code/files/api/assets/get_asset_label_settings.php.md), [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md), [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md), [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), [api/self-service/get_ticket_categories.php](/code/files/api/self-service/get_ticket_categories.php.md), [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md), [api/tickets/get_time_entries.php](/code/files/api/tickets/get_time_entries.php.md), [api/tickets/get_time_tracking_settings.php](/code/files/api/tickets/get_time_tracking_settings.php.md), [api/tickets/save_checklist_settings.php](/code/files/api/tickets/save_checklist_settings.php.md), [api/tickets/save_ticket_classification_settings.php](/code/files/api/tickets/save_ticket_classification_settings.php.md), [api/tickets/save_time_entry.php](/code/files/api/tickets/save_time_entry.php.md), [api/tickets/save_time_tracking_settings.php](/code/files/api/tickets/save_time_tracking_settings.php.md), [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), [asset-management/labels.php](/code/files/asset-management/labels.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md), [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md), [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)

**Calls:** [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyTablesReady()](/code/functions/tenancyTablesReady.md)

