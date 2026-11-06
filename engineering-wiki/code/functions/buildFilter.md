# buildFilter()

Resolve a filter type + value into a SQL WHERE fragment + bound params. All filter types are validated against this whitelist; nothing user-supplied goes into the SQL string directly.

```php
function buildFilter(string $filter, string $value, ?string &$friendly): array
```

Defined in [api/intune/dashboard_drilldown.php](/code/files/api/intune/dashboard_drilldown.php.md), line 55.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filter` | `string` |  | the drill-down type from the request, e.g. 'compliance', 'os', 'last_sync', 'kpi_stale' |
| `$value` | `string` |  | the clicked value for that type (untrusted - always bound, never put in the SQL) |
| `$friendly` | `?string` |  | set by reference to a human label for the filter, shown above the device list |

**Returns:** `array`

## Called from

- [api/intune/dashboard_drilldown.php](/code/files/api/intune/dashboard_drilldown.php.md)

