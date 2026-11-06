# aiProviderTraceStart()

Record every HTTP exchange this file makes until `aiProviderTraceTake()`.

For debug tool D014 (GH #171: "is there a way to inspect the payload and
response without dev tools?"). Browser dev tools can never show it, because
the call is made by the SERVER - the browser only ever talks to FreeITSM.
Off unless started, so no ordinary request pays for it.

```php
function aiProviderTraceStart(): void
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 404.

**Returns:** `void`

## Called from

- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)

