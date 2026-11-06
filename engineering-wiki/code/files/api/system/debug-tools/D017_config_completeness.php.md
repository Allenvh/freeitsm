# api/system/debug-tools/D017_config_completeness.php

> API endpoint - 166 lines

Debug Tool D017 — is anything missing from config.php?

config.php is the operator's own file. It is edited once at install and kept,
so an upgrade never adds a line to it (GH #129). This tool compares the copy
this server is actually running with the list of what config.php is expected
to define, and says - per setting - whether it is there, whether the app has
a built-in default for it, and the line to add if you want to set it.

🔑 THE LIST IS includes/config_requirements.php, NOT A SECOND COPY OF A
TEMPLATE. It does not diff against docker/config.php (which defines database
credentials a hand install must not add). tests/config-not-load-bearing.php
holds the list to both shipped templates, so it cannot drift.

🔴 IT NEVER PRINTS A VALUE. Names and present/absent only - the database
password is defined by these same files. The config file is read with the
tokeniser for the NAMES it declares; nothing it assigns is echoed.

READ-ONLY. It reads config.php's text and checks which constants exist. It
writes nothing and touches no database row (beyond the administrator check).

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D017_config_completeness.php` |
| Protected by | Signed-in analyst (session check) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 58 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 68 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/config_requirements.php](/code/files/includes/config_requirements.php.md), [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [configRequirements()](/code/functions/configRequirements.md), [configRequirementsReport()](/code/functions/configRequirementsReport.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [storagePersistenceInContainer()](/code/functions/storagePersistenceInContainer.md)

