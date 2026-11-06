# handoverDocumentCss()

The handover document's stylesheet, in one place (discussion #56).

Shared by the printable page, the designer's live preview and the emailed
copy. Kept as a PHP function rather than a .css file for one reason: the
emailed version has to carry its styles inline in the message, so the same
text has to be available as a string, not only as a URL a mail client would
refuse to fetch.

⚠️ Deliberately NOT themed. A handover is printed and filed, so it is always
dark ink on white paper — a dark-mode print wastes toner and reads badly, and
the analyst's theme is not the document's business.

```php
function handoverDocumentCss(): string
```

Defined in [includes/handover_styles.php](/code/files/includes/handover_styles.php.md), line 25.

**Returns:** `string`

## Called from

- [api/assets/email_handover.php](/code/files/api/assets/email_handover.php.md)
- [asset-management/handover.php](/code/files/asset-management/handover.php.md)
- [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md)

