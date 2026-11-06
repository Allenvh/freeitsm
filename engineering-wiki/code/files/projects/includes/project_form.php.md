# projects/includes/project_form.php

> PHP - 117 lines

The create / edit project dialog, shared by the portfolio and the project page. Filled and saved by Prj.openProjectForm() in assets/js/projects.js. The method cards, swatches and icons are drawn from api/projects/lookups.php, so the palette and icon set have one source (includes/projects/methodologies.php).

## How it hangs together

**Included by:** [projects/index.php](/code/files/projects/index.php.md), [projects/view.php](/code/files/projects/view.php.md)

**Calls:** [t()](/code/functions/t.md)

