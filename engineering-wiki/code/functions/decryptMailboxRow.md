# decryptMailboxRow()

Decrypt all encrypted columns in a target_mailboxes row.

```php
function decryptMailboxRow($row)
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 323.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` |  |  | A single row from the target_mailboxes table |

**Returns:** array|null The row with encrypted columns decrypted

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md)
- [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md) - in [sendMailboxEmail()](/code/functions/sendMailboxEmail.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md) - in [sendMailboxEmail()](/code/functions/sendMailboxEmail.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [getMailboxConfig()](/code/functions/getMailboxConfig.md)
- [api/tickets/get_mailboxes.php](/code/files/api/tickets/get_mailboxes.php.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [getMailboxForTicket()](/code/functions/getMailboxForTicket.md)
- [api/tickets/set_mailbox_health_dismissed.php](/code/files/api/tickets/set_mailbox_health_dismissed.php.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncLoadConnection()](/code/functions/calendarSyncLoadConnection.md)
- [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) - in [ssGetSendingMailbox()](/code/functions/ssGetSendingMailbox.md)
- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_get_first_active_mailbox()](/code/functions/sla_get_first_active_mailbox.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [templateGetMailboxForTicket()](/code/functions/templateGetMailboxForTicket.md), [internalTicketEmail()](/code/functions/internalTicketEmail.md)

