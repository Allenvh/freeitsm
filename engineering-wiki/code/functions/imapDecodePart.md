# imapDecodePart()

Decode a transfer-encoded body part (base64 / quoted-printable / raw).

```php
function imapDecodePart(string $data, int $encoding): string
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 310.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$data` | `string` |  | the part as fetched |
| `$encoding` | `int` |  | IMAP's transfer encoding code - 3 base64, 4 quoted-printable, anything else returned as is |

**Returns:** `string`

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapWalkStructure()](/code/functions/imapWalkStructure.md)

