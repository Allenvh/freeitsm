# portal_user_preferences

The same idea for SELF-SERVICE PORTAL users, who have no analyst row and so cannot use the table above. The portal's colour palette was given its own column on `users` when this gap first appeared; this is the generic twin, so the next portal preference does not become a third column.  🔴 Everything that reads it degrades when it is absent (see includes/portal_preferences.php): the code ships before an operator runs Database Verification, so on that install this table does not exist yet.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `user_id` | `INT NOT NULL` |  |
| `preference_key` | `VARCHAR(100) NOT NULL` |  |
| `preference_value` | `TEXT NULL` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/portal_preferences.php](/code/files/includes/portal_preferences.php.md):68 | `portalPrefSet` | update | `preference_value`, `updated_datetime` |  |
| [includes/portal_preferences.php](/code/files/includes/portal_preferences.php.md):72 | `portalPrefSet` | insert | `user_id`, `preference_key`, `preference_value`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/portal_preferences.php](/code/files/includes/portal_preferences.php.md) | portalPrefGet L33, portalPrefSet L63 |  |

