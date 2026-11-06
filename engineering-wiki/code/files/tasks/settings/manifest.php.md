# tasks/settings/manifest.php

> PHP - 110 lines

Tasks — settings manifest.

THE single declaration of this module's settings tabs, and therefore of its
capabilities. See includes/capabilities.php.

Two things this module forced us to be honest about:

1. **Creating a tag is operational, deleting one is not.** The Kanban board lets you
   type a new tag straight onto a task (assets/js/tasks.js), so api/tasks/save_task_tag.php
   is everyday work and stays on plain module access — gate it and the board breaks for
   everyone. Deleting a tag, and configuring how tags behave, are administration and sit
   behind tasks.tags. The capability is honest about what it actually controls.

2. **Reordering the board's columns is a configuration change.** Dragging a column on
   the Kanban board reorders the statuses *for everybody*, so it now needs tasks.statuses
   — the same permission as editing the statuses in settings. It is the same act,
   reached by a different route.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

