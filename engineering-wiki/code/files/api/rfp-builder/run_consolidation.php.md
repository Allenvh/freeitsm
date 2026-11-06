# api/rfp-builder/run_consolidation.php

> API endpoint - 245 lines

Run Pass 2 (AI consolidation) as a Server-Sent Events stream so the browser can show live output (claude.ai-style) instead of waiting 60-180s on a spinner. Same DB shape as before — categories, consolidated rows, M:N source links, conflicts — but the AI call is streamed and progress is forwarded to the browser.

Event protocol emitted to the browser:
  event: phase   data: { phase, message }
  event: text    data: { delta }              per-token chunk
  event: usage   data: { tokens_in, tokens_out, cache_read, cache_write }
  event: complete data: { counts, tokens_*, duration_ms, ... }
  event: error   data: { error }

The DB transaction only runs after the stream finishes successfully —
if anything fails mid-stream, no data is written.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/run_consolidation.php` |
| Protected by | Signed-in analyst (session check) (via includes/rfp_scope.php) |
| Reads | `GET rfp_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sse()](/code/functions/sse.md) | 48 | Send one SSE event to the browser. Always flushes. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 90 |  | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 132 |  | **delete** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 134 |  | **delete** [rfp_categories](/code/tables/rfp_categories.md) |  |
| 138 |  | **insert** [rfp_categories](/code/tables/rfp_categories.md) (`rfp_id`, `name`, `description`, `sort_order`) |  |
| 152 |  | **insert** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) (`rfp_id`, `category_id`, `requirement_text`, `requirement_type`, `priority`, `ai_rationale`) |  |
| 157 |  | **insert** [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md) (`consolidated_id`, `extracted_id`) |  |
| 193 |  | **insert** [rfp_conflicts](/code/tables/rfp_conflicts.md) (`rfp_id`, `consolidated_id_a`, `consolidated_id_b`, `ai_explanation`, `resolution`) |  |
| 210 |  | **update** [rfps](/code/tables/rfps.md) (`status`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [csrfRequireToken()](/code/functions/csrfRequireToken.md), [rfpAiConsolidateStreaming()](/code/functions/rfpAiConsolidateStreaming.md), [rfpCanView()](/code/functions/rfpCanView.md)

