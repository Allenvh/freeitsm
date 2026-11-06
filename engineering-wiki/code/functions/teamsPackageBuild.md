# teamsPackageBuild()

Write the package for `$channel` to `$outPath`. Returns the zip's file name. Throws with a message the admin can act on when the channel is not ready.

```php
function teamsPackageBuild(PDO $conn, array $channel, string $outPath): string
```

Defined in [includes/messaging/TeamsPackage.php](/code/files/includes/messaging/TeamsPackage.php.md), line 20.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the Teams messaging_channels row, decrypted (credentials.app_id, name) |
| `$outPath` | `string` |  | where to write the zip file; overwritten if it exists |

**Returns:** `string`

## Calls

[messagingPublicBaseUrl()](/code/functions/messagingPublicBaseUrl.md), [teamsZipStore()](/code/functions/teamsZipStore.md)

## Called from

- [api/messaging/teams_package.php](/code/files/api/messaging/teams_package.php.md)

