# getHelpTopics()

Every System help page, keyed by the slug of its System area, with its hero, standfirst, sections (sidebar and search deep links) and extra search terms.

```php
function getHelpTopics()
```

Defined in [system/help/_registry.php](/code/files/system/help/_registry.php.md), line 37.

**Returns:** array<string,array<string,mixed>> Help topics, keyed by slug.

## Called from

- [system/help/_registry.php](/code/files/system/help/_registry.php.md) - in [getHelpTopic()](/code/functions/getHelpTopic.md), [helpCards()](/code/functions/helpCards.md), [helpTopicsMissing()](/code/functions/helpTopicsMissing.md)

