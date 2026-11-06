# morningChecks_Checks

## Columns

| Column | Definition | Notes |
|---|---|---|
| `CheckID` | `INT NOT NULL AUTO_INCREMENT` |  |
| `CheckName` | `VARCHAR(255) NOT NULL` |  |
| `CheckDescription` | `LONGTEXT NULL` |  |
| `IsActive` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `SortOrder` | `INT NOT NULL DEFAULT 0` |  |
| `GroupID` | `INT NULL` | NULL group = ungrouped, where every existing check starts. |
| `AssignedAnalystID` | `INT NULL` |  |
| `CreatedDate` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `ModifiedDate` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

*No SQL in the scanned code names this table directly.*
