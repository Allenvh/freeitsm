# morning-checks/settings/manifest.php

> PHP - 52 lines

Morning Checks — settings manifest. See includes/capabilities.php.

The 'chart' tab is NOT administration: it stores a per-analyst display preference (the
bar fill style) via api/system/set_user_preference.php, exactly like the left-panel tab
on other modules. So it declares no capability and everyone with the module sees it.

Recording a check RESULT is the everyday job and stays on plain module access.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

