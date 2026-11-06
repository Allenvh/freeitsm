# warbotToolImpactOf()

impact_of: the first CMDB object whose name matches, and everything related to it one hop away in either direction, worded with the relationship's verb. Not the full blast radius - that walk stays in the CMDB module.

```php
function warbotToolImpactOf(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 601.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments - untrusted: name (part of a configuration item's name) |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 607 | `warbotToolImpactOf` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 620 | `warbotToolImpactOf` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

*Nothing calls this function by name.*

