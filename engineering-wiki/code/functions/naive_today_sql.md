# naive_today_sql()

🔴 "TODAY" FOR A BARE DATE COLUMN — the SQL literal, not a bound parameter.

The third kind of stored date has no time and no zone at all: a contract's end,
an asset's warranty expiry, a task's due date, a licence renewal, a knowledge
article's review date. "Is this contract expiring in the next 30 days" is a
question about a CALENDAR, and the answer must come from a calendar in the same
frame the date was typed in.

⚠️ `CURDATE()` is not that any more. With the connection pinned to UTC (GH #126,
config.php) it returns the UTC date, which rolls over an hour before the local
one in British summer time and two hours before it in central Europe. Left
alone, "due today" on Watchtower would change its answer for an hour every
night — the kind of off-by-one that gets reported as a ghost.

🔑 WHY A LITERAL AND NOT A PLACEHOLDER. Every caller below builds SQL by
assembling fragments and collecting bound parameters separately, several of
them into a `$where[]` array. Threading an extra parameter through each one
means getting its POSITION right in a list built somewhere else, which is a
much better way to introduce a bug than the one it would be preventing.

This is not user input and cannot become user input: the value is produced by
PHP's own `date()` and the format is asserted before it is returned, so what
comes back is always exactly `'YYYY-MM-DD'`, quotes included. If the assertion
could ever fail it falls back to `CURDATE()`, which is wrong by an hour rather
than wrong by being a syntax error on somebody's dashboard.

```php
function naive_today_sql(): string
```

Defined in [includes/timezone.php](/code/files/includes/timezone.php.md), line 572.

**Returns:** string A quoted SQL date literal, e.g. `'2026-09-01'`.

## Called from

- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetsList()](/code/functions/apiAssetsList.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractsList()](/code/functions/apiContractsList.md)
- [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) - in [apiSoftwareLicencesList()](/code/functions/apiSoftwareLicencesList.md)
- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiTasksList()](/code/functions/apiTasksList.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)
- [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) - in [workflowEmitContractExpiries()](/code/functions/workflowEmitContractExpiries.md), [workflowEmitWarrantyExpiries()](/code/functions/workflowEmitWarrantyExpiries.md)

