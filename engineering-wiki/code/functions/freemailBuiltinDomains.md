# freemailBuiltinDomains()

The built-in list of public free-email / consumer domains. These are ALWAYS treated as freemail; an admin can ADD to the list (the freemail_domains table, see getCustomFreemailDomains) but never remove a built-in — so gmail.com and friends can't be accidentally un-protected.

```php
function freemailBuiltinDomains(): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1265.

**Returns:** `array`

## Called from

- [api/system/add_freemail_domain.php](/code/files/api/system/add_freemail_domain.php.md)
- [api/system/get_freemail_domains.php](/code/files/api/system/get_freemail_domains.php.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [isFreemailDomain()](/code/functions/isFreemailDomain.md)

