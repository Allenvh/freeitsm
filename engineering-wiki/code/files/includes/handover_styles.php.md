# includes/handover_styles.php

> PHP - 88 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Asset Handover Developer Guide](https://github.com/edmozley/freeitsm/wiki/Asset-Handover-Developer-Guide)

The asset handover document's stylesheets, kept as PHP strings.

`handoverDocumentCss()` styles the document itself and is shared by the printable page, the
designer's live preview and the emailed copy; `handoverPrintCss()` adds the print-only
rules.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [handoverDocumentCss()](/code/functions/handoverDocumentCss.md) | 25 | The handover document's stylesheet, in one place (discussion #56). |
| [handoverPrintCss()](/code/functions/handoverPrintCss.md) | 74 | Print rules — only the printable page needs these. |

## How it hangs together

**Included by:** [api/assets/email_handover.php](/code/files/api/assets/email_handover.php.md), [asset-management/handover.php](/code/files/asset-management/handover.php.md), [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md)

