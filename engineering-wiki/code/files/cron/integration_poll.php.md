# cron/integration_poll.php

> PHP - 168 lines

External issue tracker poll — cron entry point.

Refreshes the cached status of every linked issue, so the pill on a ticket
says what the tracker actually says. Without this, a link's status is frozen
at whatever it was when the issue was raised.

⚠️ THIS IS THE ONLY WAY STATUS EVER CHANGES TODAY. Webhooks are V2. An install
that never schedules this will show issues as "To Do" forever, which is why
the settings screen should say so and why this file logs loudly when it has
nothing to do.

Each connection has its own `poll_interval_minutes`, honoured here, so a busy
tracker can be checked often and a quiet one rarely without a global setting.

SECURITY (HTTP invocation only), mirroring cron/webhook_deliveries.php:
  - Shared-secret token via ?token=<value> matching `integration_cron_token`
    in system_settings (hash_equals; seeded by Database Verification).
  - Min interval between runs (`integration_cron_min_interval_seconds`,
    default 60s) for CLI + HTTP, defeating double-scheduling.
CLI invocation skips the token (there is no untrusted caller).

  php cron/integration_poll.php
  curl "https://your-install/cron/integration_poll.php?token=…"

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 87 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 97 |  | read [integration_connections](/code/tables/integration_connections.md) |  |
| 150 |  | **update** [integration_links](/code/tables/integration_links.md) (`last_error`) |  |
| 157 |  | **update** [integration_connections](/code/tables/integration_connections.md) (`last_poll_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md), [integrationsPullComments()](/code/functions/integrationsPullComments.md), [integrationsRefreshConnection()](/code/functions/integrationsRefreshConnection.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md)

