# cardDavBuildCard()

Build the new card. vCard 3.0: what the fixture, Baikal, Nextcloud and iCloud all store, and what `cdsyncMapCard()` reads.

```php
function cardDavBuildCard(string $uid, array $preview, ?string $organisation): string
```

Defined in [includes/carddav_create.php](/code/files/includes/carddav_create.php.md), line 128.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$uid` | `string` |  | the new card's UID from `cardDavNewUid()` |
| `$preview` | `array` |  | the result of `cardDavCreatePreview()` - fields, scope, scope_value |
| `$organisation` | `?string` |  | the person's company name, written as the first part of ORG; null or '' leaves it empty |

**Returns:** `string`

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)

