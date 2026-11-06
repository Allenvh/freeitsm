# cardDavPutCard()

Write a card back, refusing if it has changed on the server since we read it.

🔴🔴 `If-Match` IS THE WHOLE SAFETY MODEL AND IS NOT OPTIONAL. Without it a
`PUT` is last-writer-wins: an analyst edits a phone number, and in doing so
silently reverts the address the customer's own office updated an hour ago in
Outlook, because our copy of the card was fetched last night. With it, the
server compares ETags and answers 412 — and 412 is not a failure, it is the
feature. It means "somebody else got there first", and the honest response is
to re-read the card and apply the change to the current version.

⚠️ An EMPTY etag is refused outright rather than sent as `If-Match: *`, which
means "any existing card" and would be exactly the unguarded overwrite this
exists to prevent. No stored ETag means we have not really read this card, and
the correct move is to fetch it, not to write over it.

```php
function cardDavPutCard(array $cfg, string $cardUrl, string $vcard, string $etag): array
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 74.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | request settings from `cardDavConfigFromProvider()` |
| `$cardUrl` | `string` |  | the card's absolute URL |
| `$vcard` | `string` |  | the whole card to store, already edited |
| `$etag` | `string` |  | the ETag the card had when it was read, sent as If-Match; '' refuses the write |

**Returns:** array ['ok'=>bool, 'status'=>int, 'etag'=>string, 'conflict'=>bool, 'error'=>string]  `etag` is the card's NEW tag when the server returned one; '' means re-read before writing again.

## Calls

[cardDavExplainStatus()](/code/functions/cardDavExplainStatus.md), [cardDavRequest()](/code/functions/cardDavRequest.md)

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md)

