# cdsyncMapCard()

Turn one vCard into the person array the policy layer expects.

🔑 NO CONFIGURABLE FIELD MAPPING, and that is a real difference from LDAP
rather than a shortcut. Directories disagree about everything — `mail` versus
`userPrincipalName`, `sAMAccountName` versus `uid` — which is why
`auth_providers` carries eleven `ldap_attr_*` columns. vCard is a
*specification*: `FN`, `EMAIL`, `TEL`, `TITLE` and `ORG` mean the same thing
on every server that speaks it. Offering a mapping screen would invite
somebody to break a working import by editing settings that only have one
correct answer.

The genuinely ambiguous choices are made here, in the open:

  phone / mobile — `TEL;TYPE=CELL` is the mobile; anything else is the phone,
                   preferring one marked WORK. A card with one untyped number
                   puts it in `phone`, because that is the field a service
                   desk rings first.
  department     — `ORG` is a *structured* value, `Organisation;Department`,
                   so the second component is the department. Most clients
                   write only the first, which correctly yields nothing.
  office         — the locality (town) from `ADR`, which is the part that
                   answers "which site are they at". The full address is not
                   stored: FreeITSM has nowhere for it and inventing a column
                   to hold data nobody asked for is how tables rot.
  employee_id    — NOT MAPPED. vCard has no payroll-number property, and
                   guessing one from `UID` would put an opaque server
                   identifier into a field an analyst reads as an HR number.

```php
function cdsyncMapCard(string $vcard, string $href): ?array
```

Defined in [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$vcard` | `string` |  | one raw vCard as the server sent it |
| `$href` | `string` |  | the card's href, kept as the 'dn' for logging and display |

**Returns:** array|null null when the card cannot be a person — no UID, or it is a group card rather than somebody.

## Calls

[cardDavProperty()](/code/functions/cardDavProperty.md), [cardDavUnescapeText()](/code/functions/cardDavUnescapeText.md), [cardDavUnfold()](/code/functions/cardDavUnfold.md), [cdsyncLocality()](/code/functions/cdsyncLocality.md), [cdsyncOrg()](/code/functions/cdsyncOrg.md), [cdsyncPhones()](/code/functions/cdsyncPhones.md)

## Called from

- [api/system/debug-tools/D016_carddav_drift.php](/code/files/api/system/debug-tools/D016_carddav_drift.php.md)
- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)
- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md)

