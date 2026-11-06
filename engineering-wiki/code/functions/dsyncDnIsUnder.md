# dsyncDnIsUnder()

Is `$dn` inside the subtree rooted at `$ancestor` (or the ancestor itself)?

⚠️ The comma matters. Without it "OU=Sales,DC=x" tests true against
"OU=WholesaleSales,DC=x", and a carve-out would silently swallow an OU whose
name merely ends the same way.

```php
function dsyncDnIsUnder(string $dn, string $ancestor): bool
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 204.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dn` | `string` |  | the entry's distinguished name (compared case-insensitively) |
| `$ancestor` | `string` |  | the branch DN to test against; empty always gives false |

**Returns:** `bool`

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncScopes()](/code/functions/dsyncScopes.md), [dsyncDnIsExcluded()](/code/functions/dsyncDnIsExcluded.md)

