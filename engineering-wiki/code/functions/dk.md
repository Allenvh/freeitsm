# dk()

Escape a translated string, then drop pre-built HTML into its {placeholders}.

```php
function dk(string $key, array $html = []): string
```

Defined in [system/docker/index.php](/code/files/system/docker/index.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the string key under system.docker, e.g. 'not_container' |
| `$html` | `array` | `[]` | placeholder name => ready-built HTML (trusted, inserted unescaped) |

**Returns:** `string`

## Calls

[t()](/code/functions/t.md)

## Called from

- [system/docker/index.php](/code/files/system/docker/index.php.md)

