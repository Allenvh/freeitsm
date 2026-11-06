# api/domains/auth_code.php

> API endpoint - 28 lines

POST {id, action:'reveal'} — the decrypted auth code, recorded in the domain's history (unless switched off in Settings → Auth codes). POST {id, action:'set', code} — set or clear it (blank clears).

🔴 BOTH need Cap::DOMAINS_AUTH_CODES. This is a READ that returns a secret,
which is the one kind of read a capability guards (docs/design/rbac.md §9b.1):
whoever holds a domain's auth code can move the domain to a registrar of
their choosing.

## The endpoint

| | |
|---|---|
| URL | `api/domains/auth_code.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md)

**Calls:** [domainApiBody()](/code/functions/domainApiBody.md), [domainApiFail()](/code/functions/domainApiFail.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainRequireCap()](/code/functions/domainRequireCap.md)

