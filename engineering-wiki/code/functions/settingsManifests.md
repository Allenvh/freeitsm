# settingsManifests()

Every module's settings manifest, loaded once.

---------------------------------------------------------------------------
THE MANIFEST IS THE SINGLE DECLARATION
---------------------------------------------------------------------------
A module declares its settings tabs — and therefore its capabilities — in exactly one
file: <module>/settings/manifest.php. Everything else is DERIVED from that:

  `capRegistry()`      the capabilities, their module, label and sensitivity
  `capModules()`       the module list for the Roles picker
  `settingKeyOwners()` who may write each shared system_settings key
  the tab bar        rendered from the same list (includes/settings_manifest.php)

The earlier design had all four written out by hand and a self-check to catch them
drifting apart. Needing that check WAS the bug: four copies of one fact, kept in step
by discipline. They can't disagree now, because there is only one of them.

The Cap:: constants above are the one thing that cannot be derived — they are the type,
and their whole value is that a typo at a call site is a fatal error rather than a
silent 403. `capSelfCheck()` proves every constant is claimed by exactly one manifest.

Discovery is a glob, so adding a module means adding its manifest — nothing to register.

```php
function settingsManifests(): array
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 314.

**Returns:** array<int,array> the manifests, in module order

## Called from

- [includes/capabilities.php](/code/files/includes/capabilities.php.md) - in [settingsManifestFor()](/code/functions/settingsManifestFor.md), [capModules()](/code/functions/capModules.md), [capRegistry()](/code/functions/capRegistry.md), [capSelfCheck()](/code/functions/capSelfCheck.md)
- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [settingKeyOwners()](/code/functions/settingKeyOwners.md)

