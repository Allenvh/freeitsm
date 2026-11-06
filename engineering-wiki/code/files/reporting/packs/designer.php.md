# reporting/packs/designer.php

> PHP - 65 lines

Reporting -> Report Packs -> the designer.

A thin shell: the ribbon, toolbox, pages and properties are built by
assets/js/report-packs/designer.js from one configuration, and the pages are
drawn by the layout engine. Whether you may change the pack (owner / edit)
or only view and export it is decided by the server on every save - the
read-only mode here is a courtesy, not the control.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [reporting/includes/header.php](/code/files/reporting/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/vendor/jspdf.umd.min.js](/code/files/assets/js/vendor/jspdf.umd.min.js.md), [assets/js/chart.min.js](/code/files/assets/js/chart.min.js.md), [assets/js/report-packs/engine.js](/code/files/assets/js/report-packs/engine.js.md), [assets/js/report-packs/charts.js](/code/files/assets/js/report-packs/charts.js.md), [assets/js/report-packs/render-svg.js](/code/files/assets/js/report-packs/render-svg.js.md), [assets/js/report-packs/render-pdf.js](/code/files/assets/js/report-packs/render-pdf.js.md), [assets/js/report-packs/runtime.js](/code/files/assets/js/report-packs/runtime.js.md), [assets/js/report-packs/editor.js](/code/files/assets/js/report-packs/editor.js.md), [assets/js/report-packs/designer.js](/code/files/assets/js/report-packs/designer.js.md)

**Calls:** [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

