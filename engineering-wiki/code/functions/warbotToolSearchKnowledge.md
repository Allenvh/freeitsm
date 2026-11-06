# warbotToolSearchKnowledge()

search_knowledge: up to eight live Knowledge article titles matching the query that the asking analyst may see - titles only, most viewed first.

```php
function warbotToolSearchKnowledge(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 955.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments - untrusted: query (words to find in an article title) |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 978 | `warbotToolSearchKnowledge` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

*Nothing calls this function by name.*

