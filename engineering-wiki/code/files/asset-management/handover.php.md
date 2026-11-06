# asset-management/handover.php

> PHP - 163 lines

Equipment handover document (discussion #56).

A printable record that a named person received a named list of equipment,
with somewhere for both of them to sign. The layout comes from a template
built in Assets → Settings → Handover; an install that has never opened the
designer gets the shipped default, which is why HandoverTemplates::effective()
always returns something.

⚠️ RENDERED SERVER-SIDE, ON PURPOSE. This is the one page in the module that
somebody signs and files, so it must not depend on JavaScript having run, on a
fetch having succeeded, or on a library being present. What the printer puts
on paper is what the server sent.

Printing is the browser's own dialogue rather than a generated PDF: it gives a
true PDF via "Save as PDF" everywhere, keeps the text selectable, and inherits
the logo and fonts already on the page. A jsPDF re-implementation would be a
second layout to keep in step with this one.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/services/handover_templates.php](/code/files/includes/services/handover_templates.php.md), [includes/handover_styles.php](/code/files/includes/handover_styles.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [handoverDocumentCss()](/code/functions/handoverDocumentCss.md), [handoverPrintCss()](/code/functions/handoverPrintCss.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

