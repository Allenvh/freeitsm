# cardDavUnescapeText()

Turn a vCard TEXT value into the plain string a human means by it.

RFC 6350 §3.4: within a text value, `\\`, `\,`, `\;` and `\n` (or `\N`) stand
for a backslash, a comma, a semicolon and a line break. Everything else after
a backslash is that character.

🔴 A SINGLE LEFT-TO-RIGHT PASS, not a sequence of str_replace calls. Chained
replacement gets `\\,` wrong in both directions: unescaping `\\` first yields
`\,`, which the next pass then reads as an escaped comma — so a value that
legitimately ended in a backslash before a comma comes out having silently
lost the separator. Scanning once means an escape can never be re-read as the
start of another.

⚠️ This was MISSING for TITLE and TEL and present for ORG and ADR, so two of
the four mapped text fields imported a job title of `Head\, Sales` with the
backslash still in it. Harmless-looking while the sync was one-way; the moment
a value is written back it would be re-escaped to `Head\\, Sales` and the card
would gain a backslash on every run.

```php
function cardDavUnescapeText(string $v): string
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 451.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` | `string` |  | an escaped vCard TEXT value as it appears after the colon |

**Returns:** `string`

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cdsyncMapCard()](/code/functions/cdsyncMapCard.md), [cdsyncPhones()](/code/functions/cdsyncPhones.md), [cdsyncOrg()](/code/functions/cdsyncOrg.md), [cdsyncLocality()](/code/functions/cdsyncLocality.md)
- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavApplyPersonEdits()](/code/functions/cardDavApplyPersonEdits.md)

