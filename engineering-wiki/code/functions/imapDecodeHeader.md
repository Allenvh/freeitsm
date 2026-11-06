# imapDecodeHeader()

Decode a MIME-encoded header value (e.g. =?UTF-8?B?...?=) to a UTF-8 string.

```php
function imapDecodeHeader(string $value): string
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 352.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` | `string` |  | a raw header value, possibly MIME-encoded words |

**Returns:** `string`

## Calls

[imapToUtf8()](/code/functions/imapToUtf8.md)

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapNormaliseMessage()](/code/functions/imapNormaliseMessage.md), [imapWalkStructure()](/code/functions/imapWalkStructure.md), [imapParseAddressList()](/code/functions/imapParseAddressList.md)

