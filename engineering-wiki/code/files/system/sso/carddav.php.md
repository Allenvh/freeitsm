# system/sso/carddav.php

> PHP - 854 lines

System → Authentication → one CardDAV address book.

WHY A PAGE AND NOT THE DIALOG
-----------------------------
Ed asked the question and the answer was already written down one file over:
`provider.php` exists because a directory's settings "had all been bolted onto
the modal on the list page, where the import section ended up below the fold
and people reasonably concluded it was not there."

A CardDAV source went the same way within a day. It has a connection, a book
to choose, a test whose result is several lines, a list of groups or tags to
tick, Preview and Run, and a run history — and the modal had already needed
sticky chrome to stay usable, which was the same symptom.

⚠️ THREE TABS, AND NO FIELD MAPPING TAB — deliberately, not yet. Directories
need one because they disagree about attribute names; vCard is a
specification, so `FN`, `EMAIL`, `TEL` and `TITLE` mean the same thing
everywhere and the mapping has exactly one correct answer. See the docblock
of `cdsyncMapCard()` for the ambiguous calls and why each was made.

⚠️ The page furniture CSS below duplicates provider.php's. That is a known
cost, recorded in TODO.local.md rather than pretended away: extracting it is
a genuine refactor because provider.php's block interleaves LDAP-specific
mapping and history rules with the shared ones, and the right moment is when
this page grows those same tabs.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [v()](/code/functions/v.md) | 86 | Print a value into an input safely. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/carddav.php](/code/files/includes/carddav.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/confirm.js](/code/files/assets/js/confirm.js.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [cardDavScopeList()](/code/functions/cardDavScopeList.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [t()](/code/functions/t.md)

