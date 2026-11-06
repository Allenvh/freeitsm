# morningChecks_Groups

## Columns

| Column | Definition | Notes |
|---|---|---|
| `GroupID` | `INT NOT NULL AUTO_INCREMENT` |  |
| `GroupName` | `VARCHAR(255) NOT NULL` |  |
| `GroupDescription` | `LONGTEXT NULL` |  |
| `AssignedTeamID` | `INT NULL` |  |
| `AssignedAnalystID` | `INT NULL` |  |
| `IsActive` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `SortOrder` | `INT NOT NULL DEFAULT 0` |  |
| `CreatedDate` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `ModifiedDate` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

*No SQL in the scanned code names this table directly.*
