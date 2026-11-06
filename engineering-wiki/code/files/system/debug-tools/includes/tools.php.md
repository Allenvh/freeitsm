# system/debug-tools/includes/tools.php

> PHP - 526 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [System](https://github.com/edmozley/freeitsm/wiki/System)

Debug Tools registry — single source of truth.

Each entry powers a searchable card on the Debug Tools landing
(system/debug-tools/index.php) and its own dedicated page
(system/debug-tools/<slug>/index.php). The diagnostic itself is a
self-contained script under api/system/debug-tools/<file> that outputs a
single plain-text report.

To add a new diagnostic:
  1. Drop api/system/debug-tools/Dnnn_short_name.php (plain text, === SECTION === headers).
  2. Add an entry below (id, slug, file, title, category, desc, keywords, icon,
     when, checks, duration, persists, optional input).
  3. Create system/debug-tools/<slug>/index.php (two lines — see d001/index.php).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [getDebugTools()](/code/functions/getDebugTools.md) | 25 | Every Debug Tool with its card and page metadata - id, slug, backend file, title, category, description, search keywords, when to run it, what it checks. |
| [getDebugToolBySlug()](/code/functions/getDebugToolBySlug.md) | 473 | Find one tool by its slug (e.g. 'd001'). Returns null if not found. |
| [debugToolAnalystOptions()](/code/functions/debugToolAnalystOptions.md) | 484 | D018's analyst picker: "you" first, then every active analyst. An empty list (database unreachable) still leaves "you", so the page always renders. |
| [debugToolAiFeatureOptions()](/code/functions/debugToolAiFeatureOptions.md) | 496 | D014's feature picker: every AI feature, plus "all" (GH #171). |
| [debugToolIcon()](/code/functions/debugToolIcon.md) | 511 | Inline SVG markup for a debug-tool icon key. Unknown keys render a neutral wrench so a typo never breaks the page. Sizing/colour come from CSS. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 488 | `debugToolAnalystOptions` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Included by:** [system/debug-tools/includes/tool-page.php](/code/files/system/debug-tools/includes/tool-page.php.md), [system/debug-tools/index.php](/code/files/system/debug-tools/index.php.md)

**Calls:** [aiSettingsRegistry()](/code/functions/aiSettingsRegistry.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

