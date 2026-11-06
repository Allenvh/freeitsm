# system/help/index.php

> PHP - 231 lines

System Help — landing page. A searchable card per system area.

Cards come from `helpCards()` (system/help/_registry.php), which joins the
System area registry (icons + translated titles) to the help topic registry
(sections + search terms). Two consequences worth knowing:

  - An area with no help page yet gets NO card, rather than a card linking to
    a page that doesn't exist. It's listed as a gap at the foot instead.
  - Search matches a card's title, description, the area's keyword synonyms,
    the page's standfirst, its section headings and its extra search terms —
    and a section-heading hit deep-links straight to that section.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [system/includes/areas.php](/code/files/system/includes/areas.php.md), [system/help/_registry.php](/code/files/system/help/_registry.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [helpCardHaystack()](/code/functions/helpCardHaystack.md), [helpCards()](/code/functions/helpCards.md), [helpTopicsMissing()](/code/functions/helpTopicsMissing.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [pageTitle()](/code/functions/pageTitle.md), [systemAreaIcon()](/code/functions/systemAreaIcon.md)

