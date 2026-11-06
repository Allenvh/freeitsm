# api/system/test_ldap_connection.php

> API endpoint - 123 lines

API: Test an LDAP / Active Directory provider's settings. POST JSON { id?, ldap_host, ldap_port, ldap_encryption, ldap_bind_dn, ldap_bind_password, ldap_base_dn, ldap_user_filter, ldap_attr_*, test_username?, test_password? }

Proves, in order: we can reach the server, the service account can bind, and
the base DN is readable. If a sample username/password is supplied it also
runs a full end-to-end authentication and reports which attributes came back
— the fastest way to spot a wrong attribute name or filter.

Unlike the login form, this returns the REAL error. That is the point: an
admin debugging their own directory needs the detail, and this endpoint is
already restricted to System admins.

A blank/masked bind password means "use the one already stored for provider
`id`", so Test works without re-typing the secret.

## The endpoint

| | |
|---|---|
| URL | `api/system/test_ldap_connection.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ldap.php](/code/files/includes/ldap.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [ldapAccessRole()](/code/functions/ldapAccessRole.md), [ldapExtensionAvailable()](/code/functions/ldapExtensionAvailable.md), [ldapGetProvider()](/code/functions/ldapGetProvider.md), [ldapTestConnection()](/code/functions/ldapTestConnection.md)

