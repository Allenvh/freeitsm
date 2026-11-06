# ssoDnLines()

Normalise a list of DNs (an array, or newline-separated text) for storage.

⚠️ These strings are handed to `ldap_search()` as the SEARCH BASE, which is a
position in the tree rather than a filter — so filter escaping does not apply
and would not help. What matters is that nothing but a DN is ever stored:

 - control characters and newlines are stripped, since a newline is the
   record separator here and one embedded in a value would forge an entry;
 - anything without an '=' is not a DN and is dropped rather than stored and
   failed at run time, when the message would be far from the cause;
 - the list is deduplicated and capped, because it comes from a browser and
   a browser is not a promise.

Whether the base is one the bind account may read is the DIRECTORY's decision,
not ours: an unreadable base returns nothing, and a base outside the tree
errors. Neither leaks anything the bind account could not already read.

```php
function ssoDnLines($value): ?string
```

Defined in [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the DNs from the request - an array, or newline-separated text (untrusted) |

**Returns:** `?string`

## Called from

- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)

