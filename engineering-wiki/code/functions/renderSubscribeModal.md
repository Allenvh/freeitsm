# renderSubscribeModal()

Prints the "subscribe to this calendar feed" dialogue - feed address, QR code and phone instructions - with the labels given.

```php
function renderSubscribeModal(string $id, array $labels): void
```

Defined in [includes/subscribe_modal.php](/code/files/includes/subscribe_modal.php.md), line 28.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` | `string` |  | prefix for every element id, so two can coexist |
| `$labels` | `array` |  | title, intro, address_label, address_hint, url_label, copy, ios_label, ios_hint, android_label, android_hint, reset, close, insecure, secret_note |

**Returns:** `void`

## Called from

- [calendar/index.php](/code/files/calendar/index.php.md)
- [system/preferences/index.php](/code/files/system/preferences/index.php.md)

