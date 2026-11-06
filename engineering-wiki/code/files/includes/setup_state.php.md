# includes/setup_state.php

> PHP - 46 lines

Has this install ever been provisioned?

setup/index.php and api/system/db_verify.php both have to answer that before
letting an anonymous visitor near schema changes, because there is a genuine
bootstrap problem: on a brand-new install no analyst exists yet, so the
endpoint that BUILDS the table they will log in against cannot itself
require a login.

That used to be solved with a session flag which setup/index.php handed to
anyone who loaded the page:

    `$_SESSION`['setup_access'] = true;      // no authentication of any kind

so `GET /setup/` followed by `POST /api/system/db_verify.php` on the same
cookie ran migrations against a live database with no credentials at any
point, and the flag was never cleared. Reported privately against f7f1e9dd.

The flag is gone. Both callers now ask the database the real question, so
there is no longer a token to forge — the bootstrap path exists only while
the condition that justifies it is actually true.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [installIsUnprovisioned()](/code/functions/installIsUnprovisioned.md) | 38 | True only when no analyst account exists, i.e. nobody can possibly log in and an unauthenticated bootstrap is the only way to build the schema. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 | `installIsUnprovisioned` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/db_errors.php](/code/files/includes/db_errors.php.md)

**Included by:** [api/system/db_verify.php](/code/files/api/system/db_verify.php.md), [api/system/db_verify_preview.php](/code/files/api/system/db_verify_preview.php.md), [setup/index.php](/code/files/setup/index.php.md)

**Calls:** [dbErrorIsMissingTable()](/code/functions/dbErrorIsMissingTable.md)

