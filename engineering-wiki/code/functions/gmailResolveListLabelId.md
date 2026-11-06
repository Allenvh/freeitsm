# gmailResolveListLabelId()

Resolve the configured folder to the label id the message list should be scoped to.

⚠️ Gmail has no folders. It has labels, a message carries several at once, and
"in the Inbox" is itself just a label — archiving a message removes INBOX and
leaves the rest. So the configured folder IS the list scope.

This used to read `labelIds=INBOX` with the configured folder bolted on as an
extra `label:` search term, which meant a message had to be in the Inbox AND
carry the label. Mail filtered straight past the Inbox — the usual reason to
label anything — was never collected, silently and permanently.

Scoping by label ID also fixes labels whose names contain spaces: Gmail's
`label:` search syntax cannot express "Customer Support" without quoting rules
that the old string concatenation did not apply.

See the Gmail mail collection developer guide on the wiki.

```php
function gmailResolveListLabelId(string $accessToken, array $mailbox): string
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 238.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` | `string` |  | a valid Google OAuth access token for the mailbox |
| `$mailbox` | `array` |  | the target_mailboxes row; email_folder is the label name, blank meaning INBOX |

**Returns:** `string`

## Calls

[gmailFindLabel()](/code/functions/gmailFindLabel.md), [gmailListLabels()](/code/functions/gmailListLabels.md)

## Called from

- [includes/gmail.php](/code/files/includes/gmail.php.md) - in [gmailGetEmails()](/code/functions/gmailGetEmails.md)

