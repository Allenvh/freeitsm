# user_sso_identities

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `user_id` | `INT NOT NULL` |  |
| `provider_id` | `INT NOT NULL` |  |
| `subject` | `VARCHAR(255) NOT NULL` |  |
| `email` | `VARCHAR(255) NULL` |  |
| `linked_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `last_login_datetime` | `DATETIME NULL` |  |
| `source_ref` | `VARCHAR(1000) NULL` | WHERE this person's record lives in the source, as opposed to WHAT the source calls them (`subject`, above). For LDAP it is the distinguished name; for CardDAV the URL of the individual .vcf. Named for the job rather than the protocol because `subject` already means two different things on this table depending on which provider owns the row.  🔴 Load-bearing for CardDAV write-back: without it, changing one contact's phone number means re-fetching the ENTIRE address book to find which card is theirs — slow, and a race against anybody else editing in between. |
| `source_etag` | `VARCHAR(255) NULL` | The version marker the source gave us for that record — a CardDAV ETag. Sent back as `If-Match` on a write, which is what makes the server refuse rather than clobber when the card changed underneath us. NULL for LDAP and OIDC, which have no equivalent. |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md):496 | `completeSelfServiceSso` | update | `last_login_datetime`, `email` |  |
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md):555 | `completeSelfServiceSso` | insert | `user_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime` |  |
| [api/tickets/unlink_user_signin.php](/code/files/api/tickets/unlink_user_signin.php.md):73 |  | delete |  |  |
| [includes/carddav_write.php](/code/files/includes/carddav_write.php.md):828 | `cardDavPushPersonChanges` | update | `source_etag` |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):878 | `dsyncLinkIdentity` | insert | `user_id`, `provider_id`, `subject`, `email`, `source_ref`, `source_etag`, `linked_datetime` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):778 | `ldapResolveUser` | update | `last_login_datetime`, `email` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):861 | `ldapResolveUser` | insert | `user_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) | completeSelfServiceSso L478 |  |
| [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md) | L325 |  |
| [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md) | L460 |  |
| [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md) | L116, L230, L231 |  |
| [api/system/debug-tools/D016_carddav_drift.php](/code/files/api/system/debug-tools/D016_carddav_drift.php.md) | L143 |  |
| [api/tickets/unlink_user_signin.php](/code/files/api/tickets/unlink_user_signin.php.md) | L49 |  |
| [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) | cardDavPushPersonChanges L701 |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) | dsyncFindExisting L430 |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md) | ldapResolveUser L754 |  |

