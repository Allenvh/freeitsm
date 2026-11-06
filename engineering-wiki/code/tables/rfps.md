# rfps

RFP Builder (feature of the Contracts module)

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NULL` | The company that owns the RFP (3.5.0) - scoped data, NULL = Default. Everything below an RFP (documents, requirements, sections, scores) follows its RFP. See includes/rfp_scope.php. |
| `name` | `VARCHAR(200) NOT NULL` |  |
| `status` | `VARCHAR(50) NOT NULL DEFAULT 'draft'` |  |
| `contract_id` | `INT NULL` |  |
| `chosen_supplier_id` | `INT NULL` |  |
| `style_guide` | `LONGTEXT NULL` |  |
| `framing_context_text` | `LONGTEXT NULL` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/add_consolidated.php](/code/files/api/rfp-builder/add_consolidated.php.md):95 |  | update | `updated_datetime` |  |
| [api/rfp-builder/create_prospective_supplier.php](/code/files/api/rfp-builder/create_prospective_supplier.php.md):82 |  | update | `updated_datetime` |  |
| [api/rfp-builder/delete_consolidated.php](/code/files/api/rfp-builder/delete_consolidated.php.md):40 |  | update | `updated_datetime` |  |
| [api/rfp-builder/delete_document.php](/code/files/api/rfp-builder/delete_document.php.md):47 |  | update | `updated_datetime` |  |
| [api/rfp-builder/delete_extracted.php](/code/files/api/rfp-builder/delete_extracted.php.md):38 |  | update | `updated_datetime` |  |
| [api/rfp-builder/delete_rfp.php](/code/files/api/rfp-builder/delete_rfp.php.md):46 |  | delete |  |  |
| [api/rfp-builder/extract_document.php](/code/files/api/rfp-builder/extract_document.php.md):61 |  | update | `updated_datetime` |  |
| [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md):103 |  | update | `updated_datetime` |  |
| [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md):169 |  | update | `updated_datetime` |  |
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md):271 |  | update | `updated_datetime` |  |
| [api/rfp-builder/invite_supplier.php](/code/files/api/rfp-builder/invite_supplier.php.md):57 |  | update | `updated_datetime` |  |
| [api/rfp-builder/lock_consolidation.php](/code/files/api/rfp-builder/lock_consolidation.php.md):65 |  | update | `status`, `updated_datetime` |  |
| [api/rfp-builder/lock_consolidation.php](/code/files/api/rfp-builder/lock_consolidation.php.md):72 |  | update | `status`, `updated_datetime` |  |
| [api/rfp-builder/merge_consolidated.php](/code/files/api/rfp-builder/merge_consolidated.php.md):115 |  | update | `updated_datetime` |  |
| [api/rfp-builder/remove_invited_supplier.php](/code/files/api/rfp-builder/remove_invited_supplier.php.md):56 |  | update | `updated_datetime` |  |
| [api/rfp-builder/resolve_conflict.php](/code/files/api/rfp-builder/resolve_conflict.php.md):73 |  | update | `updated_datetime` |  |
| [api/rfp-builder/restore_section_version.php](/code/files/api/rfp-builder/restore_section_version.php.md):87 |  | update | `updated_datetime` |  |
| [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md):136 |  | update | `updated_datetime` |  |
| [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md):164 |  | update | `updated_datetime` |  |
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md):210 |  | update | `status`, `updated_datetime` |  |
| [api/rfp-builder/save_extracted.php](/code/files/api/rfp-builder/save_extracted.php.md):58 |  | update | `updated_datetime` |  |
| [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md):88 |  | update | `name`, `status`, `style_guide`, `contract_id`, `chosen_supplier_id`, `updated_datetime` |  |
| [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md):99 |  | update | `tenant_id` |  |
| [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md):101 |  | insert | `name`, `status`, `style_guide`, `contract_id`, `chosen_supplier_id`, `created_by_analyst_id` |  |
| [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md):110 |  | update | `tenant_id` |  |
| [api/rfp-builder/save_score.php](/code/files/api/rfp-builder/save_score.php.md):77 |  | update | `updated_datetime` |  |
| [api/rfp-builder/split_consolidated.php](/code/files/api/rfp-builder/split_consolidated.php.md):134 |  | update | `updated_datetime` |  |
| [api/rfp-builder/update_consolidated.php](/code/files/api/rfp-builder/update_consolidated.php.md):69 |  | update | `updated_datetime` |  |
| [api/rfp-builder/update_framing.php](/code/files/api/rfp-builder/update_framing.php.md):49 |  | update | `updated_datetime` |  |
| [api/rfp-builder/update_framing_context.php](/code/files/api/rfp-builder/update_framing_context.php.md):40 |  | update | `framing_context_text`, `updated_datetime` |  |
| [api/rfp-builder/update_invited_supplier.php](/code/files/api/rfp-builder/update_invited_supplier.php.md):45 |  | update | `updated_datetime` |  |
| [api/rfp-builder/update_section.php](/code/files/api/rfp-builder/update_section.php.md):77 |  | update | `updated_datetime` |  |
| [api/rfp-builder/upload_document.php](/code/files/api/rfp-builder/upload_document.php.md):105 |  | update | `updated_datetime` |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md):247 | `ContractsService::deleteSupplier` | update | `chosen_supplier_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/add_consolidated.php](/code/files/api/rfp-builder/add_consolidated.php.md) | L52 |  |
| [api/rfp-builder/create_prospective_supplier.php](/code/files/api/rfp-builder/create_prospective_supplier.php.md) | L47 |  |
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md) | L180 |  |
| [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md) | L38 |  |
| [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md) | L35 |  |
| [api/rfp-builder/get_sections.php](/code/files/api/rfp-builder/get_sections.php.md) | L97 |  |
| [api/rfp-builder/invite_supplier.php](/code/files/api/rfp-builder/invite_supplier.php.md) | L39 |  |
| [api/rfp-builder/lock_consolidation.php](/code/files/api/rfp-builder/lock_consolidation.php.md) | L40 |  |
| [api/rfp-builder/update_framing_context.php](/code/files/api/rfp-builder/update_framing_context.php.md) | L35 |  |
| [api/rfp-builder/upload_document.php](/code/files/api/rfp-builder/upload_document.php.md) | L65 |  |
| [contracts/rfp-builder/preview.php](/code/files/contracts/rfp-builder/preview.php.md) | L42 |  |
| [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) | rfpAiResolveStyleGuide L42, rfpAiGenerateFramingStreaming L1247 |  |
| [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) | rfpTenantReady L30, rfpTenantOf L63, rfpCanView L79, rfpIdOf L94 |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) | ContractsService::assertMovable L349 |  |

