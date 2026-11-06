# includes/settings_manifest.php

> PHP - 155 lines

Settings manifests — the only place a settings TAB may be declared.

RBAC phase 3 gives every settings tab its own capability (docs/design/rbac.md §5).
A manifest binds the two together, and the tab bar is RENDERED FROM IT — so a tab
cannot exist without either a declared capability or an explicit null meaning "this
is a personal preference, not administration". Drift between what the settings page
shows and what the code enforces becomes impossible, rather than merely detectable.

A module's manifest lives at <module>/settings/manifest.php and returns:

  [
    'module' => 'assets',                       // Layer 1 module slug
    'label'  => 'Asset management',             // its name in the Roles picker
    'umbrella' => ['cap' => Cap::ASSETS_MANAGE, 'grant' => '…'],
    'tabs'   => [
      ['id' => 'vcenter',
       'cap' => Cap::ASSETS_VCENTER,            // null = personal preference, never gated
       'label_key' => 'asset-management.settings.tab_vcenter',   // the TAB's name
       'grant' => 'Configure the vCenter connection…',           // the PERMISSION's description
       'sensitive' => true,                                      // badged in the Roles picker
       'setting_keys' => ['vcenter_server', …],                  // who may write these
      ],
    ],
  ]

This file is the ONLY place any of that is written. The capability registry, the module
list for System → Roles, and the setting-key ownership map are all DERIVED from it (see
includes/capabilities.php). They cannot disagree with it, because they are it.

---------------------------------------------------------------------------
NOT RENDERED, NOT HIDDEN
---------------------------------------------------------------------------
A panel the analyst lacks the capability for is not emitted into the page at all.
There is no `display:none` to flip in devtools, because there is no DOM. The
data-capability attribute the renderer stamps on each tab is a LABEL, not a lock —
it exists so the page is machine-readable for the coverage report. The enforcement
is (a) the panel not being rendered and (b) the guard on the endpoint behind it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md) | 64 | The tabs this analyst may see, in manifest order. |
| [settingsTabVisible()](/code/functions/settingsTabVisible.md) | 83 | Is this tab id in the visible set? Use to decide whether to emit its panel. |
| [settingsFirstTabId()](/code/functions/settingsFirstTabId.md) | 100 | The tab that should open first: the first visible one. |
| [settingsHasAnyAdminTab()](/code/functions/settingsHasAnyAdminTab.md) | 110 | Does this analyst hold ANY administrative capability in this module? |
| [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md) | 130 | Render the tab bar from the manifest — only the tabs this analyst may see. |

## How it hangs together

**Includes:** [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md)

**Included by:** [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md), [calendar/settings/index.php](/code/files/calendar/settings/index.php.md), [change-management/settings/index.php](/code/files/change-management/settings/index.php.md), [cmdb/settings/index.php](/code/files/cmdb/settings/index.php.md), [contracts/settings/index.php](/code/files/contracts/settings/index.php.md), [domains/settings/index.php](/code/files/domains/settings/index.php.md), [files/settings/index.php](/code/files/files/settings/index.php.md), [forms/settings/index.php](/code/files/forms/settings/index.php.md), [knowledge/settings/index.php](/code/files/knowledge/settings/index.php.md), [morning-checks/settings/index.php](/code/files/morning-checks/settings/index.php.md), [problem-management/settings/index.php](/code/files/problem-management/settings/index.php.md), [process-mapper/settings/index.php](/code/files/process-mapper/settings/index.php.md), [projects/settings/index.php](/code/files/projects/settings/index.php.md), [service-status/settings/index.php](/code/files/service-status/settings/index.php.md), [software/settings/index.php](/code/files/software/settings/index.php.md), [system/sso/carddav.php](/code/files/system/sso/carddav.php.md), [system/sso/provider.php](/code/files/system/sso/provider.php.md), [tasks/settings/index.php](/code/files/tasks/settings/index.php.md), [tickets/settings/index.php](/code/files/tickets/settings/index.php.md), [war-room/settings/index.php](/code/files/war-room/settings/index.php.md), [watchtower/includes/header.php](/code/files/watchtower/includes/header.php.md), [watchtower/settings/index.php](/code/files/watchtower/settings/index.php.md), [workflow/settings/index.php](/code/files/workflow/settings/index.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [t()](/code/functions/t.md)

