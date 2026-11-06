# handleHardDelete()

Permanently deletes an archived article through KnowledgeService.

```php
function handleHardDelete($conn, $input)
```

Defined in [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$input` |  |  | the decoded JSON request; id is the archived article to delete for good |

## Called from

- [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md)

