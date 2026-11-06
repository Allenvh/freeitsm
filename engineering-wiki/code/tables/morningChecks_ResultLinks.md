# morningChecks_ResultLinks

Tickets/tasks raised from a check. A link table rather than columns on the result, because one bad check can raise several things.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `LinkID` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ResultID` | `INT NOT NULL` |  |
| `EntityType` | `VARCHAR(20) NOT NULL` |  |
| `EntityID` | `INT NOT NULL` |  |
| `EntityRef` | `VARCHAR(100) NULL` |  |
| `CreatedByID` | `INT NULL` |  |
| `CreatedDate` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

*No SQL in the scanned code names this table directly.*
