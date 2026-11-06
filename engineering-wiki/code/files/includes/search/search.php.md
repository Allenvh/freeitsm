# includes/search/search.php

> PHP - 382 lines

THE search function. Nothing else in FreeITSM writes a search query.

That is the whole point of this file. If `MATCH ... AGAINST` appears in six
endpoints then changing how search works — or changing what answers it — is a
rewrite. Behind one function it is a second implementation of one function.
See the Full-Text-Search wiki page for the reasoning.

TWO RULES CALLERS MUST NOT BREAK

 1. THE PERMISSION PREDICATE GOES INTO THE QUERY, NEVER OVER THE RESULTS.
    Filtering afterwards starves results: the index returns its top N by
    relevance, you discard what the caller may not see, and hand back three
    rows while hundreds they were entitled to never made the top N. It fails
    worst for the least privileged user, who is also the least likely to be
    the one testing it.

 2. THE SCOPE IS A DATA STRUCTURE, NOT SQL. A SQL fragment would weld this to
    MySQL. FreeITSM computes the predicate; the backend merely applies it.
    Replicating POLICY into a second system is dangerous; passing a COMPUTED
    FILTER to a dumb store is ordinary.

WHAT IT WILL AND WILL NOT MATCH. Word order is irrelevant and phrases work,
but MySQL has no stemmer and no fuzzy matching: "printers" will not match
"printer" and a typo finds nothing. `searchParseQuery()` adds a trailing
wildcard to soften the first of those. See §4.6 of the wiki page.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [searchMinTokenSize()](/code/functions/searchMinTokenSize.md) | 39 | The shortest word this server will index. Read from the server rather than assumed, because it is not the same everywhere — WAMP ships 0, stock MySQL is 3 — and requiring a term below it in boolean mode returns NOTHING AT ALL rather than ignoring it. That is the difference between "search works" and "search mysteriously returns nothing for that phrase". |
| [searchParseQuery()](/code/functions/searchParseQuery.md) | 65 | Turn what a person typed into a MySQL boolean-mode query. |
| [searchScopeForAnalyst()](/code/functions/searchScopeForAnalyst.md) | 134 | Build the scope structure for an analyst. |
| [searchScopeToSql()](/code/functions/searchScopeToSql.md) | 192 | Translate the scope structure into SQL. The ONLY place this happens. |
| [searchCorpusQuery()](/code/functions/searchCorpusQuery.md) | 255 | Search the corpus. |
| [searchSnippet()](/code/functions/searchSnippet.md) | 366 | A readable fragment of the body around the first matching term. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 279 | `searchCorpusQuery` | read [tickets](/code/tables/tickets.md) |  |
| 286 | `searchCorpusQuery` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 294 | `searchCorpusQuery` | read [search_documents](/code/tables/search_documents.md) |  |
| 301 | `searchCorpusQuery` | read [search_documents](/code/tables/search_documents.md) |  |
| 316 | `searchCorpusQuery` | read [search_documents](/code/tables/search_documents.md) |  |

## How it hangs together

**Includes:** [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md)

**Included by:** [api/system/global_search.php](/code/files/api/system/global_search.php.md), [api/system/search_status.php](/code/files/api/system/search_status.php.md), [api/tickets/search_content.php](/code/files/api/tickets/search_content.php.md)

**Calls:** [documentSearchVisibilityClause()](/code/functions/documentSearchVisibilityClause.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [searchCorpusReady()](/code/functions/searchCorpusReady.md)

