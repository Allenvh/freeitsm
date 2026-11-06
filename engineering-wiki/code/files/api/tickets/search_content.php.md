# api/tickets/search_content.php

> API endpoint - 122 lines

API Endpoint: search INSIDE tickets — message bodies, notes and subjects.

The sibling of search_tickets.php, which matches only a ticket number, an
address or a subject. This one asks the search corpus, so it finds a phrase
buried in the fourth reply of a two-year-old ticket.

⚠️ IT DOES NOT DECIDE PERMISSIONS ITSELF. It builds a scope from the analyst's
session and hands it to the one search function, which applies it INSIDE the
query. Filtering results here would starve them — the index returns its top N
by relevance, and discarding afterwards hands back a near-empty page while
plenty the analyst was entitled to never made the cut.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/search_content.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 85 |  | read [emails](/code/tables/emails.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/search/search.php](/code/files/includes/search/search.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [searchCorpusQuery()](/code/functions/searchCorpusQuery.md), [searchMinTokenSize()](/code/functions/searchMinTokenSize.md), [searchScopeForAnalyst()](/code/functions/searchScopeForAnalyst.md)

