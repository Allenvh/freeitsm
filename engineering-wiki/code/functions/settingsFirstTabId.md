# settingsFirstTabId()

The tab that should open first: the first visible one.

Not simply "the first in the manifest" — that tab may not be visible to this analyst,
and hard-coding `class="tab-content active"` on it would leave a restricted user
looking at a settings page with no panel showing.

```php
function settingsFirstTabId(array $visibleTabs): ?string
```

Defined in [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), line 100.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$visibleTabs` | `array` |  | the tabs from `settingsVisibleTabs()` |

**Returns:** `?string`

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

