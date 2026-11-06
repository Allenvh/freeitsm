# documents

A document is either a file we hold or a link to somewhere else (an external DMS). It carries NO permissions of its own — see includes/documents.php: visibility is inherited from whatever it is attached to.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `kind` | `VARCHAR(8) NOT NULL DEFAULT 'file'` | file \| link |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `description` | `TEXT NULL` | For a LINK this is all search will ever have to go on — there is no document to extract text from, only a URL. Hence not "optional". |
| `storage_key` | `VARCHAR(255) NULL` | Opaque key, never a path: the files can move without a data migration. |
| `original_name` | `VARCHAR(255) NULL` |  |
| `mime_type` | `VARCHAR(100) NULL` |  |
| `size_bytes` | `BIGINT NULL` |  |
| `content_hash` | `CHAR(64) NULL` | Lets the same warranty PDF attached to eleven laptops be stored once. |
| `external_url` | `VARCHAR(2048) NULL` |  |
| `tenant_id` | `INT NULL` |  |
| `uploaded_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `deleted_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/documents/save.php](/code/files/api/documents/save.php.md):125 |  | insert | `kind`, `title`, `description`, `storage_key`, `original_name`, `mime_type`, `size_bytes`, `content_hash`, `external_url`, `tenant_id` |  |
| [api/documents/unlink.php](/code/files/api/documents/unlink.php.md):77 |  | update | `deleted_datetime` |  |
| [includes/documents.php](/code/files/includes/documents.php.md):700 | `documentsCollectOrphans` | update | `deleted_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/documents/download.php](/code/files/api/documents/download.php.md) | L55 |  |
| [api/documents/find.php](/code/files/api/documents/find.php.md) | L60 |  |
| [api/documents/help_centre.php](/code/files/api/documents/help_centre.php.md) | L64 |  |
| [api/documents/links.php](/code/files/api/documents/links.php.md) | L49 |  |
| [api/documents/list.php](/code/files/api/documents/list.php.md) | L58, L72, L127 |  |
| [api/documents/unlink.php](/code/files/api/documents/unlink.php.md) | L73, L92 |  |
| [api/self-service/get_document.php](/code/files/api/self-service/get_document.php.md) | L64 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L222 |  |
| [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md) | L507, L951, L993 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L468, L677 |  |
| [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md) | L98 |  |
| [includes/documents.php](/code/files/includes/documents.php.md) | documentsCollectOrphans L690, documentsCollectOrphans L711 |  |
| [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md) | helpCentreArticleDocuments L59, helpCentreDocumentCounts L90, helpCentreDocumentForPortalUser L119 |  |
| [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) | projectGateDocuments L45 |  |
| [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) | searchIndexDocument L112, documentTextDrain L186, documentSearchVisibilityClause L293 |  |

