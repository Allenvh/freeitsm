# includes/carddav_create.php

> PHP - 352 lines

CardDAV — ADD a person to an address book (a new card), and link them.

For somebody who exists in FreeITSM (an email came in, a ticket was raised)
but not in the organisation's address book: an analyst presses "Add to
address book" instead of retyping them in another application.

──────────────────────────────────────────────────────────────────────────
🔑 WHY THIS FILE GENERATES A VCARD WHEN carddav_write.php SAYS NEVER TO.

That rule is about EXISTING cards: rendering one from FreeITSM's seven fields
would silently destroy the photo, notes and every property FreeITSM does not
model. A NEW card has none of those - there is nothing to destroy - so it has
to be built. Everything that touches an existing card here (the group card,
when the source imports a group) still edits the bytes, one line, with
If-Match. Kept in its own file so the import path, which must stay provably
read-only, never loads it.
──────────────────────────────────────────────────────────────────────────

🔴🔴 THE TRAP THIS IS BUILT AROUND.

A source may import only part of its book: one tag, or one group. A card
created outside that part is invisible to the next import, which would read
the person as having gone - and after `sync_deactivate_after` runs, mark them
as left. So:

  1. the card is created INSIDE the source's scope: with its tag, or added to
     its group;
  2. then the book is read back and the import's OWN functions
     (cdsyncResolveScope / cdsyncMapCard / cdsyncInScope) are asked whether
     the new card would be imported. Not a second copy of the rule - the
     rule;
  3. if it would not, for any reason, everything written is taken back and
     nobody is linked.

Duplicates: refused if any card in the book already has the person's email
address (the import would adopt that card instead - run it). FreeITSM's own
record is LINKED, never copied, and the link records the new card's UID, so
the next import finds the person by UID and neither creates nor adopts.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [cardDavCreateTargets()](/code/functions/cardDavCreateTargets.md) | 52 | Address books this person could be added to right now, and why not if none. |
| [cardDavCreatePreview()](/code/functions/cardDavCreatePreview.md) | 91 | What the new card would carry, for the confirm step and for the card itself. |
| [cardDavNewUid()](/code/functions/cardDavNewUid.md) | 110 | A random RFC 4122 v4 UUID - the card's UID, and its resource name. |
| [cardDavBuildCard()](/code/functions/cardDavBuildCard.md) | 128 | Build the new card. vCard 3.0: what the fixture, Baikal, Nextcloud and iCloud all store, and what `cdsyncMapCard()` reads. |
| [cardDavFindCardByUid()](/code/functions/cardDavFindCardByUid.md) | 169 | Find a card in a fetched book by UID. |
| [cardDavCreateContact()](/code/functions/cardDavCreateContact.md) | 186 | Add a person to an address book and link them to it. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 | `cardDavCreateTargets` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 67 | `cardDavCreateTargets` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 191 | `cardDavCreateContact` | read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |
| 336 | `cardDavCreateContact` | **update** [users](/code/tables/users.md) (`is_managed`, `auth_provider_id`, `last_seen_in_source`, `sync_missed_count`) |  |

## How it hangs together

**Includes:** [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), [includes/directory_sync.php](/code/files/includes/directory_sync.php.md)

**Included by:** [api/tickets/address_book_add.php](/code/files/api/tickets/address_book_add.php.md)

**Calls:** [cardDavAbsoluteUrl()](/code/functions/cardDavAbsoluteUrl.md), [cardDavConfigFromProvider()](/code/functions/cardDavConfigFromProvider.md), [cardDavDetectEol()](/code/functions/cardDavDetectEol.md), [cardDavExplainStatus()](/code/functions/cardDavExplainStatus.md), [cardDavFetchCards()](/code/functions/cardDavFetchCards.md), [cardDavGetCard()](/code/functions/cardDavGetCard.md), [cardDavLogWrite()](/code/functions/cardDavLogWrite.md), [cardDavProperty()](/code/functions/cardDavProperty.md), [cardDavPutCard()](/code/functions/cardDavPutCard.md), [cardDavRequest()](/code/functions/cardDavRequest.md), [cardDavScopeList()](/code/functions/cardDavScopeList.md), [cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md), [cardDavUnfold()](/code/functions/cardDavUnfold.md), [cdsyncInScope()](/code/functions/cdsyncInScope.md), [cdsyncMapCard()](/code/functions/cdsyncMapCard.md), [cdsyncResolveScope()](/code/functions/cdsyncResolveScope.md), [dsyncLinkIdentity()](/code/functions/dsyncLinkIdentity.md)

