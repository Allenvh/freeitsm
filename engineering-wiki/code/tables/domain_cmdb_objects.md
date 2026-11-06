# domain_cmdb_objects

Domains joined to the rest of FreeITSM (3.0.0): four links and the record of the status incidents a domain raised. Keys and FKs are in db_verify_indexes.php and api/system/db_verify.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `domain_id` | `INT NOT NULL` |  |
| `cmdb_object_id` | `INT NOT NULL` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

*No SQL in the scanned code names this table directly.*
