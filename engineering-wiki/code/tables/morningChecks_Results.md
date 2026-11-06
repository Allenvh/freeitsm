# morningChecks_Results

## Columns

| Column | Definition | Notes |
|---|---|---|
| `ResultID` | `INT NOT NULL AUTO_INCREMENT` |  |
| `CheckID` | `INT NOT NULL` |  |
| `CheckDate` | `DATETIME NOT NULL` |  |
| `StatusID` | `INT NULL` | Normalised reference to morningChecks_Statuses.StatusID. NULL is allowed for two cases: (a) pre-#424 rows whose Status label didn't match any seeded status (orphans the admin needs to normalise via Settings); (b) rows whose status was later deleted (FK is ON DELETE SET NULL, with delete_status.php snapshotting the label into Status first so the orphan keeps its label for the normalisation tool). |
| `Status` | `VARCHAR(50) NULL` | Label snapshot — nullable now that StatusID is the source of truth. Holds the original label string for orphan rows so the normalisation tool in Settings can show "you have N results with label X, map them to ...". |
| `Notes` | `LONGTEXT NULL` |  |
| `CreatedBy` | `VARCHAR(100) NULL` |  |
| `ModifiedBy` | `VARCHAR(100) NULL` | Who last set the status (discussion #64) — see freeitsm.sql for why this is not simply CreatedBy being reused. |
| `CreatedDate` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `ModifiedDate` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

*No SQL in the scanned code names this table directly.*
