# csrfRequireToken()

For the rare GET that changes something and is called by the app itself (an EventSource stream that writes, for instance): require the token explicitly.

```php
function csrfRequireToken(): void
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 257.

**Returns:** `void`

## Calls

[csrfProvidedToken()](/code/functions/csrfProvidedToken.md), [csrfRefuse()](/code/functions/csrfRefuse.md), [csrfSameOrigin()](/code/functions/csrfSameOrigin.md)

## Called from

- [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md)
- [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md)
- [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md)
- [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md)
- [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md)

