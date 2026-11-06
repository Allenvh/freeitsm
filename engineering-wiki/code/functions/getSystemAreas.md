# getSystemAreas()

Every card on the System landing page, in display order: icon key, url, the i18n keys for title, description and search keywords, and an optional 'requires' gate.

```php
function getSystemAreas()
```

Defined in [system/includes/areas.php](/code/files/system/includes/areas.php.md), line 27.

**Returns:** array<int,array<string,string>> The ordered list of system areas.

## Called from

- [system/help/_registry.php](/code/files/system/help/_registry.php.md) - in [helpCards()](/code/functions/helpCards.md), [helpTopicsMissing()](/code/functions/helpTopicsMissing.md)
- [system/index.php](/code/files/system/index.php.md)

