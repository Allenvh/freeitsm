# api/v1/resources/forms.php

> API endpoint - 410 lines

FreeITSM REST API v1 — forms resource (forms, fields, versions, submissions).

Mirrors the module's internal endpoints:
  - GET /forms is get_forms.php: ONE row per version chain (the leaf — the
    current editable version), with field + submission counts;
  - PATCH replicates save_form.php's field sync EXACTLY, and refuses to touch
    a frozen historical version (409, like the server-side leaf check).
    ⚠️ That sync is now BY ID, not positional. Send each field's `id` back (as
    GET returns it) and it is updated in place. A field with no `id` is treated
    as new, and any field you omit is RETIRED — soft-deleted, keeping the
    answers people already gave it. A client that rebuilds the field list from
    scratch without ids therefore replaces the questions rather than renaming
    them; that is deliberate, and the opposite of the old behaviour, which
    quietly re-pointed historical answers at whichever question had landed in
    that position;
  - POST /forms/{id}/versions is create_version.php: fork the leaf into a
    new row (parent_form_id chain, version_number + 1), fields cloned;
  - POST /forms/{id}/submissions is submit_form.php: per-type required +
    format validation, then the form.submitted workflow dispatch with the
    label-keyed answers map (the "new starter form → tickets" use case) —
    fired after commit and swallowed on error, exactly like the UI.

Deliberate differences from the UI, documented:
  - unknown field ids in a submission are a 422 (the UI inserts them
    blindly and hits the FK);
  - a field with an empty label is a 422 on create/update (the UI
    silently drops it — machines deserve the error), and field_type must
    be one of the module's eight types (the UI stores anything);
  - PATCH can set is_active — NOTHING in the UI writes it today (the
    column only carries across version clones), so the API is the only
    way to retire a form without deleting it;
  - DELETE refuses a non-leaf version (409) unless ?chain=true, which
    removes the WHOLE chain + all its submissions transactionally —
    deleting just the leaf resurfaces the previous version in the list.

Field types: text, textarea, email, number, checkbox (single yes/no),
checkboxes (multi — stored as a JSON array string), dropdown, radio.
`options` is a JSON array for dropdown / radio / checkboxes.

Install-wide (no company scoping — matches the UI); no audit trail exists.
The module's AI form-generation endpoints stay UI-only.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/forms.php` |
| Protected by | REST API key |
| Reads | `GET is_active`, `GET q`, `GET chain` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiFormSelect()](/code/functions/apiFormSelect.md) | 60 | The SELECT for forms, with creator and editor names and counts of child versions, fields and submissions - child_count 0 marks the current version. |
| [apiSerializeFormField()](/code/functions/apiSerializeFormField.md) | 76 | Shapes a form field row for the API - type, label, decoded options, required flag, order and its conditional-visibility config. |
| [apiSerializeForm()](/code/functions/apiSerializeForm.md) | 108 | Shapes a form row for the API - title, version (is_current marks the editable one), counts and people, plus its live fields unless `$withFields` is false. |
| [apiLoadForm()](/code/functions/apiLoadForm.md) | 142 | Loads one form row (apiFormSelect), or answers 404 and stops. |
| [apiSerializeFormSubmission()](/code/functions/apiSerializeFormSubmission.md) | 159 | Shapes a submission for the API with every answer beside its field - checkbox answers decoded to lists, and a table's answer decoded to rows with its column definitions (retired columns included and marked). |
| [apiLoadFormSubmission()](/code/functions/apiLoadFormSubmission.md) | 223 | Loads one submission that belongs to the given form, or answers 404 and stops. |
| [apiFormsList()](/code/functions/apiFormsList.md) | 244 | GET /forms — one row per chain (the current version), like the module list. |
| [apiFormsGet()](/code/functions/apiFormsGet.md) | 265 | GET /forms/{id} - one form with its fields. |
| [apiFormsCreate()](/code/functions/apiFormsCreate.md) | 270 | POST /forms — mirrors save_form.php's create branch (version_number 1). |
| [apiFormsUpdate()](/code/functions/apiFormsUpdate.md) | 278 | PATCH /forms/{id} — save_form.php's in-place save (id-based field sync). |
| [apiFormsDelete()](/code/functions/apiFormsDelete.md) | 287 | DELETE /forms/{id} — one version (leaf only), or the whole chain with ?chain=true. Always transactional, submissions + data removed explicitly. |
| [apiFormVersionsList()](/code/functions/apiFormVersionsList.md) | 300 | GET /forms/{id}/versions — the whole chain, oldest first (list_versions.php). |
| [apiFormVersionsCreate()](/code/functions/apiFormVersionsCreate.md) | 334 | POST /forms/{id}/versions — fork the leaf (create_version.php). |
| [apiFormSubmissionsSelect()](/code/functions/apiFormSubmissionsSelect.md) | 346 | The SELECT for form submissions, with the form's title and the submitter's name. |
| [apiFormSubmissionsList()](/code/functions/apiFormSubmissionsList.md) | 354 | GET /forms/{id}/submissions — newest first, paginated. |
| [apiFormSubmissionsGet()](/code/functions/apiFormSubmissionsGet.md) | 388 | GET /forms/{id}/submissions/{submissionId} - one submission with its answers. |
| [apiFormSubmissionsCreate()](/code/functions/apiFormSubmissionsCreate.md) | 394 | POST /forms/{id}/submissions — submit_form.php: per-type validation, then the form.submitted workflow dispatch (after commit, errors swallowed). |
| [apiFormSubmissionsDelete()](/code/functions/apiFormSubmissionsDelete.md) | 404 | DELETE /forms/{id}/submissions/{sid} — delete_submission.php, but with the data rows removed explicitly (grown installs may lack the cascade FK). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 | `apiFormSelect` | read [forms](/code/tables/forms.md), read [form_fields](/code/tables/form_fields.md), read [form_submissions](/code/tables/form_submissions.md), read [analysts](/code/tables/analysts.md) |  |
| 130 | `apiSerializeForm` | read [form_fields](/code/tables/form_fields.md) |  |
| 163 | `apiSerializeFormSubmission` | read [form_submission_data](/code/tables/form_submission_data.md), read [form_fields](/code/tables/form_fields.md) |  |
| 225 | `apiLoadFormSubmission` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md), read [analysts](/code/tables/analysts.md) |  |
| 245 | `apiFormsList` | read [forms](/code/tables/forms.md) |  |
| 306 | `apiFormVersionsList` | read [forms](/code/tables/forms.md) |  |
| 317 | `apiFormVersionsList` | read [forms](/code/tables/forms.md) |  |
| 347 | `apiFormSubmissionsSelect` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md), read [analysts](/code/tables/analysts.md) |  |
| 367 | `apiFormSubmissionsList` | read [form_submissions](/code/tables/form_submissions.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md)

