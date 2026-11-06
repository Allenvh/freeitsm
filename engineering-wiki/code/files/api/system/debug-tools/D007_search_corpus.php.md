# api/system/debug-tools/D007_search_corpus.php

> API endpoint - 309 lines

D007 — Search corpus health.

Full-text search depends on things that fail SILENTLY. MySQL has a handful of
server settings that decide which words it will even remember, and none of
them produce an error when they are wrong — searches simply come back empty
and everyone assumes the feature is broken. The worst offender is
innodb_ft_max_token_size: leave it at a low value and every word longer than
that is quietly ignored, so "authentication" is unfindable while "printer"
works perfectly.

Nobody should have to hold that in their head. This tool looks at the whole
chain in one place — the table, its indexes, the foreign key, the server
settings, and a real search performed live — and ends in a plain-English
verdict with the fix.

⚠️ It writes ONE probe row and deletes it. That is deliberate and unavoidable:
InnoDB does not expose rows inserted in an uncommitted transaction to
MATCH...AGAINST (the full-text cache is flushed at commit), so a search cannot
be proven from inside a rolled-back transaction. The row uses a reserved
source_type that nothing else writes, and is removed in a finally block.

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D007_search_corpus.php` |
| Protected by | Signed-in analyst (session check) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 57 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [yn()](/code/functions/yn.md) | 66 | Formats a boolean as YES or NO for the report. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 73 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

