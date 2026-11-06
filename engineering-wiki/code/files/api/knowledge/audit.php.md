# api/knowledge/audit.php

> API endpoint - 82 lines

API Endpoint: the history of one Knowledge folder or article.

─────────────────────────────────────────────────────────────────────────────
⚠️ THIS IS THE ONE SCREEN THAT SAYS WHO READ WHAT.

The audit table has been written since folders shipped and displayed nowhere,
which made it a promise rather than a feature: the design page said "every use
of the administrator floor is recorded", and nothing could show that it was.

Two gates, and both are needed for different reasons:

  1. `knowledgeCanRead()`  — you cannot ask for the history of an article you
     cannot read. Without this, the endpoint answers "here are 40 events" for
     a document whose existence is meant to be hidden, and the actions and
     timestamps describe it well enough to be worth having.

  2. the administrator floor — the history names PEOPLE and says when they
     read something. That is information about them, not only about the
     document, so it sits behind the same capability that governs the access
     list itself rather than being visible to anyone who can open the article.

The order matters: read first, floor second, so a person WITHOUT the floor is
told the same thing whether the article is restricted or merely not theirs to
audit. Answering "you may not audit this" only for articles that exist would
turn this endpoint into a way of testing whether an id is real.
─────────────────────────────────────────────────────────────────────────────

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/audit.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |
| Reads | `GET type`, `GET id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [knowledgeAuditHistory()](/code/functions/knowledgeAuditHistory.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [knowledgeViewerHasAdminFloor()](/code/functions/knowledgeViewerHasAdminFloor.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

