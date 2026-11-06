# storagePersistenceHasFiles()

Does this directory hold anything an operator would mind losing?

🔑 THIS IS WHAT DECIDES WHICH ADVICE TO GIVE, and it must not be inferred from
anything else. An install with nothing stored yet can simply add the volumes;
an install with files in these folders has to copy them out FIRST, because a
new volume is filled from the image rather than from the container it replaces.
Give the first instruction to somebody in the second situation and they lose
their files while following the documentation.

⚠️ The obvious proxy — "has an analyst been created yet" — is WRONG HERE. The
shipped schema seeds an `admin` account, so every installation looks provisioned
from the moment the database exists, including one that is thirty seconds old.

The execution guards are shipped in these folders, so they are not evidence of
use and are excluded.

```php
function storagePersistenceHasFiles(string $dir): bool
```

Defined in [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), line 289.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dir` | `string` |  | the absolute directory to look in, sub-directories included |

**Returns:** `bool`

## Called from

- [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md) - in [storagePersistenceHasFiles()](/code/functions/storagePersistenceHasFiles.md), [storagePersistenceAnythingToLose()](/code/functions/storagePersistenceAnythingToLose.md)

