# carddav_write_log

Every attempt to write a contact back to an address book — succeeded, refused, or failed.  🔴 THE ATTEMPT, NOT THE SUCCESS. An import records its runs and every person it touched; write-back, which is the half that changes SOMEBODY ELSE'S data, recorded nothing at all until this table. When an operator says "it isn't writing", the difference between "we never tried", "we tried and the server said no", and "we refused on purpose because their copy had changed" is the whole of the diagnosis, and none of it was recoverable afterwards.  ⚠️ Deliberately NOT folded into `directory_sync_runs`. A write-back is not a run: it is one contact, triggered by one person saving one form, and giving it a synthetic run per save would make the import history unreadable.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `provider_id` | `INT NOT NULL` |  |
| `user_id` | `INT NULL` |  |
| `display_name` | `VARCHAR(255) NULL` | denormalised: still answerable after a delete |
| `outcome` | `VARCHAR(12) NOT NULL` | ok \| conflict \| failed \| skipped. `conflict` is kept distinct from `failed` for the same reason the import keeps 'stopped' apart from 'failed' — refusing to overwrite somebody is the feature working. |
| `fields` | `VARCHAR(255) NULL` | which ones were written, comma-separated |
| `http_status` | `INT NULL` |  |
| `server_response` | `TEXT NULL` | 🔑 The server's OWN words, kept verbatim and truncated rather than summarised. A tidied message is the one thing that cannot be used to diagnose a server nobody here can log in to. |
| `message` | `VARCHAR(1000) NULL` | what FreeITSM concluded, in plain English |
| `triggered_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/delete_sso_provider.php](/code/files/api/system/delete_sso_provider.php.md):44 |  | delete |  |  |
| [includes/carddav_write.php](/code/files/includes/carddav_write.php.md):616 | `cardDavLogWrite` | insert | `provider_id`, `user_id`, `display_name`, `outcome`, `fields`, `http_status`, `server_response`, `message`, `triggered_by_analyst_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md) | L251, L256 |  |
| [api/system/get_carddav_write_log.php](/code/files/api/system/get_carddav_write_log.php.md) | L50, L72 |  |

