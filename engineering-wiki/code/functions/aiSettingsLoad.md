# aiSettingsLoad()

Load a ready-to-use config (decrypted key) for `aiProviderChat()`. Defaults: provider/model from the registry; verify_ssl from the row, else the global SSL_VERIFY_PEER constant (preserves existing localhost behaviour).

```php
function aiSettingsLoad(PDO $conn, string $ns): array
```

Defined in [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), line 160.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ns` | `string` |  | a registered namespace, e.g. 'workflow_ai'; an unknown one throws |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 167 | `aiSettingsLoad` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[aiSettingsEntry()](/code/functions/aiSettingsEntry.md), [aiSettingsKeys()](/code/functions/aiSettingsKeys.md), [decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md) - in [loadCmdbAiConfig()](/code/functions/loadCmdbAiConfig.md)
- [api/forms/_ai_helpers.php](/code/files/api/forms/_ai_helpers.php.md) - in [loadFormsAiConfig()](/code/functions/loadFormsAiConfig.md)
- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md)
- [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md)
- [api/lms/tests.php](/code/files/api/lms/tests.php.md)
- [api/messaging/ai_suggest_reply.php](/code/files/api/messaging/ai_suggest_reply.php.md)
- [api/messaging/ai_summary.php](/code/files/api/messaging/ai_summary.php.md)
- [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md)
- [api/problem-management/ai_suggest_problem.php](/code/files/api/problem-management/ai_suggest_problem.php.md)
- [api/system/ai/test_connection.php](/code/files/api/system/ai/test_connection.php.md)
- [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md)
- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)
- [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md)
- [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md)
- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)
- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)
- [api/war-room/sitrep.php](/code/files/api/war-room/sitrep.php.md)
- [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md) - in [loadWorkflowAiConfig()](/code/functions/loadWorkflowAiConfig.md)
- [includes/ai_settings.php](/code/files/includes/ai_settings.php.md) - in [aiSettingsForUi()](/code/functions/aiSettingsForUi.md)
- [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md) - in [writeupAiConfig()](/code/functions/writeupAiConfig.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiAsk()](/code/functions/projectAiAsk.md), [projectAiReady()](/code/functions/projectAiReady.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatTurn()](/code/functions/projectChatTurn.md)
- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotAnswer()](/code/functions/warbotAnswer.md)
- [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md) - in [webchatAiConfigured()](/code/functions/webchatAiConfigured.md), [webchatAiReply()](/code/functions/webchatAiReply.md), [webchatAiSummarise()](/code/functions/webchatAiSummarise.md)
- [system/ai/index.php](/code/files/system/ai/index.php.md)

