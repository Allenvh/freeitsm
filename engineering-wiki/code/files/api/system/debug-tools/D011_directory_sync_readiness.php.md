# api/system/debug-tools/D011_directory_sync_readiness.php

> API endpoint - 635 lines

Debug Tool D011 — Why can't this sign-in method import people?

D010 asks "can anybody sign in with this?". This asks a different question of
the same rows: "could this bring people in from the directory, and if not,
what exactly is in the way?"

They are genuinely separate. A directory can be set up purely as a source of
people — so staff exist to be issued equipment — with single sign-on switched
off entirely. Nothing in the import path consults the global SSO switch. The
two get confused because both live on a screen called Authentication, under a
heading called Sign-in methods.

The tool walks the gates in the order the code actually applies them and names
THE blocker rather than listing everything that happens to be unset. It also
reports the one disagreement in the codebase worth knowing about: running an
import from the UI ignores the provider's Enabled flag, and the scheduled run
does not — so a directory that imports perfectly when you press the button can
be silently skipped every night.

Read-only, and it does NOT contact the directory: this is about configuration,
not reachability, and a tool that hangs on an unreachable host answers nothing.
Every DN, bind account and filter value is masked so the report can be sent on.

Output: plain text, section-delimited with === HEADERS ===.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D011_directory_sync_readiness.php` |
| Protected by | Signed-in analyst (session check) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 61 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [yn()](/code/functions/yn.md) | 70 | Formats a boolean as YES or NO for the report. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 77 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |
| [mask()](/code/functions/mask.md) | 89 | Masks a value for a report that will be sent on - all stars when very short, otherwise the first one or two characters (and the last, for a long value) with stars between. |
| [maskDn()](/code/functions/maskDn.md) | 106 | Keep the attribute NAMES and the shape of the tree; mask every value. |
| [maskDnList()](/code/functions/maskDnList.md) | 123 | Masks a newline-separated list of DNs, one `maskDn()` per line - ['(none)'] when it is empty. |
| [colExists()](/code/functions/colExists.md) | 161 | Whether a column exists in this database - false on any error, so an older install reads as not ready rather than breaking the report. |
| [tableExists()](/code/functions/tableExists.md) | 176 | Whether a table exists in this database - false on any error, so an older install reads as not ready rather than breaking the report. |
| [storedValue()](/code/functions/storedValue.md) | 318 | Describe what is actually stored, keeping the three "blank" cases apart: the column is absent, the value is NULL, or the value is an empty string. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 362 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 410 |  | **update** [auth_providers](/code/tables/auth_providers.md) (`protocol`) |  |
| 559 |  | read [directory_sync_runs](/code/tables/directory_sync_runs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/directory_sync.php](/code/files/includes/directory_sync.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [dsyncAttr()](/code/functions/dsyncAttr.md), [dsyncFlavour()](/code/functions/dsyncFlavour.md), [dsyncScopes()](/code/functions/dsyncScopes.md)

