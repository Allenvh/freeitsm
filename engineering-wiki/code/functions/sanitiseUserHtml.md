# sanitiseUserHtml()

Clean customer-authored HTML down to the allow-list.

```php
function sanitiseUserHtml(string $html): string
```

Defined in [includes/html_sanitise.php](/code/files/includes/html_sanitise.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$html` | `string` |  | raw editor output |

**Returns:** string safe HTML, or '' when there is nothing left worth keeping

## Calls

[sanitiseUserHtmlNode()](/code/functions/sanitiseUserHtmlNode.md)

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [mergeBuildSnapshotHtml()](/code/functions/mergeBuildSnapshotHtml.md)

