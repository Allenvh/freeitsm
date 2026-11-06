# sslApplyCurl()

Applies the install's TLS verification setting, and the CA bundle, to a cURL handle.

```php
function sslApplyCurl($ch, bool $alwaysVerify = false): void
```

Defined in [includes/ssl.php](/code/files/includes/ssl.php.md), line 89.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ch` |  |  | the cURL handle from `curl_init()` (CurlHandle, or a resource before PHP 8) |
| `$alwaysVerify` | `bool` | `false` | true verifies even when SSL_VERIFY_PEER is off - for traffic that must never go unverified |

**Returns:** `void`

## Calls

[sslResolveCaBundle()](/code/functions/sslResolveCaBundle.md)

## Called from

- [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md) - in [vcenterAuth()](/code/functions/vcenterAuth.md), [vcenterGetRaw()](/code/functions/vcenterGetRaw.md), [vcenterLogout()](/code/functions/vcenterLogout.md)
- [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md) - in [vcenterAuth()](/code/functions/vcenterAuth.md), [vcenterGet()](/code/functions/vcenterGet.md), [vcenterLogout()](/code/functions/vcenterLogout.md)
- [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md)
- [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md) - in [sendMailboxEmail()](/code/functions/sendMailboxEmail.md), [refreshAccessToken()](/code/functions/refreshAccessToken.md)
- [api/cmdb/test_ai_key.php](/code/files/api/cmdb/test_ai_key.php.md)
- [api/forms/test_ai_key.php](/code/files/api/forms/test_ai_key.php.md) - in [formsTestAnthropic()](/code/functions/formsTestAnthropic.md), [formsTestOpenAI()](/code/functions/formsTestOpenAI.md)
- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md) - in [generateEmbedding()](/code/functions/generateEmbedding.md)
- [api/knowledge/generate_embedding.php](/code/files/api/knowledge/generate_embedding.php.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md) - in [sendMailboxEmail()](/code/functions/sendMailboxEmail.md), [refreshAccessToken()](/code/functions/refreshAccessToken.md)
- [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md)
- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md) - in [testReachability()](/code/functions/testReachability.md)
- [api/rfp-builder/test_ai_connection.php](/code/files/api/rfp-builder/test_ai_connection.php.md) - in [rfpTestAnthropic()](/code/functions/rfpTestAnthropic.md), [rfpTestOpenAI()](/code/functions/rfpTestOpenAI.md)
- [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md)
- [api/system/debug-tools/D006_ssl_verify.php](/code/files/api/system/debug-tools/D006_ssl_verify.php.md)
- [api/system/test_oidc_discovery.php](/code/files/api/system/test_oidc_discovery.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [refreshAccessToken()](/code/functions/refreshAccessToken.md), [getEmails()](/code/functions/getEmails.md), [deleteEmailFromMailbox()](/code/functions/deleteEmailFromMailbox.md), [moveEmailToFolder()](/code/functions/moveEmailToFolder.md), [markEmailAsRead()](/code/functions/markEmailAsRead.md), [fetchEmailAttachments()](/code/functions/fetchEmailAttachments.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [refreshAccessToken()](/code/functions/refreshAccessToken.md), [sendEmailViaGraph()](/code/functions/sendEmailViaGraph.md)
- [api/tickets/test_reply_cleanup_key.php](/code/files/api/tickets/test_reply_cleanup_key.php.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)
- [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md) - in [wfCallAnthropic()](/code/functions/wfCallAnthropic.md), [wfCallOpenAI()](/code/functions/wfCallOpenAI.md)
- [api/workflow/test_ai_key.php](/code/files/api/workflow/test_ai_key.php.md) - in [wfTestAnthropic()](/code/functions/wfTestAnthropic.md), [wfTestOpenAI()](/code/functions/wfTestOpenAI.md)
- [includes/ai_provider.php](/code/files/includes/ai_provider.php.md) - in [aiProviderHttpPost()](/code/functions/aiProviderHttpPost.md), [aiProviderListOpenRouterModels()](/code/functions/aiProviderListOpenRouterModels.md)
- [includes/calendar_sync/MicrosoftCalendarProvider.php](/code/files/includes/calendar_sync/MicrosoftCalendarProvider.php.md) - in `MicrosoftCalendarProvider::token`, `MicrosoftCalendarProvider::call`, `MicrosoftCalendarProvider::callAbsolute`
- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainHttpGet()](/code/functions/domainHttpGet.md)
- [includes/gmail.php](/code/files/includes/gmail.php.md) - in [gmailRefreshAccessToken()](/code/functions/gmailRefreshAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [gmailApiGet()](/code/functions/gmailApiGet.md), [gmailGetEmails()](/code/functions/gmailGetEmails.md), [gmailGetMessage()](/code/functions/gmailGetMessage.md), [gmailGetAttachmentsGraphShape()](/code/functions/gmailGetAttachmentsGraphShape.md), [gmailTrashMessage()](/code/functions/gmailTrashMessage.md), [gmailMarkAsRead()](/code/functions/gmailMarkAsRead.md), [gmailGetAttachments()](/code/functions/gmailGetAttachments.md)
- [includes/hypervisor_http.php](/code/files/includes/hypervisor_http.php.md) - in `HypervisorHttp::request`
- [includes/integrations/IssueTrackerProvider.php](/code/files/includes/integrations/IssueTrackerProvider.php.md) - in `IssueTrackerProvider::httpRequest`
- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneGetToken()](/code/functions/intuneGetToken.md), [intuneGraphGet()](/code/functions/intuneGraphGet.md)
- [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md) - in [kbGenerateEmbedding()](/code/functions/kbGenerateEmbedding.md)
- [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md) - in [mailboxAppOnlyToken()](/code/functions/mailboxAppOnlyToken.md), [mailboxDelegatedIdentity()](/code/functions/mailboxDelegatedIdentity.md), [mailboxFetchAddresses()](/code/functions/mailboxFetchAddresses.md), [mailboxGraphGet()](/code/functions/mailboxGraphGet.md)
- [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md) - in `MessagingProvider::httpRequest`, `MessagingProvider::httpMultipart`
- [includes/messaging/SlackProvider.php](/code/files/includes/messaging/SlackProvider.php.md) - in `SlackProvider::httpRequestWithHeaders`
- [includes/oidc.php](/code/files/includes/oidc.php.md) - in [oidcHttpGet()](/code/functions/oidcHttpGet.md), [oidcHttpPost()](/code/functions/oidcHttpPost.md), [oidcFetchUserInfo()](/code/functions/oidcFetchUserInfo.md)
- [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) - in [rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md), [rfpAiHttpPostWithRetry()](/code/functions/rfpAiHttpPostWithRetry.md)
- [includes/search/tika.php](/code/files/includes/search/tika.php.md) - in [tikaExtract()](/code/functions/tikaExtract.md), [tikaPing()](/code/functions/tikaPing.md)
- [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) - in `KnowledgeService::updateEmbedding`
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [templateGetValidAccessToken()](/code/functions/templateGetValidAccessToken.md), [templateSendViaGraph()](/code/functions/templateSendViaGraph.md)
- [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) - in [webhookHttpSend()](/code/functions/webhookHttpSend.md)
- [setup/index.php](/code/files/setup/index.php.md)

