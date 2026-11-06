# rpShareMatchSql()

SQL that matches the shares reaching this viewer, for `report_pack_shares s`.

```php
function rpShareMatchSql(array $viewer): array
```

Defined in [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), line 72.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$viewer` | `array` |  | the viewer from `rpViewer()`: id, team_ids and department (already normalised) |

**Returns:** array{0:string,1:array}

## Called from

- [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md) - in [rpRole()](/code/functions/rpRole.md), [rpListPacks()](/code/functions/rpListPacks.md)

