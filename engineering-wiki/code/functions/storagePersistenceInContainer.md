# storagePersistenceInContainer()

Is this PHP running inside a container?

Two signals, asked in this order. Either can say YES; only both failing says no.

─────────────────────────────────────────────────────────────────────────────
 1. Our own image says so
─────────────────────────────────────────────────────────────────────────────
The Dockerfile sets FREEITSM_CONTAINER=1, so the image identifies itself
without touching the disk at all. 🔑 THIS IS THE SIGNAL THAT CANNOT BE
SUPPRESSED, and it exists because the one below can be.

⚠️ Read with local_only = true, so the value comes from the real process
environment and not from anything the SAPI folded into `$_SERVER`. A request
header cannot reach it (a header would arrive as HTTP_FREEITSM_CONTAINER),
but reading the process environment directly means not having to reason about
that at all.

⭐ It is also an escape hatch worth having. Podman rebuilds with the same
consequences as Docker and leaves no /.dockerenv, so until now this file had
nothing to say to those operators. Setting the variable opts them in.

─────────────────────────────────────────────────────────────────────────────
 2. /.dockerenv, which the Docker daemon writes into every container
─────────────────────────────────────────────────────────────────────────────
🔴 THE @ IS LOAD-BEARING (GH #127). That file lives at the ROOT of the
filesystem, and a host with `open_basedir` set — shared hosting and control
panels routinely set it — refuses the read and raises a WARNING. FreeITSM
ships with display_errors on, and this runs before the System page emits its
first byte, so the warning was rendered above the whole page. The reporter saw
a wall of red text on an admin screen every time he opened it.

⚠️ The try/catch around the caller does NOT help: a PHP warning is not a
Throwable, so the guard written to "never break the page over a diagnostic"
cannot see this one.

⚠️ AND WHY THE PATH ITSELF MUST NOT MOVE. The obvious repair — point it
somewhere open_basedir allows, such as under DOCUMENT_ROOT — silences the
warning by asking a question with a permanently false answer. Docker writes
/.dockerenv at the filesystem root and nowhere else; nothing puts one in the
web root. Detection would fail for every Docker user and the storage warning
this whole file exists to raise would go quiet for exactly the people it was
built for. It tests clean on any machine that is not running Docker, because
there the correct answer is false too.

─────────────────────────────────────────────────────────────────────────────
Being wrong towards silence is otherwise deliberate: an unrecognised runtime
produces nothing, never a false warning. Nothing in this file should ever have
anything to say to somebody running on WAMP.

```php
function storagePersistenceInContainer(): bool
```

Defined in [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), line 117.

**Returns:** `bool`

## Called from

- [api/system/debug-tools/D013_storage_persistence.php](/code/files/api/system/debug-tools/D013_storage_persistence.php.md)
- [api/system/debug-tools/D017_config_completeness.php](/code/files/api/system/debug-tools/D017_config_completeness.php.md)
- [api/system/docker_tls.php](/code/files/api/system/docker_tls.php.md)
- [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md) - in [storagePersistenceReport()](/code/functions/storagePersistenceReport.md)
- [system/docker/index.php](/code/files/system/docker/index.php.md)
- [system/index.php](/code/files/system/index.php.md)

