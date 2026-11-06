# forms

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `description` | `LONGTEXT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_by` | `INT NULL` |  |
| `created_date` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `modified_by` | `INT NULL` |  |
| `modified_date` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `parent_form_id` | `INT NULL` | Versioning (#442): a form's history is a chain of rows, each pointing back at its predecessor via parent_form_id. The leaf (no children) is the current editable version; older rows are frozen snapshots. version_number is the position in the chain — set on create / clone, NEVER incremented by an in-place save (regular Save just updates modified_by/date). |
| `version_number` | `INT NOT NULL DEFAULT 1` |  |
| `is_portal_visible` | `TINYINT(1) NOT NULL DEFAULT 0` | Offer this form in the self-service portal's request catalogue. Separate from is_active (the analyst-side on/off) and defaulting to 0, so upgrading never exposes an existing internal form to customers. |
| `requires_approval` | `TINYINT(1) NOT NULL DEFAULT 0` | Catalogue-request approval (#928): gate a portal submission behind a designated approver before a ticket is raised. FK on approver_id lives in freeitsm.sql. requires_approval on + approver_id NULL = unconfigured. |
| `approver_id` | `INT NULL` |  |
| `collection_id` | `INT NULL` | What happens when this form is submitted, approved or rejected (discussion #95). JSON: {"submitted":[…],"approved":[…],"rejected":[…]}, each a list of {type, args} in the same shape the workflow engine's actions use — because they ARE workflow actions, run by the same handlers. JSON-in-TEXT for the same reason workflows.actions is: the schema must not need a migration every time the engine grows a new action kind.  🔑 NULL is meaningful and is NOT the same as an empty list. NULL means "never configured", and the pre-#95 behaviour applies — nothing on submit, and an approval raises a ticket the hard-coded way. That is what makes this upgrade safe WITHOUT a data migration: every existing form keeps doing exactly what it did. An empty list means somebody opened the panel and deliberately chose nothing. Which collection NEW submissions of this form are stamped into. NULL = none, and that is the normal case. ⚠️ Carried forward by `createVersion()`: the catalogue lists leaves, so a new version that dropped this would silently unpair the form the moment somebody pressed Save - exactly how approval gating was lost before #95. |
| `layout` | `LONGTEXT NULL` | WHERE this form's questions go, as opposed to what they are. JSON: {"type":"flow\|grid","rows":[{"cells":[{"field":<id>,"width":1-12,"rowspan":n}]}]}.  🔑 NULL is meaningful and is the normal case. It means "never laid out", and the layout is then DERIVED from sort_order plus each field's width — which is exactly what the form already draws. That is what makes this migration-free: every existing form stores NULL and renders unchanged.  🔑 'flow' and 'grid' are the same format. A grid cell may span rows and may hold no question; a flow layout is one where every rowspan is 1. That is deliberate — it is what lets a cell designer be added later as a UI rather than as a rewrite. See docs/design/form-designer.md.  ⚠️ Carried forward by `createVersion()`, like collection_id and the action lists above it. A per-form setting left out of that INSERT is a setting that pressing Save deletes. |
| `submission_actions` | `TEXT NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3139 |  | update | `parent_form_id` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):686 | `FormsService::setCollectionForms` | update | `collection_id`, `modified_by`, `modified_date` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):690 | `FormsService::setCollectionForms` | update | `collection_id`, `modified_by`, `modified_date` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):700 | `FormsService::setCollectionForms` | update | `collection_id`, `modified_by`, `modified_date` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1005 | `FormsService::saveForm` | update | `title`, `description`, `is_active`, `is_portal_visible`, `requires_approval`, `approver_id`, `submission_actions`, `modified_by` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1058 | `FormsService::saveForm` | insert | `title`, `description`, `is_active`, `is_portal_visible`, `requires_approval`, `approver_id`, `submission_actions`, `created_by`, `modified_by`, `version_number` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1139 | `FormsService::deleteForm` | delete |  |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1264 | `FormsService::createVersion` | insert | `title`, `description`, `is_active`, `is_portal_visible`, `requires_approval`, `approver_id`, `submission_actions`, `created_by`, `modified_by`, `parent_form_id` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1374 | `FormsService::createVersion` | update | `layout` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/forms/get_form.php](/code/files/api/forms/get_form.php.md) | L27, L87 |  |
| [api/forms/get_forms.php](/code/files/api/forms/get_forms.php.md) | L62, L66 |  |
| [api/forms/get_submissions.php](/code/files/api/forms/get_submissions.php.md) | L32 |  |
| [api/forms/image.php](/code/files/api/forms/image.php.md) | L53, L63 |  |
| [api/forms/list_versions.php](/code/files/api/forms/list_versions.php.md) | L41, L57, L68 |  |
| [api/forms/lookup_search.php](/code/files/api/forms/lookup_search.php.md) | L47 |  |
| [api/forms/upload_image.php](/code/files/api/forms/upload_image.php.md) | L57 |  |
| [api/self-service/draft.php](/code/files/api/self-service/draft.php.md) | L55 |  |
| [api/self-service/get_catalogue.php](/code/files/api/self-service/get_catalogue.php.md) | L55 |  |
| [api/self-service/get_catalogue_form.php](/code/files/api/self-service/get_catalogue_form.php.md) | L42, L73 |  |
| [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md) | L131 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3130, L3137, L3139 |  |
| [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) | apiFormSelect L61, apiLoadFormSubmission L225, apiFormsList L245, apiFormVersionsList L306, apiFormVersionsList L317, apiFormSubmissionsSelect L347 |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) | catalogueApprovalDecide L77, catalogueRaiseTicketFromSubmissionId L373, catalogueApprovalsList L568 |  |
| [includes/feature_bingo/cards/forms.php](/code/files/includes/feature_bingo/cards/forms.php.md) | L170 |  |
| [includes/feature_bingo/cards/self-service.php](/code/files/includes/feature_bingo/cards/self-service.php.md) | L332 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleForms L594 |  |
| [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) | projectProposalDetail L108, projectCreateFromAction L161 |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::listCollections L592, FormsService::collectionForms L615, FormsService::setCollectionForms L662, FormsService::formsForPicker L721, FormsService::collectionSubmissions L747, FormsService::submissionsBlockedBy L932 ... |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | L1023 |  |

