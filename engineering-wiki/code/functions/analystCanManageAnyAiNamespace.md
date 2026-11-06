# analystCanManageAnyAiNamespace()

May this analyst manage the AI settings of ANY namespace?

For the shared model-list endpoint, which has no namespace of its own but is needed by
the panel to populate its model dropdown. Anyone who can configure at least one module's
AI can fetch the list; nobody else can.

```php
function analystCanManageAnyAiNamespace(PDO $conn, int $analystId): bool
```

Defined in [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), line 239.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[aiSettingsRegistry()](/code/functions/aiSettingsRegistry.md), [analystCanManageAiNamespace()](/code/functions/analystCanManageAiNamespace.md)

## Called from

- [api/system/ai/openrouter_models.php](/code/files/api/system/ai/openrouter_models.php.md)

