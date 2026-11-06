# system/search/index.php

> PHP - 348 lines

System — Search.

What the search index holds, and a button to rebuild it (discussion #53).

⚠️ DELIBERATELY NOT the screen designed in the wiki's §8.4. That design has
two selectors — document extraction and search backend — and today each would
offer exactly one option, because there is no external extractor and no second
backend. A dropdown with one choice is furniture that implies a capability the
product does not have. They belong here when they have something to choose
between; the design's own rule is not to build phase 2 until an install needs
it.

What IS worth a screen now is the status: until this existed the only way to
see the index was D007 under Debug Tools, and the only way to rebuild it was a
command line — which is no help at all on shared hosting.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

