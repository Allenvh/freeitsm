# analystNameCopies()

Text columns that hold a COPY of an analyst's name next to their id. Anonymise replaces the copy where the id says it is theirs. [table, nameColumn, idColumn]. Plus the four audit tables' old/new values, replaced where the WHOLE value is the old name (an assignment change records names, not ids) - see `analystAnonymise()`. Names typed into free text (notes, emails) are not found.

```php
function analystNameCopies(): array
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 306.

**Returns:** `array`

## Called from

- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystAnonymise()](/code/functions/analystAnonymise.md)

