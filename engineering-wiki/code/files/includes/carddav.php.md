# includes/carddav.php

> PHP - 637 lines

CardDAV — the transport under directory sync's policy layer.

Directory sync already knows how to bring people into `users` safely: nobody
is ever deleted, a run that returns far fewer people than last time changes
nothing, and missing once is noise rather than a fact. None of that is about
LDAP, and none of it gets rebuilt here.

What this file is for is the half that IS about LDAP. Every extension point
in `includes/directory_sync.php` assumes the transport is LDAP: the columns
are named `ldap_attr_*`, paging uses `LDAP_CONTROL_PAGEDRESULTS`, identity is
`objectGUID` or `entryUUID`, and a manager is a distinguished name. CardDAV
is HTTP and vCard — no DNs, no paged controls, and identity is a URL plus an
ETag. So this is a fetch-and-map layer, deliberately narrow.

Asked for in https://github.com/edmozley/freeitsm/issues/133.
Wiki: CardDAV Contact Sync — Developer Guide.

──────────────────────────────────────────────────────────────────────────
🔴🔴 THE ONE THING TO KNOW BEFORE CHANGING ANYTHING HERE: AUTH.

A stock Baikal — the standard sabre/dav server, and what the reporter runs —
ships `dav_auth_type: Digest`. Measured against 0.12.1:

    Basic    -> 401
    Digest   -> 207
    anyauth  -> 207

Basic is the obvious choice and what almost every REST integration reaches
for, and against a default install it fails outright. The operator then sees
"authentication failed", checks a password that is correct, and concludes
FreeITSM is broken. So: CURLAUTH_ANY, and the connection test reports which
scheme actually won, because that is the one thing they cannot discover for
themselves.
──────────────────────────────────────────────────────────────────────────

## Functions

| Function | Line | What it does |
|---|--:|---|
| [cardDavRequest()](/code/functions/cardDavRequest.md) | 72 | One HTTP request to a DAV server. |
| [cardDavExplainStatus()](/code/functions/cardDavExplainStatus.md) | 193 | Turn an HTTP status into something an operator can act on. |
| [cardDavListAddressBooks()](/code/functions/cardDavListAddressBooks.md) | 225 | List the address books an account can see. |
| [cardDavParseAddressBooks()](/code/functions/cardDavParseAddressBooks.md) | 274 | Pull the address books out of a PROPFIND multistatus body. |
| [cardDavFetchCards()](/code/functions/cardDavFetchCards.md) | 335 | Fetch every vCard in one address book, in a single request. |
| [cardDavAbsoluteUrl()](/code/functions/cardDavAbsoluteUrl.md) | 393 | Resolve an href from a DAV response against the configured base URL. |
| [cardDavUnfold()](/code/functions/cardDavUnfold.md) | 419 | Unfold a vCard into logical lines. |
| [cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md) | 451 | Turn a vCard TEXT value into the plain string a human means by it. |
| [cardDavEscapeText()](/code/functions/cardDavEscapeText.md) | 482 | The inverse of `cardDavUnescapeText()` — a plain string as a vCard TEXT value. |
| [cardDavProperty()](/code/functions/cardDavProperty.md) | 500 | Read one property's values out of an unfolded vCard. |
| [cardDavScanBook()](/code/functions/cardDavScanBook.md) | 536 | What can this address book be scoped BY? |
| [cardDavScopeList()](/code/functions/cardDavScopeList.md) | 600 | Read `carddav_scope_value` into a list. |
| [cardDavConfigFromProvider()](/code/functions/cardDavConfigFromProvider.md) | 623 | Build the config array a request needs from an `auth_providers` row. |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [api/system/test_carddav_connection.php](/code/files/api/system/test_carddav_connection.php.md), [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md), [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md), [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), [system/sso/carddav.php](/code/files/system/sso/carddav.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md)

