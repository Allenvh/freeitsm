# api/rfp-builder/restyle_section.php

> API endpoint - 187 lines

Restyle one category section: re-run the AI over the existing HTML to apply the style guide without changing the substance. Same streaming SSE protocol as generate_section.php so the document page can drop a restyle into the same batch UI.

Inputs:
  GET ?rfp_id=X&section_id=Y

Snapshots the current version into rfp_section_history before
overwriting, like a manual edit, so restyles are reversible. Marks
is_manually_edited = 0 since the AI produced the result, but the
version pill still bumps so the analyst can see a restyle happened.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/restyle_section.php` |
| Protected by | Signed-in analyst (session check) (via includes/rfp_scope.php) |
| Reads | `GET rfp_id`, `GET section_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sse()](/code/functions/sse.md) | 45 | Sends one server-sent event (an event name and a JSON data line) and flushes it to the browser straight away. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 83 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 97 |  | read [rfp_output_sections](/code/tables/rfp_output_sections.md), read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 137 |  | **insert** [rfp_section_history](/code/tables/rfp_section_history.md) (`section_id`, `version`, `section_content`, `is_manually_edited`) |  |
| 154 |  | **update** [rfp_output_sections](/code/tables/rfp_output_sections.md) (`section_content`, `version`, `is_manually_edited`, `edited_datetime`, `generated_datetime`) |  |
| 164 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [csrfRequireToken()](/code/functions/csrfRequireToken.md), [rfpAiRestyleStreaming()](/code/functions/rfpAiRestyleStreaming.md), [rfpCanView()](/code/functions/rfpCanView.md)

