# api/lms/ai_author.php

> API endpoint - 189 lines

LMS API: the three AI authoring helpers.

mode=outline  — a course outline (lesson titles + a line on each) from a topic
  mode=lesson   — a lesson body, either from a knowledge article or from a title
  mode=quiz     — questions with an answer key, from a lesson's own text

They share one endpoint because they share everything except the prompt: the
same config, the same JSON contract, the same error handling. Each returns a
DRAFT — nothing is written to the database here. The author reviews it in the
editor and presses Save, which goes through the normal validated endpoints, so
the AI cannot create a question with no correct answer or a course nobody
looked at.

## The endpoint

| | |
|---|---|
| URL | `api/lms/ai_author.php` |
| Protected by | Permission: Cap::LMS_MANAGE (requireCapabilityJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [lmsPlainText()](/code/functions/lmsPlainText.md) | 38 | HTML (a knowledge article, a lesson body) down to the plain text the model should read. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 92 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 139 |  | read [lms_lessons](/code/tables/lms_lessons.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), [includes/lms/knowledge_source.php](/code/files/includes/lms/knowledge_source.php.md), [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsCanUseArticle()](/code/functions/lmsCanUseArticle.md), [parseClaudeJson()](/code/functions/parseClaudeJson.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

