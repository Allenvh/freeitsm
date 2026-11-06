# tickets/csat/survey.php

> PHP - 331 lines

Public CSAT survey landing page.

NO authentication — the URL itself is the credential. The token is matched
against ticket_csat_responses.token; an invalid or already-responded token
shows a friendly error rather than leaking which is which (timing-safe).

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md), read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |
| 65 |  | **update** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) (`rating`, `comment`, `responded_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/csat.php](/code/files/includes/csat.php.md), [includes/i18n_guarded.php](/code/files/includes/i18n_guarded.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [csatGetSetting()](/code/functions/csatGetSetting.md), [i18nGuardedInit()](/code/functions/i18nGuardedInit.md), [trh()](/code/functions/trh.md), [trj()](/code/functions/trj.md), [trLocale()](/code/functions/trLocale.md)

