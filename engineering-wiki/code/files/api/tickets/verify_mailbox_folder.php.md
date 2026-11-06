# api/tickets/verify_mailbox_folder.php

> API endpoint - 221 lines

API Endpoint: Verify a mail folder exists in a mailbox POST: { "mailbox_id": N, "folder_name": "Processed" }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/verify_mailbox_folder.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MAILBOXES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 171 |  | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), [includes/gmail.php](/code/files/includes/gmail.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [encryptValue()](/code/functions/encryptValue.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailVerifyFolder()](/code/functions/gmailVerifyFolder.md), [imapVerifyFolder()](/code/functions/imapVerifyFolder.md), [mailboxAppOnlyToken()](/code/functions/mailboxAppOnlyToken.md), [mailboxGraphBase()](/code/functions/mailboxGraphBase.md), [mailboxGraphGet()](/code/functions/mailboxGraphGet.md), [mailboxIdentityMismatch()](/code/functions/mailboxIdentityMismatch.md), [mailboxResolveFolderId()](/code/functions/mailboxResolveFolderId.md), [mailboxResolveGraphBase()](/code/functions/mailboxResolveGraphBase.md), [oauthTokenErrorMessage()](/code/functions/oauthTokenErrorMessage.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

