# cmdbImpactEdgesFrom()

One hop out from every id in `$fromIds`, across all three edge kinds.

Batched — one query per edge kind per level rather than per object, so a wide
estate costs a handful of queries rather than hundreds. Each row carries how
it was reached (via_kind / via_label / from_id / from_name) so the panel can
explain the path instead of just listing names.

```php
function cmdbImpactEdgesFrom(PDO $conn, array $fromIds, ?int $tenantScope = null): array
```

Defined in [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md), line 262.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$fromIds` | `array` |  | cmdb_objects ids of the current frontier; cast to int and de-duplicated here |
| `$tenantScope` | `?int` | `null` | company the reached objects must belong to; null means no limit |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 286 | `cmdbImpactEdgesFrom` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 297 | `cmdbImpactEdgesFrom` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 313 | `cmdbImpactEdgesFrom` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 328 | `cmdbImpactEdgesFrom` | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 348 | `cmdbImpactEdgesFrom` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

## Called from

- [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md) - in [cmdbBlastRadius()](/code/functions/cmdbBlastRadius.md)

