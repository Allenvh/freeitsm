# api/tickets/save_mandatory_fields_settings.php

> API endpoint - 102 lines

API Endpoint: save the mandatory-fields-at-closure settings.

POST {
  fields: ["category", "resolution_code", ...],   // keys of MandatoryFieldsService::FIELDS
  mode:   "warn" | "notify" | "block",
  notify: "sdm@example.com, lead@example.com",     // required when mode = notify
  record: true | false
}

Install-wide for now. Read through `tenantSetting()`, so a per-company column can
be added to the tab later without touching the reading side.

Unknown field keys and invalid addresses are REFUSED rather than dropped: a
list that quietly saved as something other than what was typed is how an
administrator ends up believing a rule is in force that is not.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_mandatory_fields_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MANDATORY_FIELDS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 83 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

