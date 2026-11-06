# search_documents

Search corpus — one row per searchable unit (ticket subject, email body, note, and later attachment text). Fully derived: every row is rebuildable from its source, so losing this table costs a reindex and nothing else. The full-text indexes are NOT here — indexes come from the generated includes/db_verify_indexes.php, which understands 'fulltext' as of #991. See database/freeitsm.sql for why tenant_scope exists rather than a nullable tenant_id alone: NULL means "the default company" on a ticket and "every company" on a knowledge article.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `BIGINT NOT NULL AUTO_INCREMENT` |  |
| `source_type` | `VARCHAR(32) NOT NULL` |  |
| `source_id` | `INT NOT NULL` |  |
| `ticket_id` | `INT NULL` |  |
| `tenant_id` | `INT NULL` |  |
| `tenant_scope` | `VARCHAR(16) NOT NULL DEFAULT 'company'` |  |
| `is_internal` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `title` | `VARCHAR(500) NULL` |  |
| `body` | `MEDIUMTEXT NULL` |  |
| `source_datetime` | `DATETIME NULL` |  |
| `indexed_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/search/backfill.php](/code/files/includes/search/backfill.php.md):160 | `searchBackfillPrune` | delete |  |  |
| [includes/search/corpus.php](/code/files/includes/search/corpus.php.md):104 | `searchCorpusUpsert` | insert | `source_type`, `source_id`, `ticket_id`, `tenant_id`, `tenant_scope`, `is_internal`, `title`, `body`, `source_datetime`, `indexed_datetime` |  |
| [includes/search/corpus.php](/code/files/includes/search/corpus.php.md):138 | `searchCorpusDelete` | delete |  |  |
| [includes/search/corpus.php](/code/files/includes/search/corpus.php.md):144 | `searchCorpusDeleteTicket` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/search_status.php](/code/files/api/system/search_status.php.md) | L46, L61, L63 |  |
| [includes/search/corpus.php](/code/files/includes/search/corpus.php.md) | searchCorpusReady L152 |  |
| [includes/search/search.php](/code/files/includes/search/search.php.md) | searchCorpusQuery L294, searchCorpusQuery L301, searchCorpusQuery L316 |  |

