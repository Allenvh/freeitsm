# replyTemplateMergeCodes()

The merge codes offered in the UI, in the order they are listed.

A subset of `buildTicketMergeData()`'s keys: the ones that make sense mid-conversation
in something a human is about to send. [closed_date] is omitted on purpose — you are
not usually typing a canned response into a ticket that is already closed.

```php
function replyTemplateMergeCodes(): array
```

Defined in [includes/reply_templates.php](/code/files/includes/reply_templates.php.md), line 44.

**Returns:** `array`

## Called from

- [api/tickets/get_reply_templates.php](/code/files/api/tickets/get_reply_templates.php.md)

