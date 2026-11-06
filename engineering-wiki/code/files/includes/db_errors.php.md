# includes/db_errors.php

> PHP - 52 lines

Telling one database error from another.

Several places in the app need to tolerate a schema that hasn't caught up
yet — a table Database Verify hasn't created, a column an older install is
still missing — while NOT tolerating anything else. Written as a bare
`catch (Exception `$e`)` that assumption quietly widens: a lock-wait timeout,
a dropped connection or a permissions error all look identical to "not
migrated yet", and whatever the catch block does for a fresh install it now
also does during a database hiccup on a live one.

That is fail-open by accident, and it is how includes/tenancy.php came to
grant cross-tenant access on any transient error. These helpers exist so a
catch block can say precisely which error it is willing to absorb.

Codes are MySQL's, matched on SQLSTATE first (portable, and always present
on a PDOException) with the driver-specific code as a second opinion.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [dbErrorIsMissingTable()](/code/functions/dbErrorIsMissingTable.md) | 26 | "That table doesn't exist." MySQL: SQLSTATE 42S02, driver code 1146. |
| [dbErrorIsUnknownColumn()](/code/functions/dbErrorIsUnknownColumn.md) | 37 | "That column doesn't exist." MySQL: SQLSTATE 42S22, driver code 1054. |
| [dbErrorIsMissingSchema()](/code/functions/dbErrorIsMissingSchema.md) | 48 | Either of the above — the two shapes a part-migrated install actually takes. |

## How it hangs together

**Included by:** [api/system/db_verify.php](/code/files/api/system/db_verify.php.md), [includes/setup_state.php](/code/files/includes/setup_state.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

