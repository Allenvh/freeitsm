# analyst_recent_trail

The recent trail (#124): every record an analyst has opened, in order, so the waffle drawer can draw an outline of how they got where they are. See includes/recent_trail.php for what this is and why it takes this shape.  🔴 A LOG, NOT A "RECENTS" LIST — so deliberately NO unique key on (analyst_id, entity_type, entity_id). The same ticket opened this morning and again this afternoon MUST be two rows, under two different headings, or the trail stops describing the navigation it exists to describe. The cap and the age-out in `recentTrailPrune()` do the job the unique key would.  ⚠️ entity_type is the ENTITY vocabulary of `entityLinkTypes()`, not a module key — 'knowledge_article', not 'knowledge'. The module is derived from it at render time by RECENT_TRAIL_MODULES, so re-homing a record type is one edit in one file rather than a data migration.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `entity_type` | `VARCHAR(40) NOT NULL` |  |
| `entity_id` | `INT NOT NULL` |  |
| `visited_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md):192 | `recentTrailWrite` | update | `visited_datetime` |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md):203 | `recentTrailWrite` | insert | `analyst_id`, `entity_type`, `entity_id`, `visited_datetime` |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md):249 | `recentTrailPrune` | delete |  |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md):259 | `recentTrailPrune` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailWrite L181, entityRecentTrail L293 |  |

