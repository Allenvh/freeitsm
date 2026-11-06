# cardDavCreateContact()

Add a person to an address book and link them to it.

```php
function cardDavCreateContact(PDO $conn, int $userId, int $providerId, ?int $analystId): array
```

Defined in [includes/carddav_create.php](/code/files/includes/carddav_create.php.md), line 186.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | users.id of the person to add |
| `$providerId` | `int` |  | auth_providers.id of the address book to add them to; must be one `cardDavCreateTargets()` offers |
| `$analystId` | `?int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array ['ok'=>bool, 'error'=>string, 'code'=>string, 'existing'=>string] code: '' | not_allowed | duplicate | failed | out_of_scope

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 191 | `cardDavCreateContact` | read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |
| 336 | `cardDavCreateContact` | **update** [users](/code/tables/users.md) (`is_managed`, `auth_provider_id`, `last_seen_in_source`, `sync_missed_count`) |  |

## Calls

[cardDavAbsoluteUrl()](/code/functions/cardDavAbsoluteUrl.md), [cardDavBuildCard()](/code/functions/cardDavBuildCard.md), [cardDavConfigFromProvider()](/code/functions/cardDavConfigFromProvider.md), [cardDavCreatePreview()](/code/functions/cardDavCreatePreview.md), [cardDavCreateTargets()](/code/functions/cardDavCreateTargets.md), [cardDavDetectEol()](/code/functions/cardDavDetectEol.md), [cardDavExplainStatus()](/code/functions/cardDavExplainStatus.md), [cardDavFetchCards()](/code/functions/cardDavFetchCards.md), [cardDavFindCardByUid()](/code/functions/cardDavFindCardByUid.md), [cardDavGetCard()](/code/functions/cardDavGetCard.md), [cardDavLogWrite()](/code/functions/cardDavLogWrite.md), [cardDavNewUid()](/code/functions/cardDavNewUid.md), [cardDavProperty()](/code/functions/cardDavProperty.md), [cardDavPutCard()](/code/functions/cardDavPutCard.md), [cardDavRequest()](/code/functions/cardDavRequest.md), [cardDavScopeList()](/code/functions/cardDavScopeList.md), [cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md), [cardDavUnfold()](/code/functions/cardDavUnfold.md), [cdsyncInScope()](/code/functions/cdsyncInScope.md), [cdsyncMapCard()](/code/functions/cdsyncMapCard.md), [cdsyncResolveScope()](/code/functions/cdsyncResolveScope.md), [dsyncLinkIdentity()](/code/functions/dsyncLinkIdentity.md)

## Called from

- [api/tickets/address_book_add.php](/code/files/api/tickets/address_book_add.php.md)

