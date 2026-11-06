# mailboxResolveFolderId()

Turn a folder a human typed into the id Graph actually wants (GH #77).

⚠️ THE WHOLE POINT. `/mailFolders/<something>` does NOT accept folder names.
Graph accepts a short list of WELL-KNOWN aliases there — inbox, archive,
sentitems and the few below — and treats anything else as an opaque folder
ID. So reading from a folder called "freeitsm" produced:

    400  ErrorInvalidIdMalformed — "Id is malformed."

"INBOX" had always worked for exactly one reason: it is on the alias list.
Reading a folder BY NAME had never worked at all, for any name off that list.

Nested folders are supported with a slash — "Inbox/freeitsm" — because
putting the intake folder under Inbox is the first thing most people try, and
a flat lookup answers "not found" for a folder they can plainly see. Each
segment after the first is resolved through childFolders, since
/mailFolders lists TOP-LEVEL folders only.

```php
function mailboxResolveFolderId($folderName, callable $get)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 367.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$folderName` |  |  | the folder as typed in the mailbox settings, e.g. 'Inbox/freeitsm'; a slash means a nested folder |
| `$get` | `callable` |  | fn(string `$url`): array — performs an authenticated GET and returns ['code' => int, 'body' => array\|null]. Passed in so this has no opinion about how callers do HTTP. |

**Returns:** string   The folder id, or a well-known alias (also valid in a path).

## Calls

[mailboxGraphBase()](/code/functions/mailboxGraphBase.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [getEmails()](/code/functions/getEmails.md), [resolveMailFolderId()](/code/functions/resolveMailFolderId.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)

