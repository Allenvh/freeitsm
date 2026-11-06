# attTextNormalise()

Reduce extracted XML/text to the words worth indexing.

OOXML puts every run of text in its own element, so stripping tags without
putting a space in their place welds words together — "Please" + "reboot"
becomes "Pleasereboot" and is then unfindable by either.

```php
function attTextNormalise(string $raw): string
```

Defined in [includes/search/extract.php](/code/files/includes/search/extract.php.md), line 117.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the text or XML read from the file |

**Returns:** `string`

## Called from

- [includes/search/extract.php](/code/files/includes/search/extract.php.md) - in [attTextExtractFile()](/code/functions/attTextExtractFile.md), [attTextExtractOoxml()](/code/functions/attTextExtractOoxml.md)
- [includes/search/tika.php](/code/files/includes/search/tika.php.md) - in [tikaExtract()](/code/functions/tikaExtract.md)

