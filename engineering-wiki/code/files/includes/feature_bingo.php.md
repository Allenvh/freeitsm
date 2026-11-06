# includes/feature_bingo.php

> PHP - 278 lines

Feature Bingo - System -> Feature Bingo.

Most people use a fraction of what they bought. Every card here is one thing
FreeITSM can do - "at least one analyst", "an SLA calendar", "portal managers"
- with a star that lights up once it is set up, and an explainer of what it is
and why it is worth doing.

─────────────────────────────────────────────────────────────────────────────
 A CARD IS DATA. The cards live in includes/feature_bingo/cards/<module>.php,
 one file per module, each returning a list of:
─────────────────────────────────────────────────────────────────────────────

  [
    'id'     => 'tickets.sla_calendar',     // unique, stable - "Not for us" is stored against it
    'module' => 'tickets',                   // a key of `featureBingoModules()`
    'tier'   => 'essential',                 // essential | recommended | extra
    'category' => 'governance',              // a key of `featureBingoCategories()` - what KIND of thing it is
    'title'  => 'Business hours for SLAs',   // the feature, short
    'what'   => 'One or two sentences: what it is.',
    'why'    => 'One or two sentences: why it is worth setting up.',
    'done'   => 'Plain words for exactly what the check counts, e.g. "At least one business calendar exists."',
    'link'   => 'tickets/settings/?tab=sla', // app-relative page where it is set up (no leading slash)
    'check'  => [...],                        // see below
  ]

THE CHECK - declarative, so a card can never run anything but a read:

  ['rows', 'table']                          at least one row
  ['rows', 'table', 'where sql', min]        at least `min` rows matching (min defaults to 1)
  ['setting', 'key', 'eq', 'value']          system_settings value equals
  ['setting', 'key', 'neq', 'value']         ... is set and differs (a never-saved key counts as not configured)
  ['setting', 'key', 'in', ['a','b']]        ... is one of
  ['setting', 'key', 'nonempty']             ... has any non-empty value
  ['sql', 'SELECT COUNT(*) ...', min]        a single number >= min (default 1). SELECT only.
  ['any', [check, check, ...]]               any one lights it
  ['all', [check, check, ...]]               all of them

A check that errors - a table Database Verification has not created yet, a
column that does not exist - reads as NOT configured and is reported by
scripts/feature_bingo_check.php, which runs every check against a database.
It never breaks the page.

Card copy is English, like System help (includes a lot of prose; see
system/help/_init.php for the reasoning). The page's own labels are translated.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [featureBingoCategories()](/code/functions/featureBingoCategories.md) | 55 | What kind of thing a card is, across modules (Ed: "AI, security, automation that you can also filter by"). Every card has exactly one. key => label. |
| [featureBingoModules()](/code/functions/featureBingoModules.md) | 74 | The modules cards belong to, in the order the filter lists them. key => label. |
| [featureBingoCards()](/code/functions/featureBingoCards.md) | 114 | Every card, validated. A card with a missing field or an unknown module or tier is left out and reported in `$problems`, never shown half-formed. |
| [featureBingoRunCheck()](/code/functions/featureBingoRunCheck.md) | 156 | Run one check. Returns true / false; an error (missing table or column, a malformed check) is false and is written to `$error`. |
| [featureBingoAssertReadOnly()](/code/functions/featureBingoAssertReadOnly.md) | 215 | A card's SQL is read-only by construction, and this makes sure of it: one statement, beginning SELECT. Cards are code in the repository, not user input - this guards against a mistake, not an attacker. |
| [featureBingoDismissed()](/code/functions/featureBingoDismissed.md) | 227 | The ids an administrator has marked "Not for us". |
| [featureBingoEvaluate()](/code/functions/featureBingoEvaluate.md) | 240 | Every card with its state: 'lit' (configured), 'unlit', or 'dismissed' (Not for us - out of the score, whatever its check says). Plus the totals. |
| [featureBingoReady()](/code/functions/featureBingoReady.md) | 258 | Has Database Verification created the "Not for us" table yet? |
| [featureBingoLocalDir()](/code/functions/featureBingoLocalDir.md) | 274 | Where an install keeps ITS OWN cards - for its own processes, or for a feature it has added to its copy of FreeITSM. Same format, same checks. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 171 | `featureBingoRunCheck` | read [system_settings](/code/tables/system_settings.md) |  |
| 230 | `featureBingoDismissed` | read [feature_bingo_dismissed](/code/tables/feature_bingo_dismissed.md) |  |
| 260 | `featureBingoReady` | read [feature_bingo_dismissed](/code/tables/feature_bingo_dismissed.md) |  |

## How it hangs together

**Included by:** [api/system/feature_bingo.php](/code/files/api/system/feature_bingo.php.md)

