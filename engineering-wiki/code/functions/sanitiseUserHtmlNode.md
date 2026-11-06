# sanitiseUserHtmlNode()

Walk a node's children, removing or unwrapping anything not allow-listed. Iterates over a SNAPSHOT of the child list because the loop mutates it.

```php
function sanitiseUserHtmlNode(DOMDocument $doc, DOMNode $node): void
```

Defined in [includes/html_sanitise.php](/code/files/includes/html_sanitise.php.md), line 104.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$doc` | `DOMDocument` |  | the document the user's HTML was loaded into |
| `$node` | `DOMNode` |  | the node whose children are cleaned, in place and recursively |

**Returns:** `void`

## Called from

- [includes/html_sanitise.php](/code/files/includes/html_sanitise.php.md) - in [sanitiseUserHtml()](/code/functions/sanitiseUserHtml.md), [sanitiseUserHtmlNode()](/code/functions/sanitiseUserHtmlNode.md)

