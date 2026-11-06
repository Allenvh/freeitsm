# rfpGate()

The gate every RFP endpoint passes. Reads the id named `$key` from the query string, the form, or the JSON body; resolves it to its RFP; and answers "not found" - never forbidden, so ids cannot be probed - when the analyst cannot see that RFP. A missing id is left to the endpoint's own validation. With `$key` naming a LIST (merge's `ids`), every id must resolve to RFPs the analyst can see.

```php
function rfpGate(string $kind, string $key): void
```

Defined in [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md), line 121.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$kind` | `string` |  | which table the request's id is for, as `rfpIdOf()` takes it ('rfp' for an RFP id itself) |
| `$key` | `string` |  | the request field holding the id (or a list of ids), read from the query string, the form or the JSON body |

**Returns:** `void`

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [rfpCanView()](/code/functions/rfpCanView.md), [rfpIdOf()](/code/functions/rfpIdOf.md), [rfpTenantReady()](/code/functions/rfpTenantReady.md)

## Called from

- [api/rfp-builder/add_consolidated.php](/code/files/api/rfp-builder/add_consolidated.php.md)
- [api/rfp-builder/create_prospective_supplier.php](/code/files/api/rfp-builder/create_prospective_supplier.php.md)
- [api/rfp-builder/delete_consolidated.php](/code/files/api/rfp-builder/delete_consolidated.php.md)
- [api/rfp-builder/delete_document.php](/code/files/api/rfp-builder/delete_document.php.md)
- [api/rfp-builder/delete_extracted.php](/code/files/api/rfp-builder/delete_extracted.php.md)
- [api/rfp-builder/delete_rfp.php](/code/files/api/rfp-builder/delete_rfp.php.md)
- [api/rfp-builder/extract_document.php](/code/files/api/rfp-builder/extract_document.php.md)
- [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md)
- [api/rfp-builder/get_available_suppliers.php](/code/files/api/rfp-builder/get_available_suppliers.php.md)
- [api/rfp-builder/get_compare_data.php](/code/files/api/rfp-builder/get_compare_data.php.md)
- [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md)
- [api/rfp-builder/get_coverage.php](/code/files/api/rfp-builder/get_coverage.php.md)
- [api/rfp-builder/get_document_text.php](/code/files/api/rfp-builder/get_document_text.php.md)
- [api/rfp-builder/get_documents.php](/code/files/api/rfp-builder/get_documents.php.md)
- [api/rfp-builder/get_extracted.php](/code/files/api/rfp-builder/get_extracted.php.md)
- [api/rfp-builder/get_invited_suppliers.php](/code/files/api/rfp-builder/get_invited_suppliers.php.md)
- [api/rfp-builder/get_processing_log.php](/code/files/api/rfp-builder/get_processing_log.php.md)
- [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md)
- [api/rfp-builder/get_scores.php](/code/files/api/rfp-builder/get_scores.php.md)
- [api/rfp-builder/get_section_history.php](/code/files/api/rfp-builder/get_section_history.php.md)
- [api/rfp-builder/get_sections.php](/code/files/api/rfp-builder/get_sections.php.md)
- [api/rfp-builder/invite_supplier.php](/code/files/api/rfp-builder/invite_supplier.php.md)
- [api/rfp-builder/lock_consolidation.php](/code/files/api/rfp-builder/lock_consolidation.php.md)
- [api/rfp-builder/merge_consolidated.php](/code/files/api/rfp-builder/merge_consolidated.php.md)
- [api/rfp-builder/remove_invited_supplier.php](/code/files/api/rfp-builder/remove_invited_supplier.php.md)
- [api/rfp-builder/resolve_conflict.php](/code/files/api/rfp-builder/resolve_conflict.php.md)
- [api/rfp-builder/restore_section_version.php](/code/files/api/rfp-builder/restore_section_version.php.md)
- [api/rfp-builder/save_extracted.php](/code/files/api/rfp-builder/save_extracted.php.md)
- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [api/rfp-builder/save_score.php](/code/files/api/rfp-builder/save_score.php.md)
- [api/rfp-builder/split_consolidated.php](/code/files/api/rfp-builder/split_consolidated.php.md)
- [api/rfp-builder/update_consolidated.php](/code/files/api/rfp-builder/update_consolidated.php.md)
- [api/rfp-builder/update_framing.php](/code/files/api/rfp-builder/update_framing.php.md)
- [api/rfp-builder/update_framing_context.php](/code/files/api/rfp-builder/update_framing_context.php.md)
- [api/rfp-builder/update_invited_supplier.php](/code/files/api/rfp-builder/update_invited_supplier.php.md)
- [api/rfp-builder/update_section.php](/code/files/api/rfp-builder/update_section.php.md)
- [api/rfp-builder/upload_document.php](/code/files/api/rfp-builder/upload_document.php.md)

