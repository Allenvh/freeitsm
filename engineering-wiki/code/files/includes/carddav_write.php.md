# includes/carddav_write.php

> PHP - 836 lines

CardDAV write-back — editing a contact card without rewriting it.

Asked for in https://github.com/edmozley/freeitsm/issues/133, where the
reporter's argument is that a telephone number changes DURING the phone call,
and having to then correct it in a second application is exactly what an ITSM
tool should stop you doing.

──────────────────────────────────────────────────────────────────────────
🔴🔴 WHY THIS IS A SEPARATE FILE FROM includes/carddav.php.

The import is provably read-only: it issues `PROPFIND` and `REPORT` and
nothing else, which is the honest answer when an operator asks whether
connecting FreeITSM can damage their address book. `PUT` lives here, in a file
the import path never loads, so that guarantee stays a fact about the code
rather than a promise about intentions.
──────────────────────────────────────────────────────────────────────────

🔴🔴 AND THE ONE RULE THAT SHAPES EVERYTHING BELOW: NEVER GENERATE A VCARD.

The obvious implementation is to take the seven person fields, render a vCard
and PUT it. That silently destroys everything on the card FreeITSM does not
model — the photo, the birthday, the notes, the street and postcode (only the
town is imported), every `X-` property somebody's CRM writes, and the
`CATEGORIES` line that decides whether the card is even in scope on the next
run. It would look like it worked. The operator would discover it months
later, with no way back.

So this edits the bytes the server gave us. A property whose value changed has
its line rewritten in place; every other line, understood or not, is passed
through untouched. That also disposes of the vCard 3.0 versus 4.0 question,
which otherwise needs a policy: the card keeps whatever `VERSION` it already
declares, because nothing rewrites it.

⚠️ The safety here is deliberately ASYMMETRIC with the import. A bad import
damages OUR data and can be re-imported. A bad write-back damages the
operator's address book and cannot be undone by us at all. So write-back
touches only fields FreeITSM owns, only on cards it imported, and only when a
human deliberately edited one — there is no bulk reconciliation pass, and
there should never be one.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [cardDavPutCard()](/code/functions/cardDavPutCard.md) | 74 | Write a card back, refusing if it has changed on the server since we read it. |
| [cardDavGetCard()](/code/functions/cardDavGetCard.md) | 128 | Fetch one card by its own URL, with the ETag the server currently holds. |
| [cardDavCanWrite()](/code/functions/cardDavCanWrite.md) | 170 | Does this account have permission to WRITE to the address book? |
| [cardDavFoldLine()](/code/functions/cardDavFoldLine.md) | 231 | Fold one logical line the way RFC 6350 §3.2 asks: break at 75 octets and begin each continuation with a single space. |
| [cardDavDetectEol()](/code/functions/cardDavDetectEol.md) | 268 | Which line ending does this card use? |
| [cardDavLineSpans()](/code/functions/cardDavLineSpans.md) | 287 | Split a card into logical lines, remembering where each one physically began and ended. |
| [cardDavLineProperty()](/code/functions/cardDavLineProperty.md) | 325 | The property name of a logical line, upper-cased, or '' if it is not one. |
| [cardDavReplaceLineValue()](/code/functions/cardDavReplaceLineValue.md) | 354 | Replace the VALUE of the logical line at `$index`, keeping its name, its group prefix and every one of its parameters exactly as they were. |
| [cardDavInsertLine()](/code/functions/cardDavInsertLine.md) | 381 | Add a property that the card does not currently have, immediately before `END:VCARD`. |
| [cardDavRemoveLine()](/code/functions/cardDavRemoveLine.md) | 403 | Remove the logical line at `$index`, its continuations and its line ending. |
| [cardDavSetComponent()](/code/functions/cardDavSetComponent.md) | 433 | Set one component of a structured value, leaving its siblings alone. |
| [cardDavWritableFields()](/code/functions/cardDavWritableFields.md) | 453 | How each writable person field is stored on a card. |
| [cardDavLocateField()](/code/functions/cardDavLocateField.md) | 479 | The line a given field's value lives on, or null if the card has no such line. |
| [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md) | 506 | Apply a set of FreeITSM field changes to a card, surgically. |
| [cardDavLogWrite()](/code/functions/cardDavLogWrite.md) | 610 | Record one write-back attempt. |
| [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md) | 688 | Push a person's changed fields to their card in the address book. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 616 | `cardDavLogWrite` | **insert** [carddav_write_log](/code/tables/carddav_write_log.md) (`provider_id`, `user_id`, `display_name`, `outcome`, `fields`, `http_status`, `server_response`, `message`, `triggered_by_analyst_id`, `created_datetime`) |  |
| 701 | `cardDavPushPersonChanges` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md), read [user_sso_identities](/code/tables/user_sso_identities.md) |  |
| 828 | `cardDavPushPersonChanges` | **update** [user_sso_identities](/code/tables/user_sso_identities.md) (`source_etag`) |  |

## How it hangs together

**Includes:** [includes/carddav.php](/code/files/includes/carddav.php.md), [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md)

**Included by:** [api/self-service/update_profile.php](/code/files/api/self-service/update_profile.php.md), [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md), [api/system/debug-tools/D016_carddav_drift.php](/code/files/api/system/debug-tools/D016_carddav_drift.php.md), [api/system/test_carddav_write.php](/code/files/api/system/test_carddav_write.php.md), [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md), [includes/carddav_create.php](/code/files/includes/carddav_create.php.md)

**Calls:** [cardDavAbsoluteUrl()](/code/functions/cardDavAbsoluteUrl.md), [cardDavConfigFromProvider()](/code/functions/cardDavConfigFromProvider.md), [cardDavEscapeText()](/code/functions/cardDavEscapeText.md), [cardDavExplainStatus()](/code/functions/cardDavExplainStatus.md), [cardDavRequest()](/code/functions/cardDavRequest.md), [cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md), [cdsyncMapCard()](/code/functions/cdsyncMapCard.md), [cdsyncPhoneLines()](/code/functions/cdsyncPhoneLines.md)

