# Native LDAP / Active Directory authentication

This fork now uses the upstream FreeITSM LDAP authentication core from `edmozley/freeitsm` commit `61163c1a1bf17071079765a44d8fdef14708091e` (`includes/ldap.php` and `includes/sso_identity.php`, under the repository's MIT license). Its UI and API integration are adapted to this fork's existing paths and schema. This is a selective authentication integration, not a wholesale upgrade to the newer upstream application.

## What changed

Configure directories on **System → Authentication / SSO** (`system/sso/`), by clicking **+ Add LDAP**. The **+ Add OIDC** button opens the separate OpenID Connect dialog. The normal analyst login (`login.php`) and self-service login (`self-service/login.php`) accept directory passwords. LDAP shares `auth_providers`, `analyst_sso_identities` and `user_sso_identities` with OIDC, as upstream does. Local and OIDC accounts continue using their own assigned authentication method; failed directory binds never try a local password. Existing TOTP remains required for accounts that enabled it.

The custom AD implementation has been retired: `includes/ad`, corporate verification registration, unified mirrored-domain identity, the separate authorization authority, group-GUID-to-role mappings and periodic/manual directory authorization refresh are removed. Old AD page URLs redirect to the normal login/Authentication pages. Old custom sessions are signed out. Legacy tables and the historical migration are retained for recovery; they no longer grant permissions. A small transition quarantine prevents existing legacy accounts being claimed before migration.

Native upstream behavior is different:

- A person is assigned to one directory provider. Mirrored accounts are not automatically merged across domains.
- Directory group gates are evaluated at sign-in. There is no periodic directory-group refresh during active sessions in this integration.
- **Analyst group** members may use the analyst login; **Self-service group** members may use the portal. Membership in both grants the analyst role and portal eligibility. A person matching neither configured group is denied.
- With both group gates blank, upstream treats authenticated directory users as analyst-eligible. Configure group gates before enabling an enterprise directory.
- Analyst and self-service auto-provisioning switches are separate. Auto-provisioning defaults off for imported/new providers.
- Default module keys apply to newly created analysts. Blank means all modules; a nonempty list restricts new analysts. Existing accounts retain their own module assignments. LDAP sessions enforce these explicit grants without the fork's legacy empty-means-all fallback.
- No email verification, SMTP mailbox or public registration URL is required for LDAP login.

The existing Generic IMAP/SMTP fixes, mail tests, local Podman/GreenMail/Roundcube Compose configuration and other fork changes are preserved.

## Upgrade and migrate existing custom AD accounts

1. Keep a working unrestricted **local administrator** account and verify its password. Do not migrate that emergency account.
2. Back up the entire database and the encryption-key volume/file. Keep the old release image for rollback. Run the following operations during a maintenance window; do not run old and new login implementations concurrently.
3. Build the new application image and recreate **only the app** using your existing environment file:

   ```sh
   python -m podman_compose --env-file .env.example build app
   python -m podman_compose --env-file .env.example up -d --no-deps --force-recreate app
   ```

4. Run Setup → **DB Verify**, or initialize the additive native schema from the app container:

   ```sh
   python -m podman_compose --env-file .env.example exec app php -r 'require "config.php"; require "includes/functions.php"; require "includes/ldap_schema.php"; ensureNativeLdapSchema(connectToDatabase());'
   ```

   Fresh installations include the new fields in `database/freeitsm.sql`. The helper also supports older databases and can be rerun. It adds LDAP settings, explicit module permissions, a nullable requester email/username, tenant association and migration ledgers without resetting existing records.

5. Inspect the migration plan; the default invocation does not modify any data:

   ```sh
   python -m podman_compose --env-file .env.example exec app php scripts/migrate_legacy_ad.php
   ```

6. After reviewing the backups and plan, explicitly apply:

   ```sh
   python -m podman_compose --env-file .env.example exec app php scripts/migrate_legacy_ad.php --apply
   ```

   Application scripts do not run this automatically. Imported providers are **disabled** and auto-provisioning is **off**. Service credentials are retained encrypted using the existing key. The migration pins each existing requester/analyst ID to its previously successful directory identity, creates upstream-format links using the immutable raw GUID bytes, retains tickets/assignments, and snapshots current explicit module permissions. It does not merge accounts or rewrite ticket relationships. For an unavailable authorization snapshot it grants no elevated modules; review that account before enabling it. Repeated runs skip already migrated accounts.

   Pending registrations without requester/analyst records remain archived and are not provisioned. Ambiguous or missing identity links, disabled legacy profiles, conflicting existing provider assignments and attempts to migrate the emergency local administrator stop and roll back the data migration. Resolve these explicitly before rerunning; do not delete history to force it through. Schema initialization occurs before the data transaction because MySQL DDL commits implicitly.

7. Sign in locally and review each imported provider in **System → Authentication / SSO**:
   - Verify server DNS name, port, transport, service DN and search bases/attributes.
   - Set explicit analyst/self-service group names or DNs and review default modules and auto-provisioning.
   - Configure enterprise CA trust in the container. The old per-provider CA-file and secondary-controller fields are archived, not silently translated; upstream uses the selected server and system trust store with an eight-second timeout.
   - Test the service bind, then an optional sample user's password and group role.
   - Enable only the reviewed provider. Do not reset existing users' passwords; directory users authenticate against the directory.

8. Verify existing employees can sign in, see the same ticket history, retain intended module access, and still complete their configured TOTP step. Verify local emergency login and OIDC as well. Retain backups and legacy tables until that review completes.

Rollback means restoring the backed-up database/key and previous release together. Do not attempt to roll back only PHP code after changing provider assignments.

## New provider examples

Select the AD or OpenLDAP preset. Example AD values (fictional):

| Field | Example |
| --- | --- |
| Directory hostname | dc1.corp.example.test |
| Port / transport | 636 / LDAPS |
| Service identity | svc-freeitsm@corp.example.test |
| User base | OU=Employees,DC=corp,DC=example,DC=test |
| Group base | OU=Groups,DC=corp,DC=example,DC=test |
| Username / email / display / immutable ID | sAMAccountName / mail / displayName / objectGUID |
| Analyst group | FreeITSM-Analysts |
| Self-service group | FreeITSM-Portal |
| Default analyst modules | tickets,knowledge,assets |

Filters use `%s`, replaced with an escaped login or user DN. The AD preset resolves nested groups using LDAP_MATCHING_RULE_IN_CHAIN. Groups can be identified by name or full DN; the retired raw-GUID mapping field is not used. Passwords remain encrypted and are never returned in provider lists. Blank service passwords on edits/tests reuse the stored secret.

Serve the application login over HTTPS in production; HTTP is for isolated local testing. Use a read-only service account, not Domain Admin. Choose LDAPS or StartTLS with trusted certificates for production. Plain LDAP is exposed only for intentionally isolated testing, consistent with upstream support. Do not disable certificate validation. Install enterprise roots with the existing `certs/*.crt` build mechanism or the platform's system trust configuration. The server name must match its certificate. The app container needs DNS and TCP access to the chosen directory; container `localhost` is not the host or domain controller.

Directory-assigned accounts cannot use local-password reset or local self-registration to bypass their provider. Deleting a provider with assigned accounts is refused; reassign them deliberately first. Migrated users retain their original requester/analyst IDs. Existing local/OIDC account behavior and module conventions remain unchanged until explicitly assigned to LDAP or an explicit module policy.

## Upstream integration boundaries and validation

The adopted core retains upstream password/NUL guards, service/user binds, escaped filters, nested AD groups, immutable identity links, provider isolation, separate JIT switches, and mailbox-less requester support. The compatibility changes are limited to this fork's tenancy helper name, optional preference templates (the newer preference-template subsystem is not imported), transition quarantine, existing admin/CSRF controls, and login/permission integration. This does **not** import unrelated upstream directory synchronization, CardDAV, Entra provisioning, claim/team mappings, preference templates or a complete upstream release.

`tests/native_ldap/run.php` creates and drops **only `freeitsm_native_ldap_test`** on its configured test MySQL server. It covers fresh installation, pre-LDAP upgrade, repeatability, local compatibility, JIT, immutable identity protection, group gates, module restrictions and non-destructive legacy migration. Native directory wire tests and HTTP smoke checks are described in that test directory. Existing `tests/mail` remain applicable.

Actual corporate AD/LDAPS certificates and deployed OIDC/SMTP must still be validated in staging. The migration does not prove your directory's reachability or group configuration.
