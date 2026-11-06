# api/system/debug-tools/D010_signin_methods.php

> API endpoint - 496 lines

Debug Tool D010 — Sign-in methods, and how you are actually signed in

Answers three questions that get confused with each other constantly:

  1. What sign-in methods exist, and what state is each one in?
  2. What will the login page actually OFFER a visitor right now?
  3. How did the person running this diagnostic get in?

(3) matters more than it looks. An administrator looking at the Authentication
screen has, by definition, already signed in — so "nobody can sign in" and
"everything is fine" look identical from that chair. Naming the route they
personally used turns an argument into a fact.

Written to be SENT TO SOMEBODY. Every username, email address, bind account and
DN is masked; secrets are reported present/absent and never printed. All the
diagnostic comparisons are computed on the real values and reported as
YES/NO/OK, so masking costs nothing.

Read-only: makes no network calls and writes nothing.

Output: plain text, section-delimited with === HEADERS ===.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D010_signin_methods.php` |
| Protected by | Signed-in analyst (session check) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 58 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [yn()](/code/functions/yn.md) | 67 | Formats a boolean as YES or NO for the report. |
| [mask()](/code/functions/mask.md) | 80 | Masks a value for a report that will be sent on - all stars when very short, otherwise the first one or two characters (and the last, for a long value) with stars between. |
| [maskEmail()](/code/functions/maskEmail.md) | 93 | Local part masked, domain kept — the domain is the diagnostic half. |
| [maskDn()](/code/functions/maskDn.md) | 110 | A DN carries a person's name in its leading component and the directory's shape in the rest. The shape is what you need to diagnose, the name is not — so mask every attribute VALUE and keep every attribute NAME and the commas. "CN=svc_freeitsm,OU=Service Accounts,DC=cnss,DC=local" -> "CN=sv******m,OU=Se******s,DC=cn***,DC=lo***" |
| [maskGuids()](/code/functions/maskGuids.md) | 129 | A tenant GUID in an issuer URL names the ORGANISATION, which is more than this report needs to be useful — and D003 already masks them for the same reason. The rest of the URL (which IdP, which realm shape) is the diagnostic half. |
| [maskHost()](/code/functions/maskHost.md) | 140 | A hostname is infrastructure, not a person — but strip any user@ prefix. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 153 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |
| [colExists()](/code/functions/colExists.md) | 191 | Whether a column exists in this database - false on any error, so an older install reads as not ready rather than breaking the report. |
| [tableExists()](/code/functions/tableExists.md) | 206 | Whether a table exists in this database - false on any error, so an older install reads as not ready rather than breaking the report. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 263 |  | read [analysts](/code/tables/analysts.md) |  |
| 298 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 311 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 347 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 441 |  | read [analysts](/code/tables/analysts.md) |  |
| 442 |  | read [analysts](/code/tables/analysts.md) |  |
| 443 |  | read [analysts](/code/tables/analysts.md) |  |
| 444 |  | read [analysts](/code/tables/analysts.md) |  |
| 446 |  | read [analysts](/code/tables/analysts.md) |  |
| 453 |  | read [users](/code/tables/users.md) |  |
| 454 |  | read [users](/code/tables/users.md) |  |
| 455 |  | read [users](/code/tables/users.md) |  |
| 457 |  | read [users](/code/tables/users.md) |  |
| 460 |  | read [user_sso_identities](/code/tables/user_sso_identities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

