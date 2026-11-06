# api/rfp-builder/generate_framing.php

> API endpoint - 186 lines

Generate one document framing section (introduction / scope / response_instructions) as a Server-Sent Events stream — same pattern as generate_section.php but for the framing rows that sit above the per-category sections in the eventual document.

Event protocol matches generate_section.php exactly.

Inputs:
  GET ?rfp_id=X&section_key=introduction|scope|response_instructions&force=0|1

Lock check: same as section generation — requires every consolidated
requirement in the RFP to be is_locked = 1. Framing sections summarise
the categories, and category text comes from consolidation, so we don't
want consolidation drifting underneath us.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/generate_framing.php` |
| Protected by | Signed-in analyst (session check) (via includes/rfp_scope.php) |
| Reads | `GET rfp_id`, `GET section_key`, `GET force` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sse()](/code/functions/sse.md) | 47 | Sends one server-sent event (an event name and a JSON data line) and flushes it to the browser straight away. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 86 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 106 |  | read [rfp_document_sections](/code/tables/rfp_document_sections.md) |  |
| 147 |  | **update** [rfp_document_sections](/code/tables/rfp_document_sections.md) (`section_title`, `section_content`, `sort_order`, `is_manually_edited`, `inputs_hash`, `generated_datetime`, `edited_datetime`) |  |
| 161 |  | **insert** [rfp_document_sections](/code/tables/rfp_document_sections.md) (`rfp_id`, `section_key`, `section_title`, `section_content`, `sort_order`, `inputs_hash`) |  |
| 169 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [csrfRequireToken()](/code/functions/csrfRequireToken.md), [rfpAiGenerateFramingStreaming()](/code/functions/rfpAiGenerateFramingStreaming.md), [rfpCanView()](/code/functions/rfpCanView.md)

