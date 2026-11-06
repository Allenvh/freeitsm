# form_drafts

A form somebody started and did not finish.
       🔴 A SEPARATE TABLE, deliberately, not a status on form_submissions —
       that table is read by the submissions list, the collection view, the
       counts, the exports, the approval inbox, the workflow triggers and the
       REST API, and a draft appearing in any of them is a half-filled record
       presented as a real one. Here it is invisible to all of them.
       ⚠️ Pinned to the exact form VERSION: `createVersion()` renumbers every
       field, so answers keyed by old ids would attach to the wrong questions.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `form_id` | `INT NOT NULL` |  |
| `owner_kind` | `VARCHAR(10) NOT NULL` | 'analyst' \| 'portal'. The kind is stored, not inferred: `analysts` and `users` are separate id spaces. One pair rather than two nullable ids because a UNIQUE key over nullable columns constrains nothing in MySQL — NULLs compare as distinct and duplicates slip through. |
| `owner_id` | `INT NOT NULL` |  |
| `answers` | `LONGTEXT NULL` |  |
| `created_date` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `modified_date` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):2334 | `FormsService::saveDraft` | insert | `form_id`, `owner_kind`, `owner_id`, `answers`, `created_date`, `modified_date` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):2400 | `FormsService::deleteDraft` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::loadDraft L2365, FormsService::draftsForOwner L2415 |  |

