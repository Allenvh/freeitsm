# api/system/feature_bingo.php

> API endpoint - 66 lines

API: Feature Bingo (System -> Feature Bingo).

GET                                               every card with its state, and the totals
  POST {action:'dismiss'|'restore', card_id}        "Not for us" on one card, or undo it
  POST {action:'dismiss_module'|'restore_module', module}   ... on every card in a module

Administrators only. The cards are code (includes/feature_bingo/cards/); the
only thing stored is which ones are "Not for us".

## The endpoint

| | |
|---|---|
| URL | `api/system/feature_bingo.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 56 |  | **insert** [feature_bingo_dismissed](/code/tables/feature_bingo_dismissed.md) (`card_id`, `dismissed_by_analyst_id`, `dismissed_datetime`) |  |
| 60 |  | **delete** [feature_bingo_dismissed](/code/tables/feature_bingo_dismissed.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [featureBingoCards()](/code/functions/featureBingoCards.md), [featureBingoCategories()](/code/functions/featureBingoCategories.md), [featureBingoEvaluate()](/code/functions/featureBingoEvaluate.md), [featureBingoModules()](/code/functions/featureBingoModules.md), [featureBingoReady()](/code/functions/featureBingoReady.md)

