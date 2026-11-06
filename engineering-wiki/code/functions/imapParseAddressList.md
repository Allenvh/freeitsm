# imapParseAddressList()

Parse an RFC 2822 address-list header into the Graph-like recipient shape. Uses imap_rfc822_parse_adrlist when available; falls back to a light regex.

```php
function imapParseAddressList(string $raw): array
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 376.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | a raw address-list header, e.g. 'Name <a@b>, c@d' |

**Returns:** `array`

## Calls

[imapDecodeHeader()](/code/functions/imapDecodeHeader.md)

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapNormaliseMessage()](/code/functions/imapNormaliseMessage.md)

