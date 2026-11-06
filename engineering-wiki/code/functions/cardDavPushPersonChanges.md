# cardDavPushPersonChanges()

Push a person's changed fields to their card in the address book.

──────────────────────────────────────────────────────────────────────────
🔴🔴 THE CONCURRENCY DESIGN, WHICH IS THE HARD PART AND NOT OBVIOUS.

There are three plausible schemes and two of them are wrong.

  WRONG 1 — PUT with the ETag stored at import time. Safe, but it refuses
  whenever ANY field on the card changed since the last sync, including ones
  FreeITSM does not even read. An analyst correcting a phone number would be
  blocked because somebody added a birthday last week.

  WRONG 2 — GET the card, edit it, PUT with the ETag that GET just returned.
  This never refuses anything, which sounds convenient and in fact means the
  concurrency guard has been switched off: `If-Match` can no longer fail,
  because we just asked the server which value would make it pass.

  WHAT THIS DOES — GET the card fresh, and before editing, compare the
  server's CURRENT value of each field being written against the value
  FreeITSM held before this save. If they differ, somebody else changed that
  same field, and the write is refused as a real conflict. If they match, the
  edit is applied to the fresh bytes so everybody else's changes to other
  properties survive, and the PUT carries the fresh ETag purely to close the
  few milliseconds between the GET and the PUT.

That is a per-field three-way merge, and it is the only one of the three that
both preserves other people's work and notices when two people changed the
same thing.
──────────────────────────────────────────────────────────────────────────

🔴 THIS NEVER FAILS THE CALLER'S SAVE. FreeITSM's own record has already been
written by the time this runs, and rolling it back because somebody else's
server is unreachable would throw away the analyst's work to report a problem
they cannot do anything about. Every outcome is returned for the UI to
mention, and none of them is an exception.

⚠️ Except for the self-service portal, which calls this BEFORE its save and
saves only on success (api/self-service/update_profile.php says why). The
function itself is the same either way; `$fromPortal` only changes what the
write log says about who made the change.

```php
function cardDavPushPersonChanges(
    PDO $conn,
    int $userId,
    array $changes,
    array $previous,
    ?int $analystId = null,
    bool $fromPortal = false
): array
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 688.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | users.id of the person whose card is updated |
| `$changes` | `array` |  | field => the value just saved |
| `$previous` | `array` |  | field => the value FreeITSM held BEFORE this save |
| `$analystId` | `?int` | `null` | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$fromPortal` | `bool` | `false` | the person changed it themselves, in the portal |

**Returns:** array ['attempted'=>bool, 'ok'=>bool, 'changed'=>string[], 'conflict'=>bool, 'error'=>string, 'reason'=>string] `reason` explains an attempted=false, which is the ordinary case for every person who is not a CardDAV contact.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 701 | `cardDavPushPersonChanges` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md), read [user_sso_identities](/code/tables/user_sso_identities.md) |  |
| 828 | `cardDavPushPersonChanges` | **update** [user_sso_identities](/code/tables/user_sso_identities.md) (`source_etag`) |  |

## Calls

[cardDavAbsoluteUrl()](/code/functions/cardDavAbsoluteUrl.md), [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md), [cardDavConfigFromProvider()](/code/functions/cardDavConfigFromProvider.md), [cardDavGetCard()](/code/functions/cardDavGetCard.md), [cardDavLogWrite()](/code/functions/cardDavLogWrite.md), [cardDavPutCard()](/code/functions/cardDavPutCard.md), [cardDavWritableFields()](/code/functions/cardDavWritableFields.md), [cdsyncMapCard()](/code/functions/cdsyncMapCard.md)

## Called from

- [api/self-service/update_profile.php](/code/files/api/self-service/update_profile.php.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)

