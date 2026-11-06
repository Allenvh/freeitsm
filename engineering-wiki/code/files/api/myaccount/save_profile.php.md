# api/myaccount/save_profile.php

> API endpoint - 58 lines

API: this analyst's own profile details — the ones a signature merges. POST { job_title, department, phone, mobile }

Name and email are deliberately NOT editable here. They identify the account, are
used to sign in and to address mail, and on an install using LDAP or SSO they come
from the directory — letting somebody edit their own would either be overwritten on
the next sync or quietly diverge from it.

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/save_profile.php` |
| Protected by | Signed-in analyst (session check) (via includes/i18n.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | **update** [analysts](/code/tables/analysts.md) (`job_title`, `department`, `phone`, `mobile`, `last_modified_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ssoAnalystProfileLocked()](/code/functions/ssoAnalystProfileLocked.md), [t()](/code/functions/t.md)

