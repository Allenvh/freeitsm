# Keycloak + Active Directory SSO for FreeITSM

This is the supported foundation for on-prem/segregated SSO. FreeITSM validates OIDC ID tokens, then treats group/role claims as authorization input only after token validation.

## Lab compose

1. Copy `.env.example` to `.env` and change all passwords.
2. Start with Podman-compatible Compose: `podman compose --env-file .env up -d`.
3. Keycloak is exposed at `http://localhost:8081` by default.

The bundled Compose uses Keycloak `start-dev` for lab use only. Production should use Keycloak `start` with hardened TLS, hostname, proxy, database, backup, and secret handling.

## Realm and OIDC client

1. Create a realm such as `freeitsm`.
2. Create an OIDC client for FreeITSM.
3. Set the valid redirect URI to the FreeITSM callback path:
   `https://freeitsm.example.local/api/auth/oidc_callback.php`.
4. Enable standard authorization-code flow. Use confidential clients where possible.
5. In FreeITSM, add an OIDC provider manually using the realm issuer URL, client ID, and client secret. Keep local break-glass admin login enabled.

## Active Directory federation

In Keycloak, add LDAP/AD user federation:

- Vendor: Active Directory
- Connection URL: your LDAPS endpoint where possible
- Bind DN/account: least-privilege directory reader
- User DN and search filters scoped to FreeITSM users
- Import/sync mode appropriate to your environment

FreeITSM does not configure AD directly; Keycloak is the federation and claims authority.

## Recommended AD / Keycloak groups

Map AD security groups to Keycloak groups or roles:

- `FreeITSM-Guest`
- `FreeITSM-Support`
- `FreeITSM-Admin`
- `FreeITSM-SysAdmin`
- `FreeITSM-DevOps`

Add a client scope/protocol mapper so group or role values are included in the ID token. FreeITSM accepts common claim shapes: `groups`, `roles`, `realm_access.roles`, and `resource_access.<client>.roles`.

## FreeITSM group mapping

Run the database migration/db-verify, then map external values in `auth_provider_group_mappings` to seeded `access_groups`. Example:

```sql
INSERT INTO auth_provider_group_mappings (provider_id, external_group_value, access_group_id)
SELECT 1, 'FreeITSM-Support', id FROM access_groups WHERE group_key = 'support';
```

Effective module access is the union of existing individual `analyst_modules` and modules assigned through active `access_group_modules`. Manual group assignments remain intact; SSO-sourced assignments are refreshed on each SSO login.
