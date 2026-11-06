# analyst_sso_identities

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `provider_id` | `INT NOT NULL` |  |
| `subject` | `VARCHAR(255) NOT NULL` |  |
| `email` | `VARCHAR(100) NULL` |  |
| `linked_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `last_login_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md):195 |  | update | `last_login_datetime`, `email` |  |
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md):264 |  | insert | `analyst_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):648 | `ldapResolveAnalyst` | update | `last_login_datetime`, `email` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):700 | `ldapResolveAnalyst` | insert | `analyst_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) | L173 |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md) | ldapResolveAnalyst L621 |  |

