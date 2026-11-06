# feature_bingo_dismissed

The self-service portal's bell (discussion #62) - `notifications` for portal users. A sibling table rather than a column: see database/freeitsm.sql. Feature Bingo: cards marked "Not for us" (the cards are code).

## Columns

| Column | Definition | Notes |
|---|---|---|
| `card_id` | `VARCHAR(100) NOT NULL` |  |
| `dismissed_by_analyst_id` | `INT NULL` |  |
| `dismissed_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/feature_bingo.php](/code/files/api/system/feature_bingo.php.md):56 |  | insert | `card_id`, `dismissed_by_analyst_id`, `dismissed_datetime` |  |
| [api/system/feature_bingo.php](/code/files/api/system/feature_bingo.php.md):60 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md) | featureBingoDismissed L230, featureBingoReady L260 |  |

