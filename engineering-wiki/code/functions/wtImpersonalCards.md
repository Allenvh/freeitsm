# wtImpersonalCards()

The cards with no notion of an owner.

⚠️ Assets is in here and it looks as though it should not be. An asset is
assigned to a USER (users_assets), and an analyst is not a user — they are
different tables and an analyst frequently has no user row at all. "My
assigned assets" would therefore be empty for most people, which reads as
"nothing to worry about" rather than "this question does not apply to you".

```php
function wtImpersonalCards(): array
```

Defined in [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md), line 78.

**Returns:** `array`

## Called from

- [api/watchtower/get_dashboard.php](/code/files/api/watchtower/get_dashboard.php.md)
- [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md) - in [wtCardCanScope()](/code/functions/wtCardCanScope.md)

