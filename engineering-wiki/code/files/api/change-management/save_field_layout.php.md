# api/change-management/save_field_layout.php

> API endpoint - 213 lines

API: save_field_layout

Atomically replaces the configurable Change-form layout. Expects:
  {
    sections: [{ id?: int, name: string, display_order: int }],
    fields:   [{ key: string, section_id: int, display_order: int,
                 is_visible: bool }]
  }

Behaviour:
  - All operations run inside a single transaction.
  - Sections with `id` are updated; sections without `id` are inserted.
    Any section in the DB whose id is NOT present in the request is
    deleted (ON DELETE CASCADE drops its change_field_layout rows;
    callers MUST move those fields to a kept section first).
  - Fields are upserted on field_key (unique index uq_cfl_field_key).
    Fields not in the request are left alone (we don't expose a "delete
    a field" operation — the field catalogue is hardcoded in
    get_field_layout.php so the set of keys is bounded).
  - Returns the same shape as get_field_layout.php so the client can
    refresh its state in one round trip.

## The endpoint

| | |
|---|---|
| URL | `api/change-management/save_field_layout.php` |
| Protected by | Module access: changes (requireModuleAccessJson); Permission: Cap::CHANGES_FIELDS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 96 |  | **delete** [change_field_sections](/code/tables/change_field_sections.md) |  |
| 100 |  | **delete** [change_field_sections](/code/tables/change_field_sections.md) |  |
| 108 |  | **update** [change_field_sections](/code/tables/change_field_sections.md) (`name`, `display_order`) |  |
| 111 |  | **insert** [change_field_sections](/code/tables/change_field_sections.md) (`name`, `display_order`) |  |
| 139 |  | read [change_field_sections](/code/tables/change_field_sections.md) |  |
| 143 |  | **insert** [change_field_layout](/code/tables/change_field_layout.md) (`field_key`, `section_id`, `display_order`, `is_visible`) |  |
| 173 |  | read [change_field_sections](/code/tables/change_field_sections.md) |  |
| 182 |  | read [change_field_layout](/code/tables/change_field_layout.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [api/change-management/_field_catalogue.php](/code/files/api/change-management/_field_catalogue.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

