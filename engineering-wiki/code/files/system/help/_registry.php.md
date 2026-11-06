# system/help/_registry.php

> PHP - 620 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Help Page House Style](https://github.com/edmozley/freeitsm/wiki/Help-Page-House-Style)

System help — the topic registry.

One entry per help page, keyed by slug. The slug matches the System area's
url in system/includes/areas.php (minus the trailing slash), which is also
the help page's filename: 'db-verify' => db-verify.php => the help for the
db-verify/ area.

The registry owns:
  hero      — the page's H1 (English: System help is English-only, see _init.php)
  sub       — the hero standfirst
  sections  — the page's sections, in order. These drive BOTH the on-page
              sidebar nav (via _top.php, so a page never restates them) and
              the search index on index.php, which can deep-link to a section.
  terms     — extra English search synonyms, for words a person would type
              that appear in neither the title, the description nor a section.

The card's icon, title and description are NOT repeated here — they come from
`getSystemAreas()` (translated), so a card and its help page cannot drift apart.
`helpCards()` joins the two; an area with no registry entry is simply not
offered a card, which is what stops the landing page linking to a help page
that does not exist.

ADDING A HELP PAGE: add the entry here, then create <slug>.php which sets
`$helpSlug` and requires _top.php / _bottom.php. Nothing else to wire up — the
card, the search index and the sidebar all follow from this entry.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [getHelpTopics()](/code/functions/getHelpTopics.md) | 37 | Every System help page, keyed by the slug of its System area, with its hero, standfirst, sections (sidebar and search deep links) and extra search terms. |
| [getHelpTopic()](/code/functions/getHelpTopic.md) | 540 | One topic by slug, or null. Slug comes from the page (`$helpSlug`) or the URL. |
| [helpCards()](/code/functions/helpCards.md) | 556 | The help cards for the landing page: every System area that HAS a help page, in the same order as the System landing page itself. |
| [helpCardHaystack()](/code/functions/helpCardHaystack.md) | 591 | The text a card is matched against when someone searches. Lower-cased and flattened so the caller can do a single substring test: title, description, the area's own keyword synonyms, this page's standfirst, its section labels and any extra search terms. |
| [helpTopicsMissing()](/code/functions/helpTopicsMissing.md) | 609 | System areas that still have no help page — the gap this registry exists to make visible. Shown as a quiet footnote on the landing page rather than hidden, so a new area doesn't quietly ship undocumented. |

## How it hangs together

**Included by:** [system/help/index.php](/code/files/system/help/index.php.md), [system/help/_init.php](/code/files/system/help/_init.php.md)

**Calls:** [getSystemAreas()](/code/functions/getSystemAreas.md), [t()](/code/functions/t.md)

