# encryptValue()

Encrypt a plaintext value using AES-256-GCM

```php
function encryptValue($plaintext)
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 233.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$plaintext` |  |  | The value to encrypt |

**Returns:** string|null Encrypted string with ENC: prefix, or null/empty if input is null/empty

## Calls

[getEncryptionKey()](/code/functions/getEncryptionKey.md)

## Called from

- [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md) - in [saveTokenData()](/code/functions/saveTokenData.md)
- [api/forms/save_ai_settings.php](/code/files/api/forms/save_ai_settings.php.md)
- [api/integrations/save_connection.php](/code/files/api/integrations/save_connection.php.md)
- [api/integrations/test_connection.php](/code/files/api/integrations/test_connection.php.md)
- [api/knowledge/save_email_settings.php](/code/files/api/knowledge/save_email_settings.php.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md) - in [saveTokenData()](/code/functions/saveTokenData.md)
- [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md)
- [api/myaccount/verify_mfa.php](/code/files/api/myaccount/verify_mfa.php.md)
- [api/rfp-builder/save_ai_settings.php](/code/files/api/rfp-builder/save_ai_settings.php.md)
- [api/settings/save_system_settings.php](/code/files/api/settings/save_system_settings.php.md)
- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)
- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveTokenData()](/code/functions/saveTokenData.md)
- [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [saveTokenData()](/code/functions/saveTokenData.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)
- [api/workflow/save_ai_settings.php](/code/files/api/workflow/save_ai_settings.php.md)
- [includes/ai_settings.php](/code/files/includes/ai_settings.php.md) - in [aiSettingsSave()](/code/functions/aiSettingsSave.md)
- [includes/calendar_sync/MicrosoftCalendarProvider.php](/code/files/includes/calendar_sync/MicrosoftCalendarProvider.php.md) - in `MicrosoftCalendarProvider::token`
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncEncodeCredentials()](/code/functions/calendarSyncEncodeCredentials.md)
- [includes/gmail.php](/code/files/includes/gmail.php.md) - in [gmailRefreshAccessToken()](/code/functions/gmailRefreshAccessToken.md)
- [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md) - in [mailboxAppOnlyToken()](/code/functions/mailboxAppOnlyToken.md)
- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxSaveConnection()](/code/functions/proxmoxSaveConnection.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::createDomain`, `DomainsService::updateDomain`
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [templateGetValidAccessToken()](/code/functions/templateGetValidAccessToken.md)
- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdSaveConnection()](/code/functions/vcdSaveConnection.md)
- [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) - in [webhookEncrypt()](/code/functions/webhookEncrypt.md)

