# entityRecentTrail()

The analyst's trail, resolved and grouped into level-1 headings.

🔴 FILTER FIRST, GROUP SECOND, and the order matters for two separate reasons:

  1. A heading whose every record has been deleted or put out of reach must
     not render at all. Grouping first and filtering after would leave a dated
     "Assets" heading with nothing under it — which is both ugly and a small
     confession that SOMETHING was there.
  2. When a record in the middle of a run drops out, the runs either side of it
     are the same module and correctly merge into one. Grouping first would
     leave a seam where a now-invisible record used to be.

```php
function entityRecentTrail(PDO $conn, int $analystId): array
```

Defined in [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), line 286.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array<int,array> Newest group first.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 293 | `entityRecentTrail` | read [analyst_recent_trail](/code/tables/analyst_recent_trail.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md), [recentTrailLabels()](/code/functions/recentTrailLabels.md), [recentTrailWithinGap()](/code/functions/recentTrailWithinGap.md)

## Called from

- [api/system/recent_trail.php](/code/files/api/system/recent_trail.php.md)

