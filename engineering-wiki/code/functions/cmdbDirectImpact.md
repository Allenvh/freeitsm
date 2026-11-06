# cmdbDirectImpact()

The three one-hop buckets. Shape is a published API contract — add keys here, never rename or remove them.

```php
function cmdbDirectImpact(PDO $conn, int $id): array
```

Defined in [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md), line 126.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | cmdb_objects.id to look one hop out from |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 133 | `cmdbDirectImpact` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 157 | `cmdbDirectImpact` | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 173 | `cmdbDirectImpact` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |

## Called from

- [api/cmdb/get_object_impact.php](/code/files/api/cmdb/get_object_impact.php.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectImpact()](/code/functions/apiCmdbObjectImpact.md)

