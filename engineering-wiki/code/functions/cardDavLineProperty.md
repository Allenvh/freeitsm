# cardDavLineProperty()

The property name of a logical line, upper-cased, or '' if it is not one.

⚠️ Strips a group prefix. `item1.TEL;TYPE=WORK:…` is how Apple Contacts writes
a property it wants to attach a label to, and reading the name as `ITEM1.TEL`
means every such line is invisible to both the reader and the writer.

```php
function cardDavLineProperty(string $logical): string
```

Defined in [includes/carddav_write.php](/code/files/includes/carddav_write.php.md), line 325.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$logical` | `string` |  | one unfolded content line |

**Returns:** `string`

## Called from

- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavLocateField()](/code/functions/cardDavLocateField.md)

