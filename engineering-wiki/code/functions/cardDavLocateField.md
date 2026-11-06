# cardDavLocateField()

The line a given field's value lives on, or null if the card has no such line.

🔑 For the two telephone numbers this defers to `cdsyncPhoneLines()`, the SAME
selector the importer reads through. "The mobile" is a selection rule over
several `TEL` lines rather than a property name, and a writer with its own
copy of that rule would eventually disagree with the reader — overwriting the
desk number when asked to change the mobile, silently and with no error.

```php
function cardDavLocateField(array $spans, string $field): ?int
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 479.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$spans` | `array` |  | `cardDavLineSpans()` of the card |
| `$field` | `string` |  | a person field from `cardDavWritableFields()`, e.g. 'mobile'; any other gives null |

**Returns:** `?int`

## Calls

[cardDavLineProperty()](/code/functions/cardDavLineProperty.md), [cardDavWritableFields()](/code/functions/cardDavWritableFields.md), [cdsyncPhoneLines()](/code/functions/cdsyncPhoneLines.md)

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md)

