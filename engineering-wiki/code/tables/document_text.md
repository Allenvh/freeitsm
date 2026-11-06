# document_text

Extracted text for an attached document (discussion #76).  Its own table rather than a source_type column on attachment_text: that table's primary key is the attachment id ALONE, and a document id is a different small integer from a different table, so the two would collide. What the pipelines genuinely share — `attTextExtractFile()` — is a function taking a path, and both use it.  ⚠️ PK is document_id, so it is registered in `$primaryKeys` in db_verify.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `document_id` | `INT NOT NULL` |  |
| `status` | `VARCHAR(16) NOT NULL` |  |
| `extractor` | `VARCHAR(20) NULL` |  |
| `extracted_text` | `LONGTEXT NULL` |  |
| `chars` | `INT NOT NULL DEFAULT 0` |  |
| `extracted_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md):74 | `documentTextEnqueue` | insert | `document_id`, `status`, `extracted_datetime` |  |
| [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md):181 | `documentTextDrain` | update | `status` |  |
| [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md):206 | `documentTextDrain` | update | `status`, `extracted_datetime` |  |
| [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md):250 | `documentTextDrain` | update | `status`, `extractor`, `extracted_text`, `chars`, `extracted_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/documents/links.php](/code/files/api/documents/links.php.md) | L49 |  |
| [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) | documentTextQueueDepth L89, searchIndexDocument L112, documentTextDrain L186 |  |

