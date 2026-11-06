# settingsVisibleTabs()

The tabs this analyst may see, in manifest order.

A tab with 'cap' => null is a personal preference (e.g. the left-panel display
setting, which appears on seven modules' settings pages) and is always visible.
Everything else is deny-by-default: no capability, no tab. is_admin holds every
capability, so administrators see the lot.

```php
function settingsVisibleTabs(PDO $conn, int $analystId, array $manifest): array
```

Defined in [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$manifest` | `array` |  | a module's settings manifest (<module>/settings/manifest.php), with its 'tabs' list |

**Returns:** array<int,array> the visible tab definitions

## Calls

[analystHasCapability()](/code/functions/analystHasCapability.md)

## Called from

- [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md)
- [calendar/settings/index.php](/code/files/calendar/settings/index.php.md)
- [change-management/settings/index.php](/code/files/change-management/settings/index.php.md)
- [cmdb/settings/index.php](/code/files/cmdb/settings/index.php.md)
- [contracts/settings/index.php](/code/files/contracts/settings/index.php.md)
- [domains/settings/index.php](/code/files/domains/settings/index.php.md)
- [files/settings/index.php](/code/files/files/settings/index.php.md)
- [forms/settings/index.php](/code/files/forms/settings/index.php.md)
- [knowledge/settings/index.php](/code/files/knowledge/settings/index.php.md)
- [morning-checks/settings/index.php](/code/files/morning-checks/settings/index.php.md)
- [problem-management/settings/index.php](/code/files/problem-management/settings/index.php.md)
- [process-mapper/settings/index.php](/code/files/process-mapper/settings/index.php.md)
- [projects/settings/index.php](/code/files/projects/settings/index.php.md)
- [service-status/settings/index.php](/code/files/service-status/settings/index.php.md)
- [software/settings/index.php](/code/files/software/settings/index.php.md)
- [tasks/settings/index.php](/code/files/tasks/settings/index.php.md)
- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)
- [war-room/settings/index.php](/code/files/war-room/settings/index.php.md)
- [watchtower/includes/header.php](/code/files/watchtower/includes/header.php.md)
- [watchtower/settings/index.php](/code/files/watchtower/settings/index.php.md)
- [workflow/settings/index.php](/code/files/workflow/settings/index.php.md)

