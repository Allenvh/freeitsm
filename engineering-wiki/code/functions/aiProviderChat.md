# aiProviderChat()

Send a one-shot chat request and return a normalised result.

```php
function aiProviderChat(array $cfg, array $opts): array
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 66.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | ['provider','model','api_key','verify_ssl'(bool),'base_url'?] |
| `$opts` | `array` |  | ['system','user','max_tokens'?=1024,'temperature'?=0.0, 'referer'?,'title'?]  (referer/title attribute the call on OpenRouter's dashboard — defaults to FreeITSM) |

**Returns:** array ['content','tokens_in','tokens_out','provider','model','duration_ms', 'finish_reason','reasoning_tokens']

## Calls

[aiAzureChatUrl()](/code/functions/aiAzureChatUrl.md), [aiAzureIsV1()](/code/functions/aiAzureIsV1.md), [aiProviderCallAnthropic()](/code/functions/aiProviderCallAnthropic.md), [aiProviderCallOpenAICompatible()](/code/functions/aiProviderCallOpenAICompatible.md)

## Called from

- [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md) - in [callAnthropic()](/code/functions/callAnthropic.md)
- [api/forms/ai_generate.php](/code/files/api/forms/ai_generate.php.md)
- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md)
- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)
- [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md)
- [api/messaging/ai_suggest_reply.php](/code/files/api/messaging/ai_suggest_reply.php.md)
- [api/messaging/ai_summary.php](/code/files/api/messaging/ai_summary.php.md)
- [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md)
- [api/problem-management/ai_suggest_problem.php](/code/files/api/problem-management/ai_suggest_problem.php.md)
- [api/system/ai/test_connection.php](/code/files/api/system/ai/test_connection.php.md)
- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)
- [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md)
- [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md)
- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)
- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)
- [api/war-room/sitrep.php](/code/files/api/war-room/sitrep.php.md)
- [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md) - in [callWorkflowAi()](/code/functions/callWorkflowAi.md)
- [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) - in [lmsCtGenerate()](/code/functions/lmsCtGenerate.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiAsk()](/code/functions/projectAiAsk.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatSummarise()](/code/functions/projectChatSummarise.md)
- [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md) - in [webchatAiReply()](/code/functions/webchatAiReply.md), [webchatAiSummarise()](/code/functions/webchatAiSummarise.md)

