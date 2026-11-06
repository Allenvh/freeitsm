# asset-management/users.php

> PHP - 584 lines

Who holds what — every person with equipment assigned to them, and a handover document for any of them (discussion #56).

Deliberately mirrors tickets/users.php: search a list of people on the left,
click one, see their things on the right. That page already taught everybody
this shape, so this is the same shape with assets in it rather than tickets.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/person_editor.php](/code/files/includes/person_editor.php.md), [asset-management/includes/header.php](/code/files/asset-management/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/address-book-add.js](/code/files/assets/js/address-book-add.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/confirm.js](/code/files/assets/js/confirm.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [personEditorRender()](/code/functions/personEditorRender.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

