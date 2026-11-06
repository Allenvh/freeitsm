# fmt_datetime()

'25 Aug 2026 14:30'

```php
function fmt_datetime(?string $utc): string
```

Defined in [includes/timezone.php](/code/files/includes/timezone.php.md), line 478.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$utc` | `?string` |  | a UTC datetime as stored; null or '' gives '', an unparseable value comes back as it is |

**Returns:** `string`

## Calls

[_fmt_dt()](/code/functions/_fmt_dt.md)

## Called from

- [asset-management/scan.php](/code/files/asset-management/scan.php.md)
- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpFmtDateTime()](/code/functions/rpFmtDateTime.md)
- [people/includes/render.php](/code/files/people/includes/render.php.md) - in [pplSectionForms()](/code/functions/pplSectionForms.md)
- [tickets/csat/index.php](/code/files/tickets/csat/index.php.md)

