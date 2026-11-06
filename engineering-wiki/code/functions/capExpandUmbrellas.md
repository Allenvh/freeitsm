# capExpandUmbrellas()

Expand umbrella grants: holding '<module>.manage' means holding every capability in that module.

This is what makes ~70 capabilities usable — an "Asset Administrator" role is one tick,
not seven. It also keeps roles stable over time: add a tab next year and umbrella-holders
pick it up automatically, while a role that ticked individual tabs does NOT. That is the
safe direction, and it is why deny-by-default survives the convenience.

```php
function capExpandUmbrellas(array $held): array
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 528.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$held` | `array` |  | capability keys granted directly by roles |

**Returns:** array<int,string> those keys plus everything their umbrellas imply

## Calls

[capRegistry()](/code/functions/capRegistry.md), [capsForModule()](/code/functions/capsForModule.md)

## Called from

- [includes/rbac.php](/code/files/includes/rbac.php.md) - in [getAnalystCapabilities()](/code/functions/getAnalystCapabilities.md)

