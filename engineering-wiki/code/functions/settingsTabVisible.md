# settingsTabVisible()

Is this tab id in the visible set? Use to decide whether to emit its panel.

```php
function settingsTabVisible(array $visibleTabs, string $tabId): bool
```

Defined in [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), line 83.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$visibleTabs` | `array` |  | the tabs from `settingsVisibleTabs()` |
| `$tabId` | `string` |  | the tab's id from the manifest, e.g. 'vcenter' |

**Returns:** `bool`

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
- [watchtower/settings/index.php](/code/files/watchtower/settings/index.php.md)
- [workflow/settings/index.php](/code/files/workflow/settings/index.php.md)

