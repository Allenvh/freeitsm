# api/rfp-builder/restyle_framing.php

> API endpoint - 153 lines

Restyle one framing section (introduction / scope / response_instructions). Same pattern as restyle_section.php, but for the rfp_document_sections table. Framing has no version table, so we just overwrite — the analyst can re-generate from scratch if they don't like the restyle.

Inputs:
  GET ?rfp_id=X&section_key=introduction|scope|response_instructions

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/restyle_framing.php` |
| Protected by | Signed-in analyst (session check) (via includes/rfp_scope.php) |
| Reads | `GET rfp_id`, `GET section_key` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sse()](/code/functions/sse.md) | 41 | Sends one server-sent event (an event name and a JSON data line) and flushes it to the browser straight away. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 92 |  | read [rfp_document_sections](/code/tables/rfp_document_sections.md) |  |
| 127 |  | **update** [rfp_document_sections](/code/tables/rfp_document_sections.md) (`section_content`, `is_manually_edited`, `edited_datetime`, `generated_datetime`) |  |
| 136 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [csrfRequireToken()](/code/functions/csrfRequireToken.md), [rfpAiRestyleStreaming()](/code/functions/rfpAiRestyleStreaming.md), [rfpCanView()](/code/functions/rfpCanView.md)

