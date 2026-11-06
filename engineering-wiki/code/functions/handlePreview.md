# handlePreview()

How many documents would change hands, and for whom.

⚠️ COUNTED, NOT ESTIMATED, and counted PER PERSON — because "47 documents
change" is not a fact anybody can act on. The honest question is "who gains or
loses what", so this asks the real resolver, under both models, on behalf of
every active analyst, and reports the difference.

Bounded deliberately: the sample stops at 25 analysts and says so, because an
install with 400 analysts and 10,000 articles would otherwise turn a settings
page into a four-million-row calculation. A capped answer that admits its cap
is useful; an uncapped one that times out is not, and a silent cap would be
worse than either.

```php
function handlePreview(PDO $conn, string $target): void
```

Defined in [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md), line 90.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$target` | `string` |  | the model to compare against, from ?model= - 'filing', anything else meaning 'containers' |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 109 | `handlePreview` | read [analysts](/code/tables/analysts.md) |  |
| 116 | `handlePreview` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 128 | `handlePreview` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 146 | `handlePreview` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## Calls

[knowledgeAclHasAnyRows()](/code/functions/knowledgeAclHasAnyRows.md), [knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md), [knowledgeFolderPermissionModel()](/code/functions/knowledgeFolderPermissionModel.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

## Called from

- [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md)

