# includes/db_verify_column_parse.php

> PHP - 172 lines

Column drift self-check — the sibling of db_verify_index_parse.php.

The schema has TWO hand-maintained sources of truth and they must agree:

  database/freeitsm.sql        what a FRESH install is built from
  includes/db_verify_schema.php   what Database Verification ALTERs an
                                  EXISTING install up to

Indexes already have a guard, but they're the easy case: the index list is
GENERATED from freeitsm.sql, so drift only ever means "you forgot to
regenerate". Columns are harder — both files are written by hand, so they can
disagree in either direction, and each direction breaks a different install:

  in db_verify but NOT freeitsm.sql → a NEW install is missing the column
                                      until someone runs Verification
  in freeitsm.sql but NOT db_verify → an EXISTING install never gains it

The first of those shipped as a real bug: `asset_locations.tenant_id` was
added to Verification but not to freeitsm.sql, so a fresh install fell over on
the asset-locations screen while every upgraded install was fine.

⚠️ WHY THIS IS EASY TO MISS: while developing you only ever exercise the
UPGRADE path. You run Database Verification, it goes green, everything works.
The fresh-install path is invisible to you until a stranger downloads the
project — and then it's the ONLY path they take. Discipline can't close that;
a check that runs on every Verification can.

Mirrors `capSelfCheck()` and the settings-manifest self-check: the source of
truth is code, and the app proves the mirror matches rather than trusting a
human to have kept it so.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [dbVerifyParseColumnsFromSql()](/code/functions/dbVerifyParseColumnsFromSql.md) | 45 | Extract the declared COLUMNS of every table in freeitsm.sql: ['table' => ['column' => 'NORMALISED TYPE', ...], ...] |
| [dbVerifyNormaliseColumnType()](/code/functions/dbVerifyNormaliseColumnType.md) | 83 | Reduce a column definition to a comparable shape. |
| [dbVerifyColumnSelfCheck()](/code/functions/dbVerifyColumnSelfCheck.md) | 110 | Compare includes/db_verify_schema.php against a fresh parse of freeitsm.sql. Returns human-readable problem strings; empty means they're in sync. |

## How it hangs together

**Included by:** [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)

