# decryptValue()

Decrypt an encrypted value

If the value doesn't have the ENC: prefix, it's returned as-is.
This allows gradual migration from plaintext to encrypted values.

```php
function decryptValue($encrypted)
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 275.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$encrypted` |  |  | The encrypted value (with ENC: prefix) |

**Returns:** string|null Decrypted plaintext, or the original value if not encrypted

## Calls

[getEncryptionKey()](/code/functions/getEncryptionKey.md)

## Called from

- [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md)
- [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md)
- [api/cmdb/get_ai_settings.php](/code/files/api/cmdb/get_ai_settings.php.md)
- [api/cmdb/test_ai_key.php](/code/files/api/cmdb/test_ai_key.php.md)
- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [api/forms/test_ai_key.php](/code/files/api/forms/test_ai_key.php.md)
- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md)
- [api/knowledge/generate_embedding.php](/code/files/api/knowledge/generate_embedding.php.md)
- [api/knowledge/get_email_settings.php](/code/files/api/knowledge/get_email_settings.php.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md)
- [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md)
- [api/rfp-builder/get_ai_settings.php](/code/files/api/rfp-builder/get_ai_settings.php.md)
- [api/rfp-builder/test_ai_connection.php](/code/files/api/rfp-builder/test_ai_connection.php.md)
- [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md)
- [api/settings/get_system_settings.php](/code/files/api/settings/get_system_settings.php.md)
- [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md)
- [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md)
- [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md)
- [api/system/get_tenant_email_routing.php](/code/files/api/system/get_tenant_email_routing.php.md)
- [api/system/get_topology.php](/code/files/api/system/get_topology.php.md)
- [api/system/run_directory_sync.php](/code/files/api/system/run_directory_sync.php.md)
- [api/system/test_carddav_connection.php](/code/files/api/system/test_carddav_connection.php.md)
- [api/system/test_carddav_write.php](/code/files/api/system/test_carddav_write.php.md)
- [api/system/test_directory_mapping.php](/code/files/api/system/test_directory_mapping.php.md)
- [api/tickets/get_reply_cleanup_settings.php](/code/files/api/tickets/get_reply_cleanup_settings.php.md)
- [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md)
- [api/tickets/test_reply_cleanup_key.php](/code/files/api/tickets/test_reply_cleanup_key.php.md)
- [api/workflow/test_ai_key.php](/code/files/api/workflow/test_ai_key.php.md)
- [cron/attachment_extract.php](/code/files/cron/attachment_extract.php.md)
- [cron/calendar_sync_pull.php](/code/files/cron/calendar_sync_pull.php.md)
- [cron/domains.php](/code/files/cron/domains.php.md)
- [cron/integration_poll.php](/code/files/cron/integration_poll.php.md)
- [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md)
- [cron/task_recurrence.php](/code/files/cron/task_recurrence.php.md)
- [cron/webhook_deliveries.php](/code/files/cron/webhook_deliveries.php.md)
- [cron/workflow_scheduled.php](/code/files/cron/workflow_scheduled.php.md)
- [includes/ai_settings.php](/code/files/includes/ai_settings.php.md) - in [aiSettingsLoad()](/code/functions/aiSettingsLoad.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncDecodeCredentials()](/code/functions/calendarSyncDecodeCredentials.md)
- [includes/carddav.php](/code/files/includes/carddav.php.md) - in [cardDavConfigFromProvider()](/code/functions/cardDavConfigFromProvider.md)
- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatGetSetting()](/code/functions/csatGetSetting.md)
- [includes/encryption.php](/code/files/includes/encryption.php.md) - in [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [settingsGetDecrypted()](/code/functions/settingsGetDecrypted.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsDecodeCredentials()](/code/functions/integrationsDecodeCredentials.md), [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md)
- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneGetSettings()](/code/functions/intuneGetSettings.md)
- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [knowledgeOpenAiKey()](/code/functions/knowledgeOpenAiKey.md)
- [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md) - in [kbRetrieveArticles()](/code/functions/kbRetrieveArticles.md)
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapGetProvider()](/code/functions/ldapGetProvider.md)
- [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md) - in [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingDecodeCredentials()](/code/functions/messagingDecodeCredentials.md)
- [includes/oidc.php](/code/files/includes/oidc.php.md) - in [oidcGetProvider()](/code/functions/oidcGetProvider.md)
- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxLoadConnection()](/code/functions/proxmoxLoadConnection.md)
- [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) - in [rfpAiGetSettings()](/code/functions/rfpAiGetSettings.md)
- [includes/self_service_mailboxes.php](/code/files/includes/self_service_mailboxes.php.md) - in [selfServiceMailboxes()](/code/functions/selfServiceMailboxes.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::revealAuthCode`
- [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) - in `KnowledgeService::updateEmbedding`
- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdLoadConnection()](/code/functions/vcdLoadConnection.md)
- [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) - in [webhookDecrypt()](/code/functions/webhookDecrypt.md)

