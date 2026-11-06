# forms/collection.php

> PHP - 512 lines

Forms Module — every submission in a collection, across all its forms.

🔑 WHY THIS IS NOT submissions.php. That page shows ONE form, and its columns
ARE that form's questions. A collection holds several forms with different
questions, so the table can only show what they have in common — which form,
who, when, and the approval — and the answers live in the detail panel and
the PDF, where each submission is rendered against its own form.

The rows come from `form_submissions.collection_id`, the STAMP, not from
joining through the form. A submission made while the form was a member
belongs here whatever the form says today.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [forms/includes/header.php](/code/files/forms/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/form-logic.js](/code/files/assets/js/form-logic.js.md), [assets/js/vendor/jspdf.umd.min.js](/code/files/assets/js/vendor/jspdf.umd.min.js.md), [assets/js/vendor/jspdf.plugin.autotable.min.js](/code/files/assets/js/vendor/jspdf.plugin.autotable.min.js.md), [assets/js/form-pdf.js](/code/files/assets/js/form-pdf.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

