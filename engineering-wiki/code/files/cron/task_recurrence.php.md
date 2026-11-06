# cron/task_recurrence.php

> PHP - 96 lines

Recurring tasks on a fixed schedule — cron entry point (discussion #94).

Only 'schedule' series need this. A series set to repeat ON COMPLETION makes
its next occurrence the moment somebody finishes the last one, which happens
inside the web request that completed it — nothing has to run in the
background for that to work, and nothing here touches those series.

So an installation that never schedules this cron still gets recurring tasks;
it just does not get the fixed-date kind. That is a deliberate split, because
⚠️ A CRON THAT WAS NEVER SET UP DOES NOT ANNOUNCE ITSELF, and the commonest
outcome of a feature that silently depends on one is a user reporting that it
"doesn't work". System → Debug Tools reports when this last ran.

Missed days are CAUGHT UP, not skipped: a quarterly access review that did not
appear because the server was down is still a review somebody owes. The
catch-up is capped per series per run so a rule left dormant for two years
cannot produce hundreds of tasks in one pass, and creating an occurrence is
idempotent on (series, due date) so overlapping or repeated runs are safe.

SECURITY (HTTP invocation only), mirroring the other crons:
  - Shared-secret token via ?token=<value> matching `workflow_cron_token`.
  - Minimum interval between runs, defeating double-scheduling.
CLI invocation skips the token — there is no untrusted caller.

Run it once a day; more often is harmless.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 81 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md)

