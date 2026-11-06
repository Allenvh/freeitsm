# vcdLoadConnection()

One vCloud Director connection row with its password decrypted into 'password'. Throws when the id does not exist.

```php
function vcdLoadConnection(PDO $conn, int $connectionId): array
```

Defined in [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connectionId` | `int` |  | the vcloud_connections id |

**Returns:** `array`

**Reads:** vcloud_connections - host, org, credentials and API version for the calls

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 | `vcdLoadConnection` | read [vcloud_connections](/code/tables/vcloud_connections.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/assets/vcloud_sync.php](/code/files/api/assets/vcloud_sync.php.md)
- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdSyncConnection()](/code/functions/vcdSyncConnection.md)

