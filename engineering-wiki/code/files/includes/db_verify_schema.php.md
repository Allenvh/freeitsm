# includes/db_verify_schema.php

> PHP - 5837 lines

The expected COLUMNS of every table, table => [column => definition].

Database Verification creates any missing table from this and ALTERs in any
missing column. It is one of the schema's two hand-maintained sources of
truth — `database/freeitsm.sql` (used by a FRESH install) is the other, and
the two must agree.

⚠️ They can silently disagree, and that has shipped a real bug: a column here
but not in freeitsm.sql leaves a NEW install missing it until someone runs
Verification, while a column in freeitsm.sql but not here means an EXISTING
install never gains it. `dbVerifyColumnSelfCheck()` (includes/db_verify_column_parse.php)
compares the two on every Verification run and raises a red card on drift.

Lives in its own file — rather than inline in db_verify.php — precisely so the
guard, and any future tooling, can `require` it. Same reasoning as
includes/db_verify_indexes.php.

NOTE: this array carries COLUMNS (+ PRIMARY KEY) only. UNIQUE keys, other
indexes and FOREIGN KEYs are NOT built from it — indexes come from the
generated includes/db_verify_indexes.php, and FKs from the explicit FK groups
in db_verify.php.

## How it hangs together

**Included by:** [api/system/db_verify.php](/code/files/api/system/db_verify.php.md), [api/system/db_verify_preview.php](/code/files/api/system/db_verify_preview.php.md)

## Traps

> [!WARNING]
> **Line 1434:** keep the column DEFAULT 0. Adding it gives every EXISTING link 0, so nothing attached before 3.7.0 goes public on upgrade; new article links are written with 1 by `documentLinkHelpCentreDefault()`.

