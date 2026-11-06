# document_links

What each document is attached to. A ROW, deliberately, not a column on `documents` — that is what lets one document belong to several things later without a migration or a rewrite of every query that touches it.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `document_id` | `INT NOT NULL` |  |
| `parent_type` | `VARCHAR(32) NOT NULL` | `documentEntityTypes()` |
| `parent_id` | `INT NOT NULL` |  |
| `linked_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `show_in_help_centre` | `TINYINT(1) NOT NULL DEFAULT 0` | Knowledge articles only (GH #177): may portal users download this document from the Help Centre? On the LINK, not the document, so the same file can be shown on one article and held back on another. TRAP: keep the column DEFAULT 0. Adding it gives every EXISTING link 0, so nothing attached before 3.7.0 goes public on upgrade; new article links are written with 1 by `documentLinkHelpCentreDefault()`. |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/documents/unlink.php](/code/files/api/documents/unlink.php.md):61 |  | delete |  |  |
| [includes/documents.php](/code/files/includes/documents.php.md):626 | `documentsDetachParent` | delete |  |  |
| [includes/documents.php](/code/files/includes/documents.php.md):670 | `documentsCollectOrphans` | delete |  |  |
| [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md):152 | `helpCentreSetShown` | update | `show_in_help_centre` |  |
| [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md):798 | `TaskRecurrence::createOccurrence` | insert | `document_id`, `parent_type`, `parent_id`, `linked_by_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/documents/find.php](/code/files/api/documents/find.php.md) | L60 |  |
| [api/documents/links.php](/code/files/api/documents/links.php.md) | L68 |  |
| [api/documents/list.php](/code/files/api/documents/list.php.md) | L58, L72, L87, L127 |  |
| [api/documents/unlink.php](/code/files/api/documents/unlink.php.md) | L65 |  |
| [api/self-service/get_document.php](/code/files/api/self-service/get_document.php.md) | L64 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L222 |  |
| [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md) | L507, L951, L993 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L482, L685 |  |
| [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md) | L98 |  |
| [includes/documents.php](/code/files/includes/documents.php.md) | documentVisibilityClause L443, documentCanView L489, documentVisibleParents L562, documentsCollectOrphans L690, documentHelpCentreReady L778 |  |
| [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md) | helpCentreArticleDocuments L59, helpCentreDocumentCounts L90, helpCentreDocumentForPortalUser L119, helpCentreSetShown L148 |  |
| [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) | projectGateDocuments L45 |  |
| [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md) | TaskRecurrence::createOccurrence L798 |  |

