# imapSplitAddresses()

Split a "a@b; c@d, e@f" string into a list of valid addresses.

```php
function imapSplitAddresses(string $raw): array
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 577.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | addresses separated by ; or ,; invalid ones are dropped |

**Returns:** `array`

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapSmtpSend()](/code/functions/imapSmtpSend.md)

