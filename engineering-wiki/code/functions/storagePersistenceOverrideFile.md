# storagePersistenceOverrideFile()

A COMPLETE docker-compose.override.yml, ready to save as a new file.

🔑 WHY AN OVERRIDE FILE AND NOT "EDIT docker-compose.yml".

docker-compose.yml is tracked in git, and the operator updates by pulling. If
they have edited it and we later change it too, `git pull` REFUSES — "your
local changes would be overwritten by merge" — and the entire upgrade stops,
not just that file. The advice everybody then finds is `git checkout --
docker-compose.yml`, which makes the pull work again by silently throwing away
the volumes they added. The next rebuild then destroys their files exactly as
before, and nothing anywhere says why. Tested; that is precisely what happens.

Compose reads docker-compose.override.yml automatically, with no extra flags,
and MERGES it — verified with `docker compose config`, base and override
volumes all present. The file is gitignored, so it is never in a pull's way
and an upgrade can never revert it.

It is also a better instruction to give: "create this file with this content"
needs no judgement about where to paste or how far to indent, and it can be
checked by eye against what we printed.

```php
function storagePersistenceOverrideFile(array $report): string
```

Defined in [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), line 383.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$report` | `array` |  | the report from `storagePersistenceReport()` |

**Returns:** `string`

## Calls

[storagePersistenceVolumeName()](/code/functions/storagePersistenceVolumeName.md)

## Called from

- [api/system/debug-tools/D013_storage_persistence.php](/code/files/api/system/debug-tools/D013_storage_persistence.php.md)
- [setup/index.php](/code/files/setup/index.php.md)

