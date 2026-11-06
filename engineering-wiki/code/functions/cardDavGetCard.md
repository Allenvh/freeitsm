# cardDavGetCard()

Fetch one card by its own URL, with the ETag the server currently holds.

```php
function cardDavGetCard(array $cfg, string $cardUrl): array
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 128.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | request settings from `cardDavConfigFromProvider()` |
| `$cardUrl` | `string` |  | the card's absolute URL |

**Returns:** array ['ok'=>bool, 'vcard'=>string, 'etag'=>string, 'error'=>string]

## Calls

[cardDavExplainStatus()](/code/functions/cardDavExplainStatus.md), [cardDavRequest()](/code/functions/cardDavRequest.md)

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md)

