# setup/index.php

> PHP - 747 lines

FreeITSM Setup Verification Checks that the system is correctly configured before going live. DELETE THIS FOLDER once your system is in production.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [setupSafeDbError()](/code/functions/setupSafeDbError.md) | 40 | Keep the diagnostic part of a PDO connection error, drop the reconnaissance. PDO reports e.g. "SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'": the code is what tells an installer what to fix, the tail names the account and host. |

## How it hangs together

**Includes:** [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/setup_state.php](/code/files/includes/setup_state.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [installIsUnprovisioned()](/code/functions/installIsUnprovisioned.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md), [storagePersistenceAnythingToLose()](/code/functions/storagePersistenceAnythingToLose.md), [storagePersistenceOverrideFile()](/code/functions/storagePersistenceOverrideFile.md), [storagePersistenceReport()](/code/functions/storagePersistenceReport.md), [t()](/code/functions/t.md)

