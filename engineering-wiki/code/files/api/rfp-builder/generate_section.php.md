# api/rfp-builder/generate_section.php

> API endpoint - 295 lines

Run Pass 3 (AI section generation) for a single category as a Server-Sent Events stream. Same pattern as run_consolidation.php but per-category — the browser opens this endpoint once per category for "Generate all", or just once for a per-section regenerate.

Event protocol:
  event: phase    data: { phase, message }
  event: text     data: { delta }                  per-token chunk
  event: usage    data: { tokens_in, tokens_out, cache_read, cache_write }
  event: skipped  data: { reason }                 fired when hash matches and force=0
  event: complete data: { section_id, version, duration_ms, ... }
  event: error    data: { error }

Inputs:
  GET ?rfp_id=X&category_id=Y&force=0|1

Lock check: section generation requires every consolidated row in
the RFP to be is_locked = 1. Locking is the gate the analyst flips
once they're happy with consolidation; sections built off unlocked
rows would risk drift if the analyst edited consolidation
mid-generation.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/generate_section.php` |
| Protected by | Signed-in analyst (session check) (via includes/rfp_scope.php) |
| Reads | `GET rfp_id`, `GET category_id`, `GET force` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sse()](/code/functions/sse.md) | 55 | Sends one server-sent event (an event name and a JSON data line) and flushes it to the browser straight away. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 98 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 120 |  | read [rfp_output_sections](/code/tables/rfp_output_sections.md) |  |
| 139 |  | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 149 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 164 |  | read [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md), read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 180 |  | read [rfps](/code/tables/rfps.md) |  |
| 230 |  | read [rfp_output_sections](/code/tables/rfp_output_sections.md) |  |
| 237 |  | **insert** [rfp_section_history](/code/tables/rfp_section_history.md) (`section_id`, `version`, `section_content`, `is_manually_edited`) |  |
| 250 |  | **update** [rfp_output_sections](/code/tables/rfp_output_sections.md) (`section_content`, `version`, `is_manually_edited`, `requirements_hash`, `generated_datetime`, `edited_datetime`) |  |
| 263 |  | **insert** [rfp_output_sections](/code/tables/rfp_output_sections.md) (`rfp_id`, `category_id`, `section_title`, `section_content`, `version`, `requirements_hash`) |  |
| 271 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [csrfRequireToken()](/code/functions/csrfRequireToken.md), [rfpAiGenerateSectionStreaming()](/code/functions/rfpAiGenerateSectionStreaming.md), [rfpCanView()](/code/functions/rfpCanView.md)

