# cardDavCanWrite()

Does this account have permission to WRITE to the address book?

🔑 The server is asked rather than guessed at, and no list of "CardDAV servers
FreeITSM supports" is kept anywhere. Writing is plain `PUT` from RFC 6352 —
the same standard as the reads — so any server the import works against can in
principle be written to. What actually varies is PERMISSION: a shared or
subscribed address book is commonly read-only, and the alternative to asking
is finding out by failing halfway through somebody's edit.

⚠️ A server that does not report `current-user-privilege-set` at all is
treated as UNKNOWN, not as permitted. The setting then stays off, and the
screen says the server did not answer rather than pretending it said no.

```php
function cardDavCanWrite(array $cfg, string $bookHref): array
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 170.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | request settings from `cardDavConfigFromProvider()` |
| `$bookHref` | `string` |  | the address book's href, absolute or relative to `$cfg`['url'] |

**Returns:** array ['writable'=>bool, 'known'=>bool, 'privileges'=>string[], 'error'=>string]

## Calls

[cardDavAbsoluteUrl()](/code/functions/cardDavAbsoluteUrl.md), [cardDavExplainStatus()](/code/functions/cardDavExplainStatus.md), [cardDavRequest()](/code/functions/cardDavRequest.md)

## Called from

- [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md)
- [api/system/test_carddav_write.php](/code/files/api/system/test_carddav_write.php.md)

