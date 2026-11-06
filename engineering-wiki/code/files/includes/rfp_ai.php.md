# includes/rfp_ai.php

> PHP - 1464 lines

RFP Builder AI helper.

One module to call out to either Anthropic or OpenAI for the
extraction / consolidation / generation prompts the RFP Builder
uses. Reads provider, model, encrypted API key and SSL-verify
preference from system_settings; logs every call to
rfp_processing_log; retries on 429 / 5xx / network errors with
exponential backoff.

Public surface (others can grow as more passes land):
  rfpAiExtractRequirements(PDO, rfpId, documentId, rawText, departmentName)
  rfpAiConsolidate(PDO, rfpId, extractedRows)
  rfpAiGenerateSectionStreaming(PDO, rfpId, categoryId, eventCallback)
  rfpAiGenerateFramingStreaming(PDO, rfpId, sectionKey, eventCallback)
  rfpAiRestyleStreaming(PDO, rfpId, existingHtml, logContext, eventCallback)

Lower-level helpers (rfpAiCall, rfpAiGetSettings) are reusable for
the consolidation / generation passes coming in Phases 3 and 4.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rfpAiResolveStyleGuide()](/code/functions/rfpAiResolveStyleGuide.md) | 40 | Resolve the style guide for an RFP. Order of precedence: 1. The RFP's own `rfps.style_guide` override (per-RFP) 2. The system-wide `system_settings.rfp_default_style_guide` 3. A safe hardcoded fallback |
| [rfpAiGetSettings()](/code/functions/rfpAiGetSettings.md) | 64 | Load the configured provider, model, decrypted API key and SSL-verify flag. Throws if any required piece is missing — callers surface that as a user-friendly "configure under Contracts → Settings → RFP AI" message. |
| [rfpAiCall()](/code/functions/rfpAiCall.md) | 125 | Generic provider-agnostic call. |
| [rfpAiCallAnthropic()](/code/functions/rfpAiCallAnthropic.md) | 153 | Sends one prompt to the Anthropic Messages API and returns the reply text with token and cache counts. |
| [rfpAiCallOpenAI()](/code/functions/rfpAiCallOpenAI.md) | 205 | Sends one prompt to the OpenAI chat completions API and returns the reply text with token counts. |
| [rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md) | 266 | Streaming Anthropic call. Receives token deltas as they arrive and forwards them to a caller-supplied callback so the browser can show live output (claude.ai-style) instead of waiting for the whole response. Single attempt, no retry — once we've started streaming to the browser we can't replay from the start. |
| [rfpAiHttpPostWithRetry()](/code/functions/rfpAiHttpPostWithRetry.md) | 427 | POST with retry on 429 / 5xx / network errors. Exponential backoff starting at RFP_AI_RETRY_BACKOFF_MS. Throws on final failure. |
| [rfpAiLogAction()](/code/functions/rfpAiLogAction.md) | 493 | Insert a row into rfp_processing_log. Silently no-ops if rfp_id is null (the column is NOT NULL in the schema; callers should always have an rfp_id by the time they're calling AI). |
| [rfpAiExtractRequirements()](/code/functions/rfpAiExtractRequirements.md) | 559 | Run Pass 1 extraction on a single document and return parsed items. Callers are responsible for inserting the items into rfp_extracted_requirements; this function only calls the AI and logs the call. |
| [rfpAiConsolidate()](/code/functions/rfpAiConsolidate.md) | 749 | Run Pass 2 (consolidate + categorise + detect conflicts) for an RFP. |
| [rfpAiConsolidateStreaming()](/code/functions/rfpAiConsolidateStreaming.md) | 852 | Streaming variant of Pass 2 consolidation. Behaves like `rfpAiConsolidate()` but forwards token deltas + usage updates to a caller-supplied callback so the browser can render live output. |
| [rfpAiGenerateSectionStreaming()](/code/functions/rfpAiGenerateSectionStreaming.md) | 1009 | Run Pass 3 (generate one section, streaming) for a single category. |
| [rfpAiGenerateFramingStreaming()](/code/functions/rfpAiGenerateFramingStreaming.md) | 1240 | Streaming generator for one framing section. |
| [rfpAiRestyleStreaming()](/code/functions/rfpAiRestyleStreaming.md) | 1404 | Streaming restyle pass over an existing piece of section HTML. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 | `rfpAiResolveStyleGuide` | read [rfps](/code/tables/rfps.md) |  |
| 49 | `rfpAiResolveStyleGuide` | read [system_settings](/code/tables/system_settings.md) |  |
| 67 | `rfpAiGetSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 506 | `rfpAiLogAction` | **insert** [rfp_processing_log](/code/tables/rfp_processing_log.md) (`rfp_id`, `document_id`, `section_id`, `action`, `status`, `details`, `tokens_in`, `tokens_out`) |  |
| 1012 | `rfpAiGenerateSectionStreaming` | read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 1021 | `rfpAiGenerateSectionStreaming` | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 1037 | `rfpAiGenerateSectionStreaming` | read [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md), read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 1247 | `rfpAiGenerateFramingStreaming` | read [rfps](/code/tables/rfps.md) |  |
| 1256 | `rfpAiGenerateFramingStreaming` | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md), read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 1272 | `rfpAiGenerateFramingStreaming` | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md)

**Included by:** [api/forms/ai_generate.php](/code/files/api/forms/ai_generate.php.md), [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md), [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md), [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md), [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md), [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md), [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md), [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md), [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md), [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

