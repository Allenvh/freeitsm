# countVersionsInChain()

Walk back to the root of the chain, then count members. Capped to avoid pathological loops.

```php
function countVersionsInChain($conn, $leafId)
```

Defined in [api/network-mapper/list_diagrams.php](/code/files/api/network-mapper/list_diagrams.php.md), line 70.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$leafId` |  |  | the network_diagrams id of any version in the chain, usually the newest |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 75 | `countVersionsInChain` | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 90 | `countVersionsInChain` | read [network_diagrams](/code/tables/network_diagrams.md) |  |

## Called from

- [api/network-mapper/list_diagrams.php](/code/files/api/network-mapper/list_diagrams.php.md)

