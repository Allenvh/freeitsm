# form_submission_data

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `submission_id` | `INT NOT NULL` |  |
| `field_id` | `INT NOT NULL` |  |
| `field_value` | `LONGTEXT NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3133 |  | delete |  |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3134 |  | delete |  |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1131 | `FormsService::deleteForm` | delete |  |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1739 | `FormsService::submitForm` | insert | `submission_id`, `field_id`, `field_value` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1853 | `FormsService::deleteSubmission` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/forms/get_submissions.php](/code/files/api/forms/get_submissions.php.md) | L103 |  |
| [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) | apiSerializeFormSubmission L163 |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) | catalogueSubmissionAnswerMap L502, catalogueSubmissionBodyHtml L531, catalogueSubmissionAnswers L619 |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::collectionSubmissions L770 |  |

