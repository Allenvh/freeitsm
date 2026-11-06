# cardDavWritableFields()

How each writable person field is stored on a card.

⚠️ Exactly the five in USER_CARDDAV_OWNED, and for the same reason: a vCard
has nowhere to keep a payroll number, and almost nothing writes
`RELATED;TYPE=manager`, so employee ID and the reporting line are FreeITSM's
own and are never sent to the server.

  kind 'text'      — the whole value after the colon
  kind 'component' — one field inside a semicolon-separated value

```php
function cardDavWritableFields(): array
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 453.

**Returns:** `array`

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavLocateField()](/code/functions/cardDavLocateField.md), [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md), [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md)

