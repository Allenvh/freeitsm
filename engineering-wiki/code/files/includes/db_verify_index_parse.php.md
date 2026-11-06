# includes/db_verify_index_parse.php

> PHP - 114 lines

Shared parser + drift self-check for the index backfill list.

`database/freeitsm.sql` is the source of truth for the schema;
`includes/db_verify_indexes.php` is a GENERATED mirror of its indexes that
Database Verification uses to restore missing ones. The risk is the classic
one: someone adds an index to freeitsm.sql and forgets to regenerate, so the
mirror goes stale and grown installs silently miss the new index — the very
drift the backfill was built to end.

These two functions close that loop. ONE parser produces the list (via
scripts/gen_db_verify_indexes.php) and audits it (via the self-check that
db_verify runs on every pass), so the generator and the checker can never
disagree. Mirrors the `capSelfCheck()` / settings-manifest self-check pattern.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [dbVerifyParseIndexesFromSql()](/code/functions/dbVerifyParseIndexesFromSql.md) | 30 | Extract every named secondary index from freeitsm.sql text: [table, name, type, columns]. Skips PRIMARY KEY, FOREIGN KEY, CONSTRAINT. |
| [dbVerifyIndexTypeOf()](/code/functions/dbVerifyIndexTypeOf.md) | 59 | Normalise an index type from either list format. |
| [dbVerifyIndexListSelfCheck()](/code/functions/dbVerifyIndexListSelfCheck.md) | 75 | Compare the committed generated list against a fresh parse of freeitsm.sql. Returns human-readable problem strings; an empty array means they're in sync. |

## How it hangs together

**Included by:** [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)

