# webchatEmbedSnippet()

The <script> embed snippet a customer pastes just before </body> on their site.

```php
function webchatEmbedSnippet(PDO $conn, string $widgetKey): string
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 67.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$widgetKey` | `string` |  | the widget's public key, written into the snippet |

**Returns:** `string`

## Calls

[webchatBaseUrl()](/code/functions/webchatBaseUrl.md)

## Called from

- [api/webchat/get_widgets.php](/code/files/api/webchat/get_widgets.php.md)
- [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md)

