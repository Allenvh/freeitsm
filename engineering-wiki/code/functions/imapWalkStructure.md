# imapWalkStructure()

Recursively walk an `imap_fetchstructure()` tree, accumulating decoded HTML/plain body text and Graph-shaped attachment entries.

```php
function imapWalkStructure(
    $stream,
    int $uid,
    $part,
    string $partNo,
    string &$html,
    string &$plain,
    array &$attachments
): void
```

Defined in [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), line 226.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$stream` |  |  | the open IMAP stream |
| `$uid` | `int` |  | the message's IMAP UID |
| `$part` |  |  | one node of the `imap_fetchstructure()` tree (an object) |
| `$partNo` | `string` |  | the IMAP part number of `$part`, e.g. '1.2'; '' for the top of the message |
| `$html` | `string` |  | collects the decoded HTML body, by reference |
| `$plain` | `string` |  | collects the decoded plain-text body, by reference |
| `$attachments` | `array` |  | collects Graph-shaped fileAttachment arrays, by reference |

**Returns:** `void`

## Calls

[imapDecodeHeader()](/code/functions/imapDecodeHeader.md), [imapDecodePart()](/code/functions/imapDecodePart.md), [imapToUtf8()](/code/functions/imapToUtf8.md)

## Called from

- [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md) - in [imapNormaliseMessage()](/code/functions/imapNormaliseMessage.md), [imapWalkStructure()](/code/functions/imapWalkStructure.md)

