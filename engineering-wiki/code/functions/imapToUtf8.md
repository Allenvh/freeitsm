# imapToUtf8()

Convert a string to UTF-8 from the part's declared charset (best effort).

```php
function imapToUtf8(string $data, string $charset): string
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 327.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$data` | `string` |  | the decoded text |
| `$charset` | `string` |  | the part's declared charset; blank is read as UTF-8 |

**Returns:** `string`

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapWalkStructure()](/code/functions/imapWalkStructure.md), [imapDecodeHeader()](/code/functions/imapDecodeHeader.md)

