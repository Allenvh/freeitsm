# capSelfCheck()

Self-check: the things derivation CANNOT rule out.

Most of what this used to check is now impossible by construction — the registry, the
module list and the setting-key map are all derived from the manifests, so they cannot
disagree with them. What's left is the seam that derivation doesn't cross: the Cap::
constants are hand-written (they have to be — they're the type), so a constant can
still be declared and then never claimed by any manifest.

That is not a cosmetic problem. An unclaimed constant is a capability nobody can be
granted, so any guard using it 403s everyone except administrators — permanently, and
invisibly to the administrator, who bypasses the check. It is exactly the failure this
whole file exists to prevent, arriving by a different door.

```php
function capSelfCheck(): array
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 591.

**Returns:** array<int,string> human-readable problems; empty means healthy

## Calls

[capAliases()](/code/functions/capAliases.md), [capExists()](/code/functions/capExists.md), [capModules()](/code/functions/capModules.md), [capRegistry()](/code/functions/capRegistry.md), [capsForModule()](/code/functions/capsForModule.md), [settingKeyOwner()](/code/functions/settingKeyOwner.md), [settingsManifests()](/code/functions/settingsManifests.md)

## Called from

- [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md)

