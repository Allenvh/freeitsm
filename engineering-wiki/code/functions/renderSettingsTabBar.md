# renderSettingsTabBar()

Render the tab bar from the manifest — only the tabs this analyst may see.

data-capability is emitted FROM the Cap:: constant, so the attribute cannot be a typo
either. Tabs with no capability are marked data-capability="none" rather than being
left bare, so the coverage report can tell "personal preference" apart from "someone
forgot to declare one".

```php
function renderSettingsTabBar(
    array $visibleTabs,
    ?string $activeId,
    string $onclickFn = 'switchTab'
): void
```

Defined in [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), line 130.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$visibleTabs` | `array` |  | the tabs from `settingsVisibleTabs()`, drawn in that order |
| `$activeId` | `?string` |  | the id of the tab drawn as active; null for none |
| `$onclickFn` | `string` | `'switchTab'` | the name of the page's JavaScript tab switcher, called with the tab id |

**Returns:** `void`

## Calls

[t()](/code/functions/t.md)

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
- [system/sso/carddav.php](/code/files/system/sso/carddav.php.md)
- [system/sso/provider.php](/code/files/system/sso/provider.php.md)
- [tasks/settings/index.php](/code/files/tasks/settings/index.php.md)
- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)
- [war-room/settings/index.php](/code/files/war-room/settings/index.php.md)
- [watchtower/settings/index.php](/code/files/watchtower/settings/index.php.md)
- [workflow/settings/index.php](/code/files/workflow/settings/index.php.md)

