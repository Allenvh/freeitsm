# form_fields

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `form_id` | `INT NOT NULL` |  |
| `field_type` | `VARCHAR(50) NOT NULL` | 'section' is a presentational item, not a question: it renders as a heading, never produces submission data, and owns the fields below it until the next 'section'. Kept in this table so one flat sort_order still describes the whole form. |
| `label` | `VARCHAR(255) NOT NULL` |  |
| `options` | `LONGTEXT NULL` |  |
| `is_required` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `config` | `LONGTEXT NULL` | Per-field JSON. Today: the conditional-visibility rule, whose `field` key is a form_fields.id. NULL = always visible = every pre-existing row, so an upgraded form renders exactly as it did. |
| `is_deleted` | `TINYINT(1) NOT NULL DEFAULT 0` | Soft delete — form_submission_data.field_id points at this row, so a hard delete destroyed past respondents' answers. See freeitsm.sql. |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3130 |  | delete |  |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1136 | `FormsService::deleteForm` | delete |  |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1344 | `FormsService::createVersion` | insert | `form_id`, `field_type`, `label`, `options`, `is_required`, `sort_order`, `config` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1353 | `FormsService::createVersion` | update | `config` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):2965 | `FormsService::syncFields` | update | `field_type`, `label`, `options`, `is_required`, `sort_order`, `is_deleted` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):2970 | `FormsService::syncFields` | insert | `form_id`, `field_type`, `label`, `options`, `is_required`, `sort_order` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):2992 | `FormsService::syncFields` | update | `is_deleted` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):2995 | `FormsService::syncFields` | update | `is_deleted` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):2999 | `FormsService::syncFields` | update | `config` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/forms/get_form.php](/code/files/api/forms/get_form.php.md) | L73 |  |
| [api/forms/get_forms.php](/code/files/api/forms/get_forms.php.md) | L60 |  |
| [api/forms/get_submissions.php](/code/files/api/forms/get_submissions.php.md) | L62 |  |
| [api/forms/image.php](/code/files/api/forms/image.php.md) | L53 |  |
| [api/forms/lookup_search.php](/code/files/api/forms/lookup_search.php.md) | L47 |  |
| [api/self-service/get_catalogue_form.php](/code/files/api/self-service/get_catalogue_form.php.md) | L60 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3134 |  |
| [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) | apiFormSelect L61, apiSerializeForm L130, apiSerializeFormSubmission L163 |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) | catalogueSubmissionAnswerMap L502, catalogueSubmissionBodyHtml L531, catalogueSubmissionAnswers L619 |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::collectionSubmissions L788, FormsService::createVersion L1335, FormsService::submitForm L1465, FormsService::saveDraft L2307, FormsService::syncFields L2959 |  |

