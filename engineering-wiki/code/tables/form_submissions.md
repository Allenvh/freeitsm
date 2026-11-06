# form_submissions

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `form_id` | `INT NOT NULL` |  |
| `submitted_by` | `INT NULL` | The ANALYST submitter. Readers LEFT JOIN this to `analysts`, so a requester's id must never land here — separate id spaces. |
| `submitted_by_user_id` | `INT NULL` | The REQUESTER submitter (portal request catalogue). Exactly one of the two is set. |
| `ticket_id` | `INT NULL` | The ticket an analyst raised from this submission; NULL = not yet actioned, which is what the queue filters on. |
| `submitted_date` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `approval_status` | `VARCHAR(20) NOT NULL DEFAULT 'not_required'` | Catalogue-request approval (#928). approval_status: not_required (default, and every pre-#928 row) / pending / approved / rejected. approver_id is snapshotted from the form at submit time. FKs live in freeitsm.sql. |
| `approver_id` | `INT NULL` |  |
| `approval_decided_by_id` | `INT NULL` |  |
| `approval_decided_datetime` | `DATETIME NULL` |  |
| `approval_comment` | `TEXT NULL` |  |
| `collection_id` | `INT NULL` | Which collection this submission WAS part of, stamped at submit time from forms.collection_id. 🔑 The two are allowed to disagree: re-pairing a form must never rewrite what last year's responses belonged to. Same snapshot rule as approver_id above. |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3137 |  | delete |  |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md):122 | `catalogueApprovalDecide` | update | `approval_status`, `approval_decided_by_id`, `approval_decided_datetime`, `approval_comment`, `ticket_id` |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md):129 | `catalogueApprovalDecide` | update | `approval_status`, `approval_decided_by_id`, `approval_decided_datetime`, `approval_comment` |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md):194 | `catalogueApprovalDecide` | update | `ticket_id` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1135 | `FormsService::deleteForm` | delete |  |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1736 | `FormsService::submitForm` | insert | `$cols` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):1854 | `FormsService::deleteSubmission` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/forms/get_forms.php](/code/files/api/forms/get_forms.php.md) | L60 |  |
| [api/forms/get_submissions.php](/code/files/api/forms/get_submissions.php.md) | L75 |  |
| [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md) | L131 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3133 |  |
| [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) | apiFormSelect L61, apiLoadFormSubmission L225, apiFormSubmissionsSelect L347, apiFormSubmissionsList L367 |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) | catalogueApprovalDecide L77, catalogueRaiseTicketFromSubmissionId L373, catalogueApprovalsList L568, catalogueApprovalsList L604, catalogueApprovalsList L605, catalogueApprovalsList L606 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleForms L594 |  |
| [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) | projectProposalDetail L108, projectCreateFromAction L161 |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::listCollections L592, FormsService::collectionSubmissions L747, FormsService::deleteCollection L904, FormsService::deleteForm L1131, FormsService::deleteSubmission L1839, FormsService::deleteSubmission L1845 |  |

