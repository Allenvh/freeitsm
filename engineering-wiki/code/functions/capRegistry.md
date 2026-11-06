# capRegistry()

The capability registry — DERIVED from the manifests.

A tab's 'cap' becomes a capability; its 'grant' is the description shown in the Roles
picker; 'sensitive' badges it. The module's 'umbrella' becomes the "manage everything
here" capability. Tabs declaring 'cap' => null are personal preferences and contribute
nothing — they are not administration and there is nothing to grant.

```php
function capRegistry(): array
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 364.

**Returns:** array<string,array{module:string,umbrella:bool,sensitive:bool,label:string}>

## Calls

[settingsManifests()](/code/functions/settingsManifests.md)

## Called from

- [includes/capabilities.php](/code/files/includes/capabilities.php.md) - in [capAll()](/code/functions/capAll.md), [capExists()](/code/functions/capExists.md), [capLabel()](/code/functions/capLabel.md), [capModule()](/code/functions/capModule.md), [capIsSensitive()](/code/functions/capIsSensitive.md), [capsForModule()](/code/functions/capsForModule.md), [capUmbrella()](/code/functions/capUmbrella.md), [capExpandUmbrellas()](/code/functions/capExpandUmbrellas.md), [capSelfCheck()](/code/functions/capSelfCheck.md), [capIsUmbrella()](/code/functions/capIsUmbrella.md)

