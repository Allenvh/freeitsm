# includes/sso_claims.php

> PHP - 304 lines

SSO groups (3.5.0, proposed in PR #174) - the OIDC twin of LDAP's analyst group, plus team membership that follows the identity provider.

An OIDC sign-in carries CLAIMS about the person, and one is usually their
groups or roles. An OIDC provider (System -> SSO -> Groups & teams) can use it:

  oidc_group_claim    which claim holds the groups: groups, roles,
                      realm_access.roles (Keycloak) or another the admin names
  oidc_analyst_group  only members sign in as - and are created as - analysts.
                      LDAP's ldap_analyst_group rule: someone who has left it
                      is refused; their account is left as it is
  oidc_admin_group    members are administrators, non-members are not
                      (the last active administrator is never demoted)
  sso_group_teams     group -> team rows, picked from dropdowns. At every
                      analyst sign-in, membership of each MAPPED team follows
                      the groups; a team with no mapping is never touched.
                      The team decides modules, companies and roles, exactly
                      as it does for anyone added to it by hand.
  sso_groups_seen     every group a provider has sent, so the screen offers
                      groups in a dropdown instead of asking anyone to type one -
                      each with a NAME the admin gives it (label), because some
                      providers (Microsoft Entra) send codes, not names

🔑 Nobody types codes. A group name is the identity provider's own, chosen
from what it has sent; everything a group GIVES comes from a team set up on
the Teams screen, in the admin's own language.

Group names match ignoring case - for any alphabet, not only A-Z (mb_strtolower),
a fix LDAP's `ldapInGroup()` gets too.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ssoClaimsReady()](/code/functions/ssoClaimsReady.md) | 37 | True once Database Verification has added the columns and tables. |
| [ssoCommonGroupClaims()](/code/functions/ssoCommonGroupClaims.md) | 50 | The claims offered in the dropdown; anything else is "Other". |
| [ssoClaimValues()](/code/functions/ssoClaimValues.md) | 65 | The values of claim `$path` as a list of strings. A dotted path walks nested objects ("realm_access.roles"); a claim whose own name has dots (a URL) is matched whole first. An array gives its scalar members; a string gives itself. Works on the decoded ID token (nested parts are stdClass) and on userinfo. |
| [ssoSameGroup()](/code/functions/ssoSameGroup.md) | 99 | The same group, ignoring case in any alphabet and stray spaces. |
| [ssoInGroup()](/code/functions/ssoInGroup.md) | 111 | Is `$wanted` among `$groups`? |
| [ssoTeamMappings()](/code/functions/ssoTeamMappings.md) | 124 | A provider's group -> team rows, with the team names. |
| [ssoSaveTeamMappings()](/code/functions/ssoSaveTeamMappings.md) | 142 | Replace a provider's group -> team rows. `$rows`: [['group' => .., 'team_id' => ..]]. Blank rows are skipped; an unknown team is refused (ServiceError). |
| [ssoSeenGroups()](/code/functions/ssoSeenGroups.md) | 166 | Groups this provider has sent: [['group' => code, 'label' => name\|null, 'last_seen' => datetime]], named ones first, then most recently seen. |
| [ssoSaveGroupLabels()](/code/functions/ssoSaveGroupLabels.md) | 183 | Name the groups a provider has sent. `$labels`: [['group' => code, 'label' => name]]. Only groups it has actually sent can be named; a blank name clears it. |
| [ssoGroupDisplay()](/code/functions/ssoGroupDisplay.md) | 200 | A group's name if it has one, else the code - for messages and the last-sign-in line. |
| [ssoRememberGroups()](/code/functions/ssoRememberGroups.md) | 216 | Record what a sign-in sent: the last sign-in line on the provider, and each group in sso_groups_seen for the dropdowns. Best effort - never fails a sign-in. |
| [ssoApplyGroupAccess()](/code/functions/ssoApplyGroupAccess.md) | 237 | At an analyst sign-in: admin from the admin group, team membership from the mappings. Returns what changed, for the log and the tests: ['admin' => true\|false\|null, 'added' => [team ids], 'removed' => [team ids]] |
| [ssoJitStartForTeams()](/code/functions/ssoJitStartForTeams.md) | 295 | When a provider maps groups to teams, the analysts it CREATES get their modules from those teams - so, unless the provider names default modules, they start with no modules of their own (the account would otherwise have every module, and the teams could add nothing to that). Called by `oidcCreateAnalyst()` right after the insert. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 | `ssoClaimsReady` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 43 | `ssoClaimsReady` | read [sso_group_teams](/code/tables/sso_group_teams.md) |  |
| 44 | `ssoClaimsReady` | read [sso_groups_seen](/code/tables/sso_groups_seen.md) |  |
| 127 | `ssoTeamMappings` | read [sso_group_teams](/code/tables/sso_group_teams.md), read [teams](/code/tables/teams.md) |  |
| 145 | `ssoSaveTeamMappings` | read [teams](/code/tables/teams.md) |  |
| 155 | `ssoSaveTeamMappings` | **delete** [sso_group_teams](/code/tables/sso_group_teams.md) |  |
| 156 | `ssoSaveTeamMappings` | **insert** [sso_group_teams](/code/tables/sso_group_teams.md) (`provider_id`, `group_name`, `team_id`) |  |
| 169 | `ssoSeenGroups` | read [sso_groups_seen](/code/tables/sso_groups_seen.md) |  |
| 185 | `ssoSaveGroupLabels` | **update** [sso_groups_seen](/code/tables/sso_groups_seen.md) (`label`) |  |
| 219 | `ssoRememberGroups` | **update** [auth_providers](/code/tables/auth_providers.md) (`oidc_groups_last_seen`) |  |
| 221 | `ssoRememberGroups` | **insert** [sso_groups_seen](/code/tables/sso_groups_seen.md) (`provider_id`, `group_name`, `first_seen_datetime`, `last_seen_datetime`) |  |
| 251 | `ssoApplyGroupAccess` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 254 | `ssoApplyGroupAccess` | **insert** [analyst_teams](/code/tables/analyst_teams.md) (`analyst_id`, `team_id`, `created_datetime`) |  |
| 255 | `ssoApplyGroupAccess` | **delete** [analyst_teams](/code/tables/analyst_teams.md) |  |
| 298 | `ssoJitStartForTeams` | read [sso_group_teams](/code/tables/sso_group_teams.md) |  |
| 301 | `ssoJitStartForTeams` | **update** [analysts](/code/tables/analysts.md) (`can_access_all_modules`) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md), [includes/services/analysts.php](/code/files/includes/services/analysts.php.md)

**Included by:** [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md), [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md), [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)

**Calls:** [analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md), [analystSignInReady()](/code/functions/analystSignInReady.md)

