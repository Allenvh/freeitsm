# includes/knowledge/audit.php

> PHP - 162 lines

The Knowledge history — the ONE place a row is written.

Four files had grown their own copy of the same INSERT before this existed
(folders.php, permissions.php, permission_model.php, visibility.php), which is
the same duplication the visibility clause suffered from: nothing breaks when
the copies drift, one of them just quietly starts recording something slightly
different — and an audit trail that disagrees with itself is worse than none,
because it is trusted.

─────────────────────────────────────────────────────────────────────────────
BEST EFFORT, ALWAYS
An unwritable log must never fail the thing being logged. Refusing to save an
article because its history row would not write is a worse outcome than a gap
in the history — but the gap is logged to error_log, because a silently
unwritable audit table is the one failure nobody would ever notice.

─────────────────────────────────────────────────────────────────────────────
⚠️ VIEWS ARE A DIFFERENT VOLUME CLASS FROM EVERYTHING ELSE
knowledge_articles.view_count already counts every read. A row per view on a
busy knowledge base is millions a year, and the rows people actually come
looking for — who changed the permissions, who deleted it — would be buried
under them within a month.

So a view is recorded ONCE PER PERSON PER ARTICLE PER DAY. That keeps the
question worth asking ("who has read this?") answerable, while a hundred
re-reads by the same analyst in an afternoon stay one row. The total count is
still exact, because it comes from view_count and never from here.
─────────────────────────────────────────────────────────────────────────────

## Functions

| Function | Line | What it does |
|---|--:|---|
| [knowledgeAuditLog()](/code/functions/knowledgeAuditLog.md) | 49 | Record one thing that happened. |
| [knowledgeAuditView()](/code/functions/knowledgeAuditView.md) | 87 | Record that somebody read an article — at most once per person per day. |
| [knowledgeAuditHistory()](/code/functions/knowledgeAuditHistory.md) | 118 | The history of one object, newest first, with the names filled in. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 | `knowledgeAuditLog` | **insert** [knowledge_audit](/code/tables/knowledge_audit.md) (`object_type`, `object_id`, `action`, `analyst_id`, `user_id`, `detail`, `ip_address`) |  |
| 92 | `knowledgeAuditView` | read [knowledge_audit](/code/tables/knowledge_audit.md) |  |
| 122 | `knowledgeAuditHistory` | read [knowledge_audit](/code/tables/knowledge_audit.md) |  |
| 137 | `knowledgeAuditHistory` | read [analysts](/code/tables/analysts.md) |  |
| 140 | `knowledgeAuditHistory` | read [users](/code/tables/users.md) |  |

## How it hangs together

**Included by:** [api/documents/help_centre.php](/code/files/api/documents/help_centre.php.md), [api/knowledge/audit.php](/code/files/api/knowledge/audit.php.md), [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md)

