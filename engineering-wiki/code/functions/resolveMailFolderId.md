# resolveMailFolderId()

Resolve a folder name to what Graph will accept in a path.

Delegates to `mailboxResolveFolderId()` in includes/mailbox_graph.php. It used to
be implemented here AND again in verify_mailbox_folder.php — which is how the
two disagreed: Verify resolved "freeitsm" happily while the code that actually
READ the mailbox never called either of them (GH #77). One implementation now,
so the button and the fetch can no longer answer differently.

```php
function resolveMailFolderId($accessToken, $folderName)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 682.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` |  |  | the mailbox's Microsoft Graph access token, from `getValidAccessToken()` |
| `$folderName` |  |  | the folder name as typed in the mailbox settings, or a well-known alias such as 'deleteditems' |

## Calls

[mailboxGraphGet()](/code/functions/mailboxGraphGet.md), [mailboxResolveFolderId()](/code/functions/mailboxResolveFolderId.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [moveEmailToFolder()](/code/functions/moveEmailToFolder.md)

