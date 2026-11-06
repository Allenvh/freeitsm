# process-mapper/index.php

> PHP - 513 lines

Process Mapper - Visual flowchart builder

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [process_step_types](/code/tables/process_step_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [process-mapper/includes/shapes.php](/code/files/process-mapper/includes/shapes.php.md), [process-mapper/includes/header.php](/code/files/process-mapper/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/vendor/html2canvas.min.js](/code/files/assets/js/vendor/html2canvas.min.js.md), [assets/js/vendor/jspdf.umd.min.js](/code/files/assets/js/vendor/jspdf.umd.min.js.md), [assets/js/process-mapper.js](/code/files/assets/js/process-mapper.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

