# system/includes/areas.php

> PHP - 482 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [System](https://github.com/edmozley/freeitsm/wiki/System)

System admin areas — single source of truth.

Each entry powers a card on the System landing page (system/index.php) and
the in-page search. Titles/descriptions/keywords are i18n keys (resolved via
`t()`), so all locales work; keywords carry search synonyms (e.g. "oidc" for
Single Sign-On) and fall back to English per-key like everything else.

Icons are referenced by key and resolved to inline SVG in `systemAreaIcon()`
below — keeps the markup out of the data and the registry easy to scan.

'requires' (optional) gates an area behind a runtime condition the landing
page evaluates: 'multitenant' (hidden until a 2nd company exists) and
'container' (only inside the Docker image), keeping the registry itself free
of DB lookups.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [getSystemAreas()](/code/functions/getSystemAreas.md) | 27 | Every card on the System landing page, in display order: icon key, url, the i18n keys for title, description and search keywords, and an optional 'requires' gate. |
| [systemAreaIcon()](/code/functions/systemAreaIcon.md) | 308 | Return the inline SVG markup for an area icon key. Unknown keys render a neutral square so a typo never breaks the page. Sizing/colour come from the .system-card svg CSS rule, so no width/height/stroke colour is hard-set here. |
| [systemAreaSettings()](/code/functions/systemAreaSettings.md) | 376 | The settings inside each System card, for the landing page search (#2293). |

## How it hangs together

**Included by:** [system/help/index.php](/code/files/system/help/index.php.md), [system/help/_init.php](/code/files/system/help/_init.php.md), [system/index.php](/code/files/system/index.php.md)

