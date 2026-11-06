# aiProviderChatTools()

─── Tool calling ────────────────────────────────────────────────────────────

A conversation where the model may ask us to run something and then answer
using the result. Everything above is single-turn; this is the loop.

Deliberately ADDITIVE — `aiProviderChat()` and its two callees are untouched, so
the eight existing AI features cannot be affected by anything here.

The two wire formats differ more than they look:

  Anthropic  tools:[{name,description,input_schema}]; the reply carries
             content blocks of type tool_use; the result goes back as a USER
             message containing tool_result blocks, and the assistant's own
             turn must be echoed back verbatim first.
  OpenAI     tools:[{type:'function',function:{...,parameters}}]; the reply
             carries message.tool_calls with arguments as a JSON STRING; each
             result goes back as its own message with role:'tool'.

`$runTool` receives (string `$name`, array `$args`) and returns a string — whatever
the model should see. It must NEVER throw: a tool that fails should say so in
words, because "the CMDB lookup failed" is a useful thing for the model to
tell the reader, and an exception here would lose the whole conversation.

`$opts`['history'] (3.3.0, the Projects assistant): earlier turns of the same
conversation, oldest first, as [['role' => 'user'|'assistant', 'content' => text], ...].
They go in before `$opts`['user'], so a conversation resumes rather than starting cold.
Plain text only - the tool calls of earlier turns are not replayed.

Azure (3.3.0): the OpenAI wire format at the deployment's address, behind an
api-key header and with no model in the body, as `aiProviderChat()` already does.
Before this it fell through to api.openai.com with the Azure key, so tool
calling - Warbot - could not work on Azure at all.

```php
function aiProviderChatTools(array $cfg, array $opts, array $tools, callable $runTool): array
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 505.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the AI settings - provider, model, api_key, verify_ssl, base_url and azure_* are read |
| `$opts` | `array` |  | system, user, history, max_rounds (1-6, default 4), max_tokens, temperature, referer, title |
| `$tools` | `array` |  | [['name'=>…,'description'=>…,'schema'=>[JSON Schema]], …] |
| `$runTool` | `callable` |  | fn(string `$name`, array `$args`): string |

**Returns:** array ['content','calls'=>[['name','args','result'],…],'tokens_in','tokens_out','provider','model','duration_ms']

## Calls

[aiAzureChatUrl()](/code/functions/aiAzureChatUrl.md), [aiAzureIsV1()](/code/functions/aiAzureIsV1.md), [aiOpenAICompatPost()](/code/functions/aiOpenAICompatPost.md), [aiProviderHttpPost()](/code/functions/aiProviderHttpPost.md), [aiToolsResult()](/code/functions/aiToolsResult.md)

## Called from

- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatTurn()](/code/functions/projectChatTurn.md)
- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotAnswer()](/code/functions/warbotAnswer.md)

