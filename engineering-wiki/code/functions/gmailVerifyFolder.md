# gmailVerifyFolder()

Verify a label exists in a Google mailbox and report what the reader will see.

⚠️ WHY THIS EXISTS. verify_mailbox_folder.php was written for Microsoft Graph.
A Google mailbox DOES hold an OAuth token, so it sailed past the token gate and
then ran the whole Microsoft flow with Google credentials: the access token went
to graph.microsoft.com, and an expired one was refreshed against
login.microsoftonline.com using azure_client_id / azure_client_secret that a
Google mailbox does not have. The user got a Microsoft error about a mailbox
with nothing to do with Microsoft.

⚠️ Counts are taken through the reader's own scope — the label id resolved by
`gmailResolveListLabelId()` — and never through a differently-built query. If the
two ever diverge, Verify starts reporting a number the collection cannot see,
which is the GH #77 failure shape wearing a Gmail hat.

```php
function gmailVerifyFolder(string $accessToken, array $mailbox, string $folderName): array
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 632.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` | `string` |  | a valid Google OAuth access token for the mailbox |
| `$mailbox` | `array` |  | the target_mailboxes row; only max_emails_per_check is read (for the backlog note) |
| `$folderName` | `string` |  | the label name to check, as typed in the mailbox settings |

**Returns:** array{displayName:string, totalItemCount:int, unreadItemCount:int, note:string|null}

## Calls

[gmailApiGet()](/code/functions/gmailApiGet.md), [gmailFindLabel()](/code/functions/gmailFindLabel.md), [gmailListLabels()](/code/functions/gmailListLabels.md)

## Called from

- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)

