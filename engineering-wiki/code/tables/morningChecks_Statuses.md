# morningChecks_Statuses

Configurable status options for morning checks. Drives the status buttons on the dashboard (label + colour) and whether picking a status pops the notes modal (RequiresNotes).

## Columns

| Column | Definition | Notes |
|---|---|---|
| `StatusID` | `INT NOT NULL AUTO_INCREMENT` |  |
| `Label` | `VARCHAR(50) NOT NULL` |  |
| `Colour` | `VARCHAR(20) NOT NULL` |  |
| `RequiresNotes` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `SortOrder` | `INT NOT NULL DEFAULT 0` |  |
| `IsActive` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `CreatedDate` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `ModifiedDate` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

*No SQL in the scanned code names this table directly.*
