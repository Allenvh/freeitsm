# includes/watchtower_settings.php

> PHP - 378 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Watchtower Settings Developer Guide](https://github.com/edmozley/freeitsm/wiki/Watchtower-Settings-Developer-Guide)

Watchtower settings — which cards appear, and which statuses feed each count.

WHY THIS IS NOT A FLAG ON THE STATUS ROW

A status carries facts about itself — is_closed, is_default, pauses_sla — and
the SLA engine and half the application depend on them. "Show this one on my
dashboard" is not a fact about the status, it is a fact about the dashboard,
and it differs per site: one team treats Blocked as the first thing they want
to see in the morning, another never looks at it. Putting it on the status
would also mean tuning Watchtower by touring three modules' settings screens —
which is already the situation for the two Watchtower settings that exist
(one lives in Assets, and one has no screen at all).

THE DEFAULT MUST BE CORRECT WITHOUT ANYONE CONFIGURING ANYTHING

No row here means "use the built-in behaviour", which is the general,
name-free version. So a fresh install, and every existing one, is right on
day one and this screen only ever TRIMS what is already true. If ticking
boxes were what made Watchtower correct, every install would be wrong until
somebody found the screen — and a blank dashboard reads as "nothing needs
attention", which is the most dangerous thing it could say.

is_customised is what makes that work: it separates "not configured" from
"configured to show nothing". Without it an empty selection is
indistinguishable from an untouched one, and would silently mean "all".

## Functions

| Function | Line | What it does |
|---|--:|---|
| [wtCardKeys()](/code/functions/wtCardKeys.md) | 36 | Every card Watchtower draws, in the order it draws them. |
| [wtScopeIsValid()](/code/functions/wtScopeIsValid.md) | 64 | True when `$scope` is one of the three Watchtower scopes: mine, team or all. |
| [wtImpersonalCards()](/code/functions/wtImpersonalCards.md) | 78 | The cards with no notion of an owner. |
| [wtCardCanScope()](/code/functions/wtCardCanScope.md) | 112 | Can this card narrow to a person or a team at all? |
| [wtImpersonalOnMine()](/code/functions/wtImpersonalOnMine.md) | 129 | What this analyst wants done with the impersonal cards while on "Mine": 'show' (marked as everyone's) or 'hide'. |
| [wtScopeFor()](/code/functions/wtScopeFor.md) | 146 | The scope this analyst last chose. Defaults to everyone, i.e. today's view. |
| [wtTeamAnalystIds()](/code/functions/wtTeamAnalystIds.md) | 170 | The analyst ids "team" covers: everyone on any team this analyst belongs to, including themselves. |
| [wtMorningCheckScope()](/code/functions/wtMorningCheckScope.md) | 207 | "Mine" for a morning check, which is not a column. |
| [wtScopeClause()](/code/functions/wtScopeClause.md) | 248 | A SQL fragment restricting `$column` to the chosen scope, plus its parameters. Returns ['', []] for "everyone", so a caller can concatenate unconditionally. |
| [wtSelectableItems()](/code/functions/wtSelectableItems.md) | 265 | The set-driven items, and what kind of thing each one selects from. item_key => ['entity_type' => …, 'table' => …, 'id' => …, 'name' => …, 'where' => …] |
| [wtVisibleCards()](/code/functions/wtVisibleCards.md) | 315 | Card visibility. Missing row = visible. |
| [wtItemMembers()](/code/functions/wtItemMembers.md) | 344 | The chosen members for an item, or NULL meaning "not customised, use the built-in default". An empty ARRAY is a real answer — the admin chose nothing — and is not the same as null. |
| [wtIdListSql()](/code/functions/wtIdListSql.md) | 373 | A safe `IN (…)` fragment from a list of ints. '(0)' when empty, so it matches nothing. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 134 | `wtImpersonalOnMine` | read [user_preferences](/code/tables/user_preferences.md) |  |
| 151 | `wtScopeFor` | read [user_preferences](/code/tables/user_preferences.md) |  |
| 175 | `wtTeamAnalystIds` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 216 | `wtMorningCheckScope` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 320 | `wtVisibleCards` | read [watchtower_items](/code/tables/watchtower_items.md) |  |
| 351 | `wtItemMembers` | read [watchtower_items](/code/tables/watchtower_items.md) |  |
| 356 | `wtItemMembers` | read [watchtower_item_members](/code/tables/watchtower_item_members.md) |  |

## How it hangs together

**Included by:** [api/watchtower/get_settings.php](/code/files/api/watchtower/get_settings.php.md), [api/watchtower/save_settings.php](/code/files/api/watchtower/save_settings.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md), [watchtower/index.php](/code/files/watchtower/index.php.md)

