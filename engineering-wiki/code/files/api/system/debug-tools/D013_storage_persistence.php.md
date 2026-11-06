# api/system/debug-tools/D013_storage_persistence.php

> API endpoint - 242 lines

Debug Tool D013 — will the files this server holds survive its next update?

Answers one question, and it is a question that can only usefully be asked
BEFORE the damage: "of the directories FreeITSM writes user files into, which
are on storage that outlives the container, and which are on storage that is
thrown away the next time somebody rebuilds?"

⚠️ THIS IS THE ONE DIAGNOSTIC THAT IS WORTH RUNNING WHEN NOTHING IS WRONG.
Every other tool here explains a failure that has already happened. Under
Docker the failure this one describes is unrecoverable once it occurs: the
rebuild removes the old container, its writable layer goes with it, and no
`docker cp` or image archaeology gets the files back. D012 will tell you
afterwards that the files are gone. Only this one can tell you beforehand.

🔑 IT SHARES ITS RULE AND ITS DIRECTORY LIST WITH THE SYSTEM BANNER AND THE
SETUP SCREEN — includes/storage_persistence.php. Three screens asking the
same question must not each carry their own copy of the answer; that is the
shape of drift that produced #109 in the first place, where .gitignore had
the right list all along and docker-compose.yml never consulted it.

⚠️ ON ANYTHING THAT IS NOT A CONTAINER THIS TOOL SAYS SO AND STOPS. On WAMP,
XAMPP or a native LAMP box the question is meaningless — `git pull` does not
delete these folders — and inventing a verdict there would be noise. It says
which case it found rather than printing an empty report.

READ-ONLY. It stats directories. It writes nothing, reads no file contents,
touches no database row and prints no secrets.

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D013_storage_persistence.php` |
| Protected by | Signed-in analyst (session check) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 65 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 75 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [storagePersistenceDirectories()](/code/functions/storagePersistenceDirectories.md), [storagePersistenceInContainer()](/code/functions/storagePersistenceInContainer.md), [storagePersistenceOverrideFile()](/code/functions/storagePersistenceOverrideFile.md), [storagePersistenceReport()](/code/functions/storagePersistenceReport.md), [storagePersistenceRootReadable()](/code/functions/storagePersistenceRootReadable.md), [storagePersistenceSuggestedVolumes()](/code/functions/storagePersistenceSuggestedVolumes.md)

