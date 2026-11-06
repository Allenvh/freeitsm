# wtScopeIsValid()

True when `$scope` is one of the three Watchtower scopes: mine, team or all.

```php
function wtScopeIsValid(string $scope): bool
```

Defined in [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$scope` | `string` |  | the scope to check, e.g. a stored preference or a request value |

**Returns:** `bool`

## Called from

- [api/watchtower/get_dashboard.php](/code/files/api/watchtower/get_dashboard.php.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)
- [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md) - in [wtScopeFor()](/code/functions/wtScopeFor.md)

