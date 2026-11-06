# includes/analyst_signin.php

> PHP - 193 lines

Sign-in method set at team level (GH #41).

A team can carry a default sign-in method; an analyst set to "Follow team"
takes it. An analyst with their own method set is never touched.

── WHY THE RESULT IS WRITTEN INTO analysts.auth_provider_id ────────────────
Every place that ENFORCES sign-in - the email-first router, the password
form, the OIDC callback, the LDAP bind - reads analysts.auth_provider_id and
nothing else. That is the strict-isolation rule: one account, exactly one
way in. Working the team default out at sign-in time instead would mean
teaching all of them about teams, and a mistake in any one would be a way
round the rule. So the team's method is COPIED into that column whenever
something that could change it happens, and the enforcement code is not
changed at all.

The cost is that every place that can change the answer has to call
analystSignInApply*() below:
  - an analyst's own method or follow flag saved   api/tickets/save_analyst.php
  - an analyst's teams saved                        api/tickets/save_analyst_teams.php
  - a team's members saved                          api/tickets/save_team_analysts.php
  - a team's method or active flag saved            api/tickets/save_team.php
  - a team deleted                                  api/tickets/delete_team.php
  - a provider deleted                              api/system/delete_sso_provider.php

── WHEN THE TEAMS DISAGREE ─────────────────────────────────────────────────
Nothing is guessed. If an analyst's teams set two different methods, their
current method is KEPT and the disagreement is reported, so an administrator
chooses on System -> Analysts. The same when none of their teams sets one.
A priority order between teams was the alternative, and was rejected: it is
invisible, and someone would one day move a person between teams and change
how they sign in without knowing.

Only ACTIVE teams count, and a team pointing at a provider that no longer
exists counts as not set (Database Verification adds columns without foreign
keys, so a deleted provider can leave a dangling id behind).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [analystSignInReady()](/code/functions/analystSignInReady.md) | 48 | Have the columns this needs been added yet? |
| [analystSignInTeamMethods()](/code/functions/analystSignInTeamMethods.md) | 71 | The sign-in methods set by analysts' active teams, grouped by analyst. |
| [analystSignInVerdict()](/code/functions/analystSignInVerdict.md) | 106 | Turn one analyst's team methods into a verdict. |
| [analystSignInFromTeams()](/code/functions/analystSignInFromTeams.md) | 119 | What one analyst's teams say, without changing anything. |
| [analystSignInApply()](/code/functions/analystSignInApply.md) | 130 | Bring one analyst's stored method in line with their teams, if they follow their team. Returns what happened, for the caller to report. |
| [analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md) | 162 | Apply to several analysts and count the outcomes. |
| [analystSignInTeamMemberIds()](/code/functions/analystSignInTeamMemberIds.md) | 178 | The members of a team, for the callers that change the team itself. |
| [analystSignInFollowerIds()](/code/functions/analystSignInFollowerIds.md) | 186 | Every analyst who follows their team - used when a provider is deleted. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 | `analystSignInReady` | read [analysts](/code/tables/analysts.md) |  |
| 54 | `analystSignInReady` | read [teams](/code/tables/teams.md) |  |
| 73 | `analystSignInTeamMethods` | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 135 | `analystSignInApply` | read [analysts](/code/tables/analysts.md) |  |
| 151 | `analystSignInApply` | **update** [analysts](/code/tables/analysts.md) (`auth_provider_id`, `last_modified_datetime`) |  |
| 180 | `analystSignInTeamMemberIds` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 191 | `analystSignInFollowerIds` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Included by:** [api/system/delete_sso_provider.php](/code/files/api/system/delete_sso_provider.php.md), [api/tickets/delete_team.php](/code/files/api/tickets/delete_team.php.md), [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md), [api/tickets/get_teams.php](/code/files/api/tickets/get_teams.php.md), [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md), [api/tickets/save_analyst_teams.php](/code/files/api/tickets/save_analyst_teams.php.md), [api/tickets/save_team.php](/code/files/api/tickets/save_team.php.md), [api/tickets/save_team_analysts.php](/code/files/api/tickets/save_team_analysts.php.md), [includes/sso_claims.php](/code/files/includes/sso_claims.php.md)

