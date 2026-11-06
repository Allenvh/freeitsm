# api_keys

REST API v1 keys (System > API) — distinct from the legacy `apikeys` table above (api/external ingest): stored hashed, granular permission map (JSON), optional company scope, acts as an analyst.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `key_prefix` | `VARCHAR(16) NOT NULL` |  |
| `key_hash` | `CHAR(64) NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `permissions` | `LONGTEXT NULL` |  |
| `company_ids` | `TEXT NULL` |  |
| `rate_limit_per_minute` | `INT NULL` |  |
| `active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `expires_at` | `DATETIME NULL` |  |
| `last_used_at` | `DATETIME NULL` |  |
| `last_used_ip` | `VARCHAR(45) NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/api_keys/create_key.php](/code/files/api/system/api_keys/create_key.php.md):83 |  | insert | `name`, `key_prefix`, `key_hash`, `analyst_id`, `permissions`, `company_ids`, `rate_limit_per_minute`, `active`, `expires_at`, `created_by` |  |
| [api/system/api_keys/delete_key.php](/code/files/api/system/api_keys/delete_key.php.md):25 |  | delete |  |  |
| [api/system/api_keys/update_key.php](/code/files/api/system/api_keys/update_key.php.md):117 |  | update |  |  |
| [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md):56 | `apiAuthenticate` | update | `last_used_at`, `last_used_ip` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/api_keys/list_keys.php](/code/files/api/system/api_keys/list_keys.php.md) | L27 |  |
| [api/system/api_keys/update_key.php](/code/files/api/system/api_keys/update_key.php.md) | L31, L84 |  |
| [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) | apiAuthenticate L28 |  |

