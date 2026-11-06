# api/change-management/get_field_layout.php

> API endpoint - 97 lines

API: get_field_layout

Returns the configurable layout for the Change form:
  - sections: [{ id, name, display_order }] sorted by display_order
  - fields:   [{ key, label, section_id, display_order, is_visible }]
              sorted by (section_id, display_order)

`fields` is the intersection of the FIELD_CATALOGUE constant below (the
hardcoded list of supported field keys with their human labels) and the
rows in `change_field_layout`. The catalogue is the source of truth for
"what fields exist on the change form" — the DB only stores placement
(section + order) and visibility.

Used by:
  - change-management/settings/ — Form fields tab (admin reorder/toggle)
  - change-management/index.php  — renders the change editor form

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get_field_layout.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [change_field_sections](/code/tables/change_field_sections.md) |  |
| 47 |  | read [change_field_layout](/code/tables/change_field_layout.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [api/change-management/_field_catalogue.php](/code/files/api/change-management/_field_catalogue.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

