# includes/storage_persistence.php

> PHP - 401 lines

Storage persistence — does what FreeITSM writes actually survive an update?

⚠️ THE PROBLEM THIS EXISTS FOR (issue #109, and the tail of #102).

Under Docker, `docker compose up -d --build` does not update the running
container. It builds a new image and REPLACES the container, discarding
everything written inside it. Only volumes — storage that lives outside the
container — are carried across.

So a directory that is not on a volume is emptied every time the operator
updates. And the way that surfaces is unusually cruel: the database IS on a
volume, so it survives intact. What is left is a complete and correct set of
records pointing at files that no longer exist, and the honest message for
that state — "recorded but missing from storage" — reads to the operator as
data corruption rather than as a missing mount.

🔑 THE ONLY MOMENT ANYTHING CAN BE DONE ABOUT IT IS BEFORE THE REBUILD. Once
the container is replaced the old one is removed and its writable layer goes
with it; the files are not recoverable from Docker by any route. That is why
this check exists at all: an after-the-fact explanation is worth very little,
and a warning in documentation only reaches people who go looking. This one
reaches them where they already are.

⚠️ THE RULE IS "IS IT ON THE ROOT FILESYSTEM", NOT "IS IT A MOUNT POINT".

The obvious test — does this directory's device differ from its PARENT's,
i.e. is it a mount point — raises a false alarm on a perfectly safe and
reasonably common setup. Somebody who bind-mounts the whole application
directory has every one of these folders on the host, entirely safe, and not
one of them is a mount point in its own right. Telling that operator their
data is about to be destroyed would be worse than saying nothing, because a
check that cries wolf is switched off and then never believed again.

Comparing against the device of "/" instead answers the question actually
being asked: is this on the container's own writable layer, which is thrown
away, or on something else, which is not. A directory inside a mounted parent
is correctly reported as safe. Verified against four cases — named volume,
bind mount, directory inside a mounted parent, and nothing mounted at all.

⚠️ NEVER GUESS "AT RISK". Every uncertainty resolves to 'unknown', which the
callers report as an unknown rather than folding into the warning count. A
container is the only place this can be answered, and even there `stat()` can
fail; an alarm raised on a failed `stat()` is an alarm about nothing.

🔑 THE DIRECTORY LIST BELOW IS THE SAME LIST AS THE ONE IN .gitignore — the
"Upload directories" and "Module-specific runtime content" blocks. It is
maintained by hand in both places, which is precisely the shape of failure
that caused #109: the deployment recipe belongs to no module, so nobody
updating a module thinks to revisit it. ⚠️ ADD A DIRECTORY HERE WHENEVER YOU
ADD ONE THERE.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [storagePersistenceInContainer()](/code/functions/storagePersistenceInContainer.md) | 117 | Is this PHP running inside a container? |
| [storagePersistenceRootReadable()](/code/functions/storagePersistenceRootReadable.md) | 135 | Would `open_basedir` stop us reading /.dockerenv? |
| [storagePersistenceDirectories()](/code/functions/storagePersistenceDirectories.md) | 153 | Every directory the running application writes user files into. |
| [storagePersistenceStatus()](/code/functions/storagePersistenceStatus.md) | 200 | Assess one directory. Returns 'persisted' \| 'at_risk' \| 'missing' \| 'unknown'. |
| [storagePersistenceReport()](/code/functions/storagePersistenceReport.md) | 229 | The whole picture, for whichever screen is asking. |
| [storagePersistenceHasFiles()](/code/functions/storagePersistenceHasFiles.md) | 289 | Does this directory hold anything an operator would mind losing? |
| [storagePersistenceAnythingToLose()](/code/functions/storagePersistenceAnythingToLose.md) | 318 | Is anything actually stored in the directories that are at risk? |
| [storagePersistenceSuggestedVolumes()](/code/functions/storagePersistenceSuggestedVolumes.md) | 336 | The volume lines an operator would need to add, ready to paste. |
| [storagePersistenceVolumeName()](/code/functions/storagePersistenceVolumeName.md) | 353 | A volume name derived from the directory, stable and readable. |
| [storagePersistenceOverrideFile()](/code/functions/storagePersistenceOverrideFile.md) | 383 | A COMPLETE docker-compose.override.yml, ready to save as a new file. |

## How it hangs together

**Included by:** [api/system/debug-tools/D013_storage_persistence.php](/code/files/api/system/debug-tools/D013_storage_persistence.php.md), [api/system/debug-tools/D017_config_completeness.php](/code/files/api/system/debug-tools/D017_config_completeness.php.md), [api/system/docker_tls.php](/code/files/api/system/docker_tls.php.md), [setup/index.php](/code/files/setup/index.php.md), [system/docker/index.php](/code/files/system/docker/index.php.md), [system/index.php](/code/files/system/index.php.md)

