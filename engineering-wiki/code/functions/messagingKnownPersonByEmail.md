# messagingKnownPersonByEmail()

The person FreeITSM already holds with this email: [userId, displayName], or null. A real person's chat tickets then sit with their emailed ones, their history and their company. Shared by Slack, Teams and Mattermost.

TRAP: only ever pass an address the PLATFORM vouches for - Slack's profile,
  Microsoft 365's directory, Mattermost's verified flag. The email is the
  whole of the identity here: an address someone merely typed would file
  their chats under whoever owns it. users.email is unique, so a match is
  one person, never a guess between two.

```php
function messagingKnownPersonByEmail(PDO $conn, string $email, string $name): ?array
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 408.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$email` | `string` |  | an address the platform vouches for; '' or invalid gives null |
| `$name` | `string` |  | the platform's display name, used if the user row has none |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 414 | `messagingKnownPersonByEmail` | read [users](/code/tables/users.md) |  |

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [resolveSlackRequester()](/code/functions/resolveSlackRequester.md), [resolveDirectoryRequester()](/code/functions/resolveDirectoryRequester.md)

