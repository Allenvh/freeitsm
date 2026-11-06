# api/system/search_status.php

> API endpoint - 146 lines

API: what the search index currently holds (discussion #53).

Powers System → Search. Everything here was already visible in
D007 — Search corpus health, but D007 is a diagnostic tool: it lives under
Debug Tools, prints a wall of environment detail, and is not somewhere an
administrator would think to look to answer "is search working?".

Read-only. The rebuild is a separate endpoint so that this one can be polled.

## The endpoint

| | |
|---|---|
| URL | `api/system/search_status.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | read [search_documents](/code/tables/search_documents.md) |  |
| 61 |  | read [search_documents](/code/tables/search_documents.md) |  |
| 63 |  | read [search_documents](/code/tables/search_documents.md) |  |
| 68 |  | read [tickets](/code/tables/tickets.md) |  |
| 75 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 91 |  | read [attachment_text](/code/tables/attachment_text.md) |  |
| 102 |  | read [attachment_text](/code/tables/attachment_text.md), read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md), read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), [includes/search/extract.php](/code/files/includes/search/extract.php.md), [includes/search/search.php](/code/files/includes/search/search.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [extractQueueDrainOpportunistic()](/code/functions/extractQueueDrainOpportunistic.md), [searchCorpusReady()](/code/functions/searchCorpusReady.md), [searchMinTokenSize()](/code/functions/searchMinTokenSize.md)

