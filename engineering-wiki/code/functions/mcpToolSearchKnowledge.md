# mcpToolSearchKnowledge()

KNOWLEDGE - by the KEY's companies (KnowledgeViewer::forApiKey), not the analyst's active company, which an API call does not have.

```php
function mcpToolSearchKnowledge(PDO $conn, array $args, int $analystId, array $apiKey): string
```

Defined in [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), line 356.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the tool arguments from the client - query is matched against article titles |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | the authenticated api_keys row; its companies decide which articles are searched |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 363 | `mcpToolSearchKnowledge` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

*Nothing calls this function by name.*

