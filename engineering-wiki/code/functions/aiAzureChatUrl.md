# aiAzureChatUrl()

Build the URL for an Azure OpenAI deployment-based chat call.

⚠️ THE QUERY STRING IS NOT OPTIONAL. Azure refuses the request outright
without `api-version`, which is why this cannot be expressed as a base URL the
way OpenAI and OpenRouter are — and why this helper exists rather than another
entry in the base-URL ternary above.

The endpoint is normalised: administrators copy it out of the Azure portal
with or without a trailing slash, and sometimes with `/openai` already on the
end. Both are the same intention and neither should be a support question.

```php
function aiAzureChatUrl(array $cfg): string
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 213.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the AI settings - azure_endpoint, azure_deployment and azure_api_version are read |

**Returns:** `string`

## Calls

[aiAzureIsV1()](/code/functions/aiAzureIsV1.md)

## Called from

- [includes/ai_provider.php](/code/files/includes/ai_provider.php.md) - in [aiProviderChat()](/code/functions/aiProviderChat.md), [aiProviderChatTools()](/code/functions/aiProviderChatTools.md)

