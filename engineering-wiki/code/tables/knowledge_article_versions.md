# knowledge_article_versions

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `article_id` | `INT NOT NULL` |  |
| `version` | `INT NOT NULL` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `body` | `LONGTEXT NULL` |  |
| `saved_by_id` | `INT NOT NULL` |  |
| `saved_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md):137 | `purgeExpired` | delete |  |  |
| [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md):164 | `apiKnowledgePurgeExpired` | delete |  |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md):231 | `KnowledgeService::saveArticle` | insert | `article_id`, `version`, `title`, `body`, `saved_by_id`, `saved_datetime` |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md):421 | `KnowledgeService::purgeArticle` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) | apiKnowledgeVersionsList L364, apiKnowledgeVersionsGet L386 |  |

