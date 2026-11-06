# includes/lms/knowledge_source.php

> PHP - 68 lines

Which Knowledge articles the LMS may build a lesson from.

SHARED ONLY — articles with no owning company (knowledge_articles.tenant_id
IS NULL).

Why not "the author's own companies", which is how the rest of the app scopes
things: because a COURSE has no company. Nothing in the LMS does —
lms_courses, lms_lessons, lms_course_assignments, lms_learning_groups and
lms_progress all lack the column. So a lesson built from one client's article
is shown to every learner in every company regardless of who authored it.
Scoping the picker to the author's companies would close the article leak and
open a lesson leak — it looks safe without being safe.

Shared-only is the only rule coherent with an install-wide course: if everyone
can see the course, only content meant for everyone can feed it.

Costs nothing on installs where every article is shared (the migration
default) — it only bites once someone marks an article as one company's.

⚠️ If the LMS ever gains a company of its own (courses, lessons, assignments,
groups, progress), revisit this: the right rule becomes "the course's company
+ shared", and this file should go.

Written once and shared by BOTH the picker (api/lms/knowledge_articles.php)
and the body read (api/lms/ai_author.php). They must agree — a title the
picker hides must not be readable by posting its id to the author endpoint.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [lmsSharedOnlySql()](/code/functions/lmsSharedOnlySql.md) | 41 | SQL fragment restricting a knowledge_articles query to shared articles. |
| [lmsCanUseArticle()](/code/functions/lmsCanUseArticle.md) | 54 | True when the LMS may read `$articleId`'s body. Fails CLOSED: no row, wrong company, or an unexpected error => false. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 | `lmsCanUseArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md), [api/lms/knowledge_articles.php](/code/files/api/lms/knowledge_articles.php.md)

**Calls:** [tenancyColumnExists()](/code/functions/tenancyColumnExists.md)

