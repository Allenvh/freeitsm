# oidcRedirectUri()

The redirect/callback URI registered in the IdP. Built from the deployment's BASE_URL so it matches whatever path the app is served at. MUST be identical in the login-redirect step and the token exchange.

```php
function oidcRedirectUri(): string
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 39.

**Returns:** `string`

## Calls

[requestScheme()](/code/functions/requestScheme.md)

## Called from

- [includes/oidc.php](/code/files/includes/oidc.php.md) - in [oidcBuildAuthUrl()](/code/functions/oidcBuildAuthUrl.md), [oidcExchangeCode()](/code/functions/oidcExchangeCode.md)

