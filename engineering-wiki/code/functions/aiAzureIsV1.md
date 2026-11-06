# aiAzureIsV1()

An API version of "v1" means Azure's newer route, which has no api-version.

```php
function aiAzureIsV1(array $cfg): bool
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 245.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the AI settings - only azure_api_version is read |

**Returns:** `bool`

## Called from

- [includes/ai_provider.php](/code/files/includes/ai_provider.php.md) - in [aiProviderChat()](/code/functions/aiProviderChat.md), [aiAzureChatUrl()](/code/functions/aiAzureChatUrl.md), [aiProviderChatTools()](/code/functions/aiProviderChatTools.md)

