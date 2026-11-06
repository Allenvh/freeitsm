# includes/rfp_scope.php

> PHP - 145 lines

Which company an RFP belongs to, and who may see it (3.5.0).

rfps.tenant_id is SCOPED DATA, like contracts and assets: NULL is the Default
company, never "shared". An analyst sees an RFP only when they can reach its
company. Everything below an RFP - documents, extracted and consolidated
requirements, conflicts, sections and their history, framing, invited
suppliers, scores, the processing log - is reached THROUGH its RFP, so it
follows the RFP's company with no column of its own.

⚠️ EVERY RFP Builder endpoint calls `rfpGate()` straight after its module check,
naming the kind of id it was given. One gate, one place: an endpoint that
forgets it reads another company's tender documents, and nothing would look
wrong. The secondary ids an endpoint takes (a category, a requirement, a
section version) are already checked against the RFP by the endpoint itself.

The RFP's linked contract must be in the RFP's company (contracts belong to
companies too, see includes/contract_party.php). Suppliers and RFP departments
stay shared by every company, as they are for contracts and assets.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rfpTenantReady()](/code/functions/rfpTenantReady.md) | 26 | True once Database Verification has added rfps.tenant_id. |
| [rfpVisibilitySql()](/code/functions/rfpVisibilitySql.md) | 39 | " AND ..." keeping RFPs (alias `$r`) in companies this analyst can reach. |
| [rfpListSql()](/code/functions/rfpListSql.md) | 50 | The RFP LIST: the active company, or every company the analyst can see under "All companies". |
| [rfpTenantOf()](/code/functions/rfpTenantOf.md) | 61 | The company an RFP belongs to (NULL read as Default), or null if there is no such RFP. |
| [rfpCanView()](/code/functions/rfpCanView.md) | 75 | May this analyst see RFP `$id`? False for one that does not exist - the same answer, deliberately. |
| [rfpIdOf()](/code/functions/rfpIdOf.md) | 91 | The RFP a child row belongs to. `$kind` names the table the id is for; every one carries rfp_id except section history (through its section). |
| [rfpGate()](/code/functions/rfpGate.md) | 121 | The gate every RFP endpoint passes. Reads the id named `$key` from the query string, the form, or the JSON body; resolves it to its RFP; and answers "not found" - never forbidden, so ids cannot be probed - when the analyst cannot see that RFP. A missing id is left to the endpoint's own validation. With `$key` naming a LIST (merge's `ids`), every id must resolve to RFPs the analyst can see. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 | `rfpTenantReady` | read [rfps](/code/tables/rfps.md) |  |
| 63 | `rfpTenantOf` | read [rfps](/code/tables/rfps.md) |  |
| 63 | `rfpTenantOf` | read [rfps](/code/tables/rfps.md) |  |
| 79 | `rfpCanView` | read [rfps](/code/tables/rfps.md) |  |
| 94 | `rfpIdOf` | read [rfps](/code/tables/rfps.md) |  |
| 95 | `rfpIdOf` | read [rfp_documents](/code/tables/rfp_documents.md) |  |
| 96 | `rfpIdOf` | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) |  |
| 97 | `rfpIdOf` | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 98 | `rfpIdOf` | read [rfp_conflicts](/code/tables/rfp_conflicts.md) |  |
| 99 | `rfpIdOf` | read [rfp_output_sections](/code/tables/rfp_output_sections.md) |  |
| 100 | `rfpIdOf` | read [rfp_document_sections](/code/tables/rfp_document_sections.md) |  |
| 101 | `rfpIdOf` | read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [api/rfp-builder/add_consolidated.php](/code/files/api/rfp-builder/add_consolidated.php.md), [api/rfp-builder/create_prospective_supplier.php](/code/files/api/rfp-builder/create_prospective_supplier.php.md), [api/rfp-builder/delete_consolidated.php](/code/files/api/rfp-builder/delete_consolidated.php.md), [api/rfp-builder/delete_document.php](/code/files/api/rfp-builder/delete_document.php.md), [api/rfp-builder/delete_extracted.php](/code/files/api/rfp-builder/delete_extracted.php.md), [api/rfp-builder/delete_rfp.php](/code/files/api/rfp-builder/delete_rfp.php.md), [api/rfp-builder/extract_document.php](/code/files/api/rfp-builder/extract_document.php.md), [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md), [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md), [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md), [api/rfp-builder/get_available_suppliers.php](/code/files/api/rfp-builder/get_available_suppliers.php.md), [api/rfp-builder/get_compare_data.php](/code/files/api/rfp-builder/get_compare_data.php.md), [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md), [api/rfp-builder/get_coverage.php](/code/files/api/rfp-builder/get_coverage.php.md), [api/rfp-builder/get_documents.php](/code/files/api/rfp-builder/get_documents.php.md), [api/rfp-builder/get_document_text.php](/code/files/api/rfp-builder/get_document_text.php.md), [api/rfp-builder/get_extracted.php](/code/files/api/rfp-builder/get_extracted.php.md), [api/rfp-builder/get_invited_suppliers.php](/code/files/api/rfp-builder/get_invited_suppliers.php.md), [api/rfp-builder/get_processing_log.php](/code/files/api/rfp-builder/get_processing_log.php.md), [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md), [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md), [api/rfp-builder/get_scores.php](/code/files/api/rfp-builder/get_scores.php.md), [api/rfp-builder/get_sections.php](/code/files/api/rfp-builder/get_sections.php.md), [api/rfp-builder/get_section_history.php](/code/files/api/rfp-builder/get_section_history.php.md), [api/rfp-builder/invite_supplier.php](/code/files/api/rfp-builder/invite_supplier.php.md), [api/rfp-builder/lock_consolidation.php](/code/files/api/rfp-builder/lock_consolidation.php.md), [api/rfp-builder/merge_consolidated.php](/code/files/api/rfp-builder/merge_consolidated.php.md), [api/rfp-builder/remove_invited_supplier.php](/code/files/api/rfp-builder/remove_invited_supplier.php.md), [api/rfp-builder/resolve_conflict.php](/code/files/api/rfp-builder/resolve_conflict.php.md), [api/rfp-builder/restore_section_version.php](/code/files/api/rfp-builder/restore_section_version.php.md), [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md), [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md), [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md), [api/rfp-builder/save_extracted.php](/code/files/api/rfp-builder/save_extracted.php.md), [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md), [api/rfp-builder/save_score.php](/code/files/api/rfp-builder/save_score.php.md), [api/rfp-builder/split_consolidated.php](/code/files/api/rfp-builder/split_consolidated.php.md), [api/rfp-builder/update_consolidated.php](/code/files/api/rfp-builder/update_consolidated.php.md), [api/rfp-builder/update_framing.php](/code/files/api/rfp-builder/update_framing.php.md), [api/rfp-builder/update_framing_context.php](/code/files/api/rfp-builder/update_framing_context.php.md), [api/rfp-builder/update_invited_supplier.php](/code/files/api/rfp-builder/update_invited_supplier.php.md), [api/rfp-builder/update_section.php](/code/files/api/rfp-builder/update_section.php.md), [api/rfp-builder/upload_document.php](/code/files/api/rfp-builder/upload_document.php.md), [contracts/rfp-builder/preview.php](/code/files/contracts/rfp-builder/preview.php.md)

**Calls:** [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

