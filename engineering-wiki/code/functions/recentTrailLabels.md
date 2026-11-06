# recentTrailLabels()

Resolve a batch of trail rows to labels, dropping every one the analyst may not see.

⚠️ WHY NOT `recordPreview()`. The preview resolver is the right AUTHORITY and
the wrong SHAPE: it runs a multi-join query per record to fill in a card of
fields, and the drawer needs one line of text. Sixty previews to draw sixty
one-line rows would make opening the drawer noticeably slow, on a control that
exists on all 91 screens. So the LABEL is fetched in one query per type and
the GATE is the very same per-record helper the preview uses — the expensive
half is batched, the authoritative half is not reimplemented.

⚠️ cmdb_object has no preview at all (previews cover seven types, links cover
eight), so there was never a resolver here to reuse for that one.

```php
function recentTrailLabels(PDO $conn, int $analystId, array $rows): array
```

Defined in [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), line 398.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$rows` | `array` |  | analyst_recent_trail rows - entity_type and entity_id are read |

**Returns:** array<string,string> "type:id" => label, for the visible ones only.

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)

## Called from

- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [entityRecentTrail()](/code/functions/entityRecentTrail.md)

