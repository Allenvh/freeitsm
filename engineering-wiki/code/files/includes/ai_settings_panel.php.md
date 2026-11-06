# includes/ai_settings_panel.php

> PHP - 161 lines

Reusable AI provider/model/key settings panel.

Drop one of these on any settings page:
    <?php renderAiSettingsPanel('knowledge_ai'); ?>

Requirements on the host page:
  - BASE_URL defined (config.php)
  - i18n: the 'common' namespace exported to window.translations (window.t),
    and assets/js/i18n.js loaded
  - assets/js/ai-settings.js loaded (drives every [data-ai-panel] on the page)
  - `showToast()` available (assets/js/toast.js — auto-loaded via the waffle menu)

The <ns> must be registered in includes/ai_settings.php. All persistence/test
goes through the shared api/system/ai/* endpoints, so this partial carries no
per-module logic.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [renderAiSettingsPanel()](/code/functions/renderAiSettingsPanel.md) | 23 | Reusable AI provider/model/key settings panel. |

## How it hangs together

**Included by:** [cmdb/settings/index.php](/code/files/cmdb/settings/index.php.md), [forms/settings/index.php](/code/files/forms/settings/index.php.md), [knowledge/settings/index.php](/code/files/knowledge/settings/index.php.md), [lms/settings/index.php](/code/files/lms/settings/index.php.md), [problem-management/settings/index.php](/code/files/problem-management/settings/index.php.md), [projects/settings/index.php](/code/files/projects/settings/index.php.md), [tickets/settings/index.php](/code/files/tickets/settings/index.php.md), [war-room/settings/index.php](/code/files/war-room/settings/index.php.md), [workflow/settings/index.php](/code/files/workflow/settings/index.php.md)

**Calls:** [t()](/code/functions/t.md)

