# imapSmtpClientHostname()

Derive a sensible EHLO hostname from the sender address.

```php
function imapSmtpClientHostname(string $from): string
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 567.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$from` | `string` |  | the sender address; its domain is used, or 'localhost' when it has none |

**Returns:** `string`

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapSmtpSend()](/code/functions/imapSmtpSend.md)

