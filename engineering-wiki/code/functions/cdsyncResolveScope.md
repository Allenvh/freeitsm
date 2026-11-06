# cdsyncResolveScope()

Which cards are in scope, resolved BEFORE anything is imported.

🔴 Group scope needs TWO passes and cannot be done card by card. A
`KIND:group` card lists its members by UID, so you have to read the group
card first and only then know whether an ordinary card belongs. Filtering in
one pass silently imports nobody when the group card happens to sort after
its members — which it usually does, alphabetically.

⚠️ MEMBER values are URIs: `urn:uuid:alice`, `mailto:a@b.c`, or a bare UID.
All three are legal and clients differ, so the scheme is stripped and both
the UID and the email are accepted as ways of naming a member.

```php
function cdsyncResolveScope(array $cards, string $scope, array $wanted): ?array
```

Defined in [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md), line 268.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cards` | `array` |  | the 'cards' list from `cardDavFetchCards()` - each with href, etag, vcard |
| `$scope` | `string` |  | auth_providers.carddav_scope - 'all', 'category' or 'group' |
| `$wanted` | `array` |  | the chosen tags or group UIDs/names, from `cardDavScopeList()`; empty means everything |

**Returns:** array ['uids' => set of in-scope UIDs, 'emails' => set of in-scope emails] or null when the scope is 'all' and everything is in scope.

## Calls

[cardDavProperty()](/code/functions/cardDavProperty.md), [cardDavUnfold()](/code/functions/cardDavUnfold.md)

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)

