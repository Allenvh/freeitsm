# classifyHash()

Classify a stored password hash. Returns: ['label','php_native'(bool can password_verify check it),'note','raw_algo'(for foreign match test)]

```php
function classifyHash($h)
```

Defined in [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md), line 79.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$h` |  |  | the stored password_hash value; empty means a passwordless account |

## Called from

- [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md)

