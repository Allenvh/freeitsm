# mailboxGraphGet()

An authenticated Graph GET, shaped for `mailboxResolveFolderId()`. Every caller was writing the same twelve lines of curl.

```php
function mailboxGraphGet($accessToken, $url)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 322.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` |  |  | a Graph access token for the mailbox |
| `$url` |  |  | the full Graph URL to GET |

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [getEmails()](/code/functions/getEmails.md), [resolveMailFolderId()](/code/functions/resolveMailFolderId.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)

