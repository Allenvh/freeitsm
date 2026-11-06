# api/lms/knowledge_articles.php

> API endpoint - 37 lines

LMS API: the knowledge articles an author can turn into a lesson.

A deliberately thin list (id + title only) rather than reusing the knowledge
module's own endpoint, because that one is gated on the 'knowledge' module and
an LMS author may not have it. Bodies are never returned here — ai_author.php
reads the body server-side when it actually needs it.

SHARED ARTICLES ONLY — see includes/lms/knowledge_source.php for why.

## The endpoint

| | |
|---|---|
| URL | `api/lms/knowledge_articles.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/lms/knowledge_source.php](/code/files/includes/lms/knowledge_source.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsSharedOnlySql()](/code/functions/lmsSharedOnlySql.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

