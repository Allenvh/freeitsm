# api/cmdb/generate_object_summary.php

> API endpoint - 252 lines

API: Generate (or regenerate) the AI summary for a CMDB object.

Pulls the object's full context — properties (resolved values), parent,
children, relationships (both directions), and impact (things that depend
on it) — packs it into a structured user message, asks Claude for a 2-3
sentence prose synthesis, and saves the result on the object so we don't
re-call the AI on every page load.

The frontend hits this when the analyst clicks "Regenerate". The cached
summary is returned by get_object.php for free on every page render.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/generate_object_summary.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Called from | [cmdb/object.js](/code/files/cmdb/object.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 65 |  | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 96 |  | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 105 |  | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 118 |  | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 131 |  | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 183 |  | read [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 196 |  | read [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 240 |  | **update** [cmdb_objects](/code/tables/cmdb_objects.md) (`ai_summary`, `ai_summary_generated_at`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md)

**Calls:** [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [anthropicResponseText()](/code/functions/anthropicResponseText.md), [callAnthropic()](/code/functions/callAnthropic.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadCmdbAiConfig()](/code/functions/loadCmdbAiConfig.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

