# includes/search/indexer.php

> PHP - 434 lines

Keep the search corpus current as tickets happen.

Until this existed, `scripts/search_backfill.php` was the ONLY thing that ever
wrote to the corpus, and somebody had to remember to run it. A ticket raised
this morning was invisible to search until then. That is worse than having no
search: people trust it, find nothing, and conclude the thing never happened.

HOW IT HOOKS IN
---------------
It subscribes to `WorkflowEngine::dispatch`, the same seam the notification
bell uses, rather than adding calls at every place a ticket changes. See
workflow/includes/engine.php — subscribers run in their own try/catch, before
the workflow loop, so none of them can break the others or the host request.

⚠️ WHOLE TICKETS, NOT SINGLE ROWS
Every event reindexes the ticket entire: its subject, all its messages, all
its notes. That looks wasteful and is deliberate.

 - It is ORDERING-IMMUNE. Some paths announce before the opening message is
   written, some after. Indexing "the row that just changed" would need every
   caller to fire at exactly the right moment; reindexing the ticket does not
   care when it is told.
 - It is SELF-HEALING. Any row a missed event would have left stale is
   rewritten by the next event on that ticket.
 - It is CHEAP. A ticket is a handful of rows, and every write is an upsert.

⚠️ It must be called AFTER the host's transaction commits. InnoDB does not
expose uncommitted rows to MATCH...AGAINST, and a rolled-back transaction
would otherwise leave corpus rows describing a ticket that never existed.

The document shapes live here and `searchBackfillRun()` calls them, so a
live-indexed ticket and a backfilled one are byte-for-byte the same. If those
two ever disagreed, a result would depend on HOW a ticket came to be indexed,
which is close to undebuggable.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [searchIndexTicket()](/code/functions/searchIndexTicket.md) | 54 | Index (or reindex) one ticket and everything on it. |
| [searchIndexTicketAttachments()](/code/functions/searchIndexTicketAttachments.md) | 155 | Index the readable text of a ticket's attachments (discussion #53, tier 1). |
| [searchIndexArticle()](/code/functions/searchIndexArticle.md) | 335 | Index (or reindex) one knowledge article. |
| [searchIndexHandleEvent()](/code/functions/searchIndexHandleEvent.md) | 394 | The dispatch subscriber. Called for EVERY workflow event, so it decides quickly whether it cares and gets out of the way. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `searchIndexTicket` | read [tickets](/code/tables/tickets.md) |  |
| 92 | `searchIndexTicket` | read [emails](/code/tables/emails.md) |  |
| 114 | `searchIndexTicket` | read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 164 | `searchIndexTicketAttachments` | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md), read [attachment_text](/code/tables/attachment_text.md) |  |
| 272 | `searchIndexTicketAttachments` | **insert** [attachment_text](/code/tables/attachment_text.md) (`attachment_id`, `status`, `extractor`, `extracted_text`, `chars`, `extracted_datetime`) |  |
| 346 | `searchIndexArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), [includes/search/extract.php](/code/files/includes/search/extract.php.md), [includes/search/tika.php](/code/files/includes/search/tika.php.md)

**Included by:** [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [includes/search/backfill.php](/code/files/includes/search/backfill.php.md), [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [attTextExtractFile()](/code/functions/attTextExtractFile.md), [attTextSupports()](/code/functions/attTextSupports.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [searchCorpusArticleScope()](/code/functions/searchCorpusArticleScope.md), [searchCorpusDelete()](/code/functions/searchCorpusDelete.md), [searchCorpusDeleteTicket()](/code/functions/searchCorpusDeleteTicket.md), [searchCorpusPlainText()](/code/functions/searchCorpusPlainText.md), [searchCorpusReady()](/code/functions/searchCorpusReady.md), [searchCorpusTicketScope()](/code/functions/searchCorpusTicketScope.md), [searchCorpusUpsert()](/code/functions/searchCorpusUpsert.md), [tikaConfigured()](/code/functions/tikaConfigured.md), [tikaExtract()](/code/functions/tikaExtract.md), [tikaHandles()](/code/functions/tikaHandles.md)

