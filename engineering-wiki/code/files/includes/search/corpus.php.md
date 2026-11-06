# includes/search/corpus.php

> PHP - 159 lines

The search corpus — the ONE place anything writes to `search_documents`.

A corpus row is DERIVED. Every one of them is rebuildable from its source, so
losing the table costs a reindex and nothing else. That is deliberate: it is
what stops a future change of search engine from turning into a re-extraction
of every attachment ever received.

Two rules hold this together:

 1. NOTHING ELSE WRITES TO THE TABLE. Indexing is an upsert keyed on
    (source_type, source_id), so re-indexing the same source updates in place
    and can never duplicate it.

 2. `body` IS STRIPPED PLAINTEXT, never HTML. Indexing markup makes every
    ticket "contain" div, span and style, so a search for "table" matches the
    entire database.

⚠️ InnoDB does not expose rows written in an UNCOMMITTED transaction to
MATCH...AGAINST — its full-text cache is flushed at commit. Nothing here can
therefore write a row and then search for it in the same transaction.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [searchCorpusTicketScope()](/code/functions/searchCorpusTicketScope.md) | 51 | Turn an email/ticket-style tenant_id into the pair the corpus stores. Mirrors `ticketTenantFilter()`'s reading of NULL. |
| [searchCorpusArticleScope()](/code/functions/searchCorpusArticleScope.md) | 61 | Turn a knowledge-article tenant_id into the pair the corpus stores. NULL is the OPPOSITE of the ticket case — see [[project_knowledge_visibility]]. |
| [searchCorpusPlainText()](/code/functions/searchCorpusPlainText.md) | 75 | Reduce HTML to the plain text worth indexing. |
| [searchCorpusUpsert()](/code/functions/searchCorpusUpsert.md) | 97 | Index one source row, or update it if already indexed. |
| [searchCorpusDelete()](/code/functions/searchCorpusDelete.md) | 137 | Remove one source's row. Safe to call for something never indexed. |
| [searchCorpusDeleteTicket()](/code/functions/searchCorpusDeleteTicket.md) | 143 | Remove every row for a ticket. The FK cascades on ticket DELETE; this is for soft deletes. |
| [searchCorpusReady()](/code/functions/searchCorpusReady.md) | 148 | Is the corpus available? False on an install that has not run Database Verification. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 104 | `searchCorpusUpsert` | **insert** [search_documents](/code/tables/search_documents.md) (`source_type`, `source_id`, `ticket_id`, `tenant_id`, `tenant_scope`, `is_internal`, `title`, `body`, `source_datetime`, `indexed_datetime`) |  |
| 138 | `searchCorpusDelete` | **delete** [search_documents](/code/tables/search_documents.md) |  |
| 144 | `searchCorpusDeleteTicket` | **delete** [search_documents](/code/tables/search_documents.md) |  |
| 152 | `searchCorpusReady` | read [search_documents](/code/tables/search_documents.md) |  |

## How it hangs together

**Included by:** [api/system/search_status.php](/code/files/api/system/search_status.php.md), [includes/search/backfill.php](/code/files/includes/search/backfill.php.md), [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md), [includes/search/indexer.php](/code/files/includes/search/indexer.php.md), [includes/search/search.php](/code/files/includes/search/search.php.md)

