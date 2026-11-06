# api/self-service/login.php

> API endpoint - 192 lines

API: Self-Service Portal Login POST { identifier | email, password } Returns mfa_required: true if TOTP is enabled.

ONE BOX, THREE OUTCOMES
-----------------------
The requester types an email address OR a username, plus a password, and this
decides how to check it — the same shape the analyst login has used since the
directory work landed (login.php), so the two behave alike:

  a) we know them and they're pinned to a directory → LDAP bind
  b) we know them and they're local                 → password_verify
  c) we don't know them                             → ask the directories, and
                                                      create the account if one
                                                      answers (JIT)

(c) is the point of the feature: nobody wants to hand-create a portal account
for every new starter, and the people who most need the portal — staff with no
mailbox, who can't be reached by email in the first place — are exactly the
ones an admin is least likely to have pre-created.

An account pinned to an OIDC provider is refused here, as it has no password
to check; the login page routes those to the SSO button instead.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/login.php` |
| Protected by | Signed-in portal user (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 |  | read [users](/code/tables/users.md) |  |
| 135 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ldap.php](/code/files/includes/ldap.php.md), [includes/users.php](/code/files/includes/users.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ldapAccessRole()](/code/functions/ldapAccessRole.md), [ldapAuthenticate()](/code/functions/ldapAuthenticate.md), [ldapGetProvider()](/code/functions/ldapGetProvider.md), [ldapPortalProviders()](/code/functions/ldapPortalProviders.md), [ldapResolveUser()](/code/functions/ldapResolveUser.md), [requesterIsActive()](/code/functions/requesterIsActive.md), [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md), [userSignsInElsewhere()](/code/functions/userSignsInElsewhere.md)

