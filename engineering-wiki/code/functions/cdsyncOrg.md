# cdsyncOrg()

Pull the department out of a structured `ORG` value.

`ORG:Acme Ltd;Finance` — component 1 is the organisation and component 2 the
department. FreeITSM has nowhere to put the organisation itself (a person's
company is `users.tenant_id`, a real relationship rather than a string), so
only the department is taken.

```php
function cdsyncOrg(array $lines): array
```

Defined in [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md), line 215.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | the card's unfolded lines from `cardDavUnfold()` |

**Returns:** `array`

## Calls

[cardDavProperty()](/code/functions/cardDavProperty.md), [cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md)

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cdsyncMapCard()](/code/functions/cdsyncMapCard.md)

