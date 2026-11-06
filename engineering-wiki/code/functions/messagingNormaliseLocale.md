# messagingNormaliseLocale()

Map a raw client-reported language tag (e.g. Telegram's message.from. language_code — the user's own OS/app setting, "uk", "en", "pt-BR", never something they typed) onto one of this install's I18n::SUPPORTED_LOCALES keys. Unrecognised or empty input falls back to English — I18n::tFor() would do that anyway, but resolving it here keeps the fallback visible to the caller (e.g. for deciding whether to bother storing it).

```php
function messagingNormaliseLocale(string $raw): string
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 296.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the client-reported language tag, e.g. "en-US" or "pt-BR"; empty gives 'en' |

**Returns:** `string`

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingTelegramResolveIdentity()](/code/functions/messagingTelegramResolveIdentity.md)

