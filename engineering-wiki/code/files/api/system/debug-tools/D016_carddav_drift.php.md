# api/system/debug-tools/D016_carddav_drift.php

> API endpoint - 259 lines

Debug Tool D016 — Where do FreeITSM and the address book disagree?

A top-to-bottom comparison: every person imported from a CardDAV address book,
against the card they came from, field by field.

⭐ THIS IS ALSO THE ANSWER TO A QUESTION NOBODY ANSWERED. #133 asked for
two-way sync; the reply asked the reporter which details actually drift, and
in which direction, because that is the one piece of evidence that would shape
it. No answer came. This measures it instead of asking — and it works on the
operator's own data, which is better evidence than anybody's recollection.

🔴 READ-ONLY. It changes nothing on either side, in either direction. It does
not write to the address book, it does not update `users`, and it deliberately
offers no "fix it" button: deciding which side is right is exactly the
judgement a person has to make, and a one-click reconcile over hundreds of
contacts is how a careful integration becomes a data-loss incident.

⚠️ It reads the WHOLE address book, so it is slower than the other tools here.
That is inherent - there is no way to ask a CardDAV server "which of these
changed" without fetching them.

Output: plain text, section-delimited with === HEADERS ===.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D016_carddav_drift.php` |
| Protected by | Signed-in analyst (session check) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 63 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 73 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |
| [shownVal()](/code/functions/shownVal.md) | 82 | Show a value, or say plainly that there isn't one. Never a bare blank. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 89 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 143 |  | read [users](/code/tables/users.md), read [user_sso_identities](/code/tables/user_sso_identities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), [includes/users.php](/code/files/includes/users.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [cardDavConfigFromProvider()](/code/functions/cardDavConfigFromProvider.md), [cardDavFetchCards()](/code/functions/cardDavFetchCards.md), [cdsyncMapCard()](/code/functions/cdsyncMapCard.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

