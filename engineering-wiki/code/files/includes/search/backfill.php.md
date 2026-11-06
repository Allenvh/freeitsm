# includes/search/backfill.php

> PHP - 167 lines

Rebuild the search corpus from ticket content already in the database.

Reads `tickets.subject`, `emails.body_content`, `ticket_notes.note_text` and
`knowledge_articles.body` — all already text — and, since #53's tier 1, the
text of attachments it can read without help (`.txt`, `.docx`, `.xlsx` and
friends; see includes/search/extract.php).

⚠️ It does NOT re-open files it has read before. Extraction is cached in
`attachment_text`, so a rebuild of the search index costs no file reads at
all — which is the whole reason that table exists, and matters far more once
tier 2 is doing OCR on PDFs.

It is also not a one-off. The same code is the "rebuild the index" command a
grown installation needs after a schema change, after the full-text settings
are corrected, or simply when someone suspects the corpus has drifted.

SAFE TO RE-RUN. Every write is an upsert keyed on (source_type, source_id), so
running it twice updates in place rather than duplicating. It only ever writes
to `search_documents` — the source tables are read and never modified.

⚠️ COMMITS AS IT GOES, in batches. It cannot wrap the whole run in one
transaction: InnoDB does not expose uncommitted rows to MATCH...AGAINST, so
nothing indexed would be searchable until the very end, and a long run would
hold a huge transaction open for no benefit.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [searchBackfillRun()](/code/functions/searchBackfillRun.md) | 40 | Fills the search corpus from existing tickets, emails, notes, attachments and Knowledge articles, in resumable batches. |
| [searchBackfillPrune()](/code/functions/searchBackfillPrune.md) | 159 | Remove every corpus row whose ticket has since been deleted or trashed. The foreign key handles a hard DELETE; this covers the soft-delete case. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 66 | `searchBackfillRun` | read [tickets](/code/tables/tickets.md) |  |
| 70 | `searchBackfillRun` | read [tickets](/code/tables/tickets.md) |  |
| 105 | `searchBackfillRun` | read [tickets](/code/tables/tickets.md) |  |
| 126 | `searchBackfillRun` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 160 | `searchBackfillPrune` | **delete** [search_documents](/code/tables/search_documents.md), read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), [includes/search/indexer.php](/code/files/includes/search/indexer.php.md)

**Included by:** [api/system/search_rebuild.php](/code/files/api/system/search_rebuild.php.md)

**Calls:** [searchCorpusReady()](/code/functions/searchCorpusReady.md), [searchIndexArticle()](/code/functions/searchIndexArticle.md), [searchIndexTicket()](/code/functions/searchIndexTicket.md)

