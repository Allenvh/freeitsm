# directory_sync_runs

One row per directory sync RUN — the attempt, not just the success. A sync that did nothing and a sync that never ran are otherwise indistinguishable.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `provider_id` | `INT NOT NULL` |  |
| `mode` | `VARCHAR(10) NOT NULL DEFAULT 'live'` |  |
| `status` | `VARCHAR(12) NOT NULL DEFAULT 'running'` | running \| ok \| stopped \| failed. 'stopped' is the sanity brake: a refusal, not an error, and kept distinct so protecting somebody never reads as breaking. |
| `started_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `finished_datetime` | `DATETIME NULL` |  |
| `seen_count` | `INT NOT NULL DEFAULT 0` |  |
| `created_count` | `INT NOT NULL DEFAULT 0` |  |
| `updated_count` | `INT NOT NULL DEFAULT 0` |  |
| `adopted_count` | `INT NOT NULL DEFAULT 0` |  |
| `deactivated_count` | `INT NOT NULL DEFAULT 0` |  |
| `conflict_count` | `INT NOT NULL DEFAULT 0` |  |
| `error_count` | `INT NOT NULL DEFAULT 0` |  |
| `message` | `TEXT NULL` |  |
| `triggered_by_analyst_id` | `INT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md):355 | `cardDavSyncRun` | insert | `provider_id`, `mode`, `status`, `started_datetime`, `triggered_by_analyst_id` |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):550 | `directorySyncRun` | insert | `provider_id`, `mode`, `status`, `started_datetime`, `triggered_by_analyst_id` |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):1025 | `dsyncFinishRun` | update | `status`, `finished_datetime`, `seen_count`, `created_count`, `updated_count`, `adopted_count`, `deactivated_count`, `conflict_count`, `error_count`, `message` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md) | L559 |  |
| [api/system/get_directory_sync_log.php](/code/files/api/system/get_directory_sync_log.php.md) | L67 |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) | dsyncGetRun L1043 |  |

