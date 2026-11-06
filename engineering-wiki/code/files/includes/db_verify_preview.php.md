# includes/db_verify_preview.php

> PHP - 240 lines

Database Verification — READ-ONLY preview of what a run would change.

── Why this exists (F10) ────────────────────────────────────────────────────

db_verify.php's own header said it "is idempotent and never drops anything".
That was not true and had not been for some time: the file contains DROP COLUMN
for six columns, plus DROP INDEX and DROP FOREIGN KEY. An administrator reading
the header would reasonably press the button without taking a backup, which is
exactly the decision the sentence was influencing. The comment is now corrected,
and this file exists so the answer to "what will this do to my database?" is
something you can read BEFORE it happens rather than in the results afterwards.

── What this is NOT ─────────────────────────────────────────────────────────

⚠️ This is not the migration running with a flag turned off, and it is important
not to describe it as one. A true statement-level dry run would mean threading a
`$dryRun` through roughly two hundred `exec()` calls in a 2,300-line procedural
script, and MySQL cannot help: DDL causes an implicit COMMIT, so the usual trick
of running it inside a transaction and rolling back does not work for schema
changes. Attempting it would put a new conditional in front of every migration in
the app — the single most dangerous refactor available in this codebase — to make
a preview slightly more precise.

Instead this INSPECTS the database and reports the differences it can see. It
opens no transaction, writes nothing, and calls none of db_verify's migration
code, so it cannot itself break anything.

The consequence, stated plainly rather than buried: the destructive list below is
MAINTAINED BY HAND. If someone adds a DROP to db_verify.php and does not add it
here, the preview will under-report. DB_VERIFY_DESTRUCTIVE is the register of
that promise, and the test suite asserts the two files agree.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [dbPreviewTableExists()](/code/functions/dbPreviewTableExists.md) | 78 | Does this table exist? |
| [dbPreviewColumnExists()](/code/functions/dbPreviewColumnExists.md) | 92 | Does this column exist? |
| [dbPreviewIndexExists()](/code/functions/dbPreviewIndexExists.md) | 106 | Does this index exist? |
| [dbPreviewRepairs()](/code/functions/dbPreviewRepairs.md) | 137 | Row repairs db_verify.php would carry out, counted against this database. |
| [dbVerifyPreview()](/code/functions/dbVerifyPreview.md) | 176 | Works out, without changing anything, what a Database Verification run would do - tables to create, columns to add, drops whose target is present, and data repairs with rows to rewrite. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 148 | `dbPreviewRepairs` | read [assets](/code/tables/assets.md), read [asset_history](/code/tables/asset_history.md) |  |

## How it hangs together

**Included by:** [api/system/db_verify_preview.php](/code/files/api/system/db_verify_preview.php.md)

