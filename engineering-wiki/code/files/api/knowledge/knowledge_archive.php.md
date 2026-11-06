# api/knowledge/knowledge_archive.php

> API endpoint - 159 lines

API Endpoint: Knowledge article recycle bin operations Actions: list, restore, hard_delete

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/knowledge_archive.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |
| Reads | `GET action` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [handleList()](/code/functions/handleList.md) | 57 | Lists the Knowledge recycle bin - archived articles the caller could see when they were live, most recently deleted first - with the retention period. |
| [handleRestore()](/code/functions/handleRestore.md) | 97 | Restores an archived article through KnowledgeService. |
| [handleHardDelete()](/code/functions/handleHardDelete.md) | 108 | Permanently deletes an archived article through KnowledgeService. |
| [purgeExpired()](/code/functions/purgeExpired.md) | 121 | Permanently deletes articles that have been in the recycle bin longer than the retention period, with their versions and tags, then removes tags no article uses any more. A retention of 0 keeps them forever. |
| [getRetentionDays()](/code/functions/getRetentionDays.md) | 152 | Returns how many days an article stays in the recycle bin before it is purged - the knowledge_recycle_bin_days setting, 30 when unset, 0 meaning forever. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 68 | `handleList` | read [knowledge_articles](/code/tables/knowledge_articles.md), read [analysts](/code/tables/analysts.md) |  |
| 128 | `purgeExpired` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 137 | `purgeExpired` | **delete** [knowledge_article_versions](/code/tables/knowledge_article_versions.md) |  |
| 138 | `purgeExpired` | **delete** [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 139 | `purgeExpired` | **delete** [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 142 | `purgeExpired` | **delete** [knowledge_tags](/code/tables/knowledge_tags.md), read [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 153 | `getRetentionDays` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

