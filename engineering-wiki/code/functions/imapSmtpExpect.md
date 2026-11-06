# imapSmtpExpect()

Read a (possibly multi-line) SMTP reply and assert its status code.

```php
function imapSmtpExpect($fp, array $expected): string
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 606.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fp` |  |  | the open SMTP socket |
| `$expected` | `array` |  | the reply codes that count as success; anything else throws |

**Returns:** `string`

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapSmtpSend()](/code/functions/imapSmtpSend.md), [imapSmtpCommand()](/code/functions/imapSmtpCommand.md)

