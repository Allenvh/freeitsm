# Corporate Active Directory sign in

FreeITSM supports native Active Directory over LDAPS or StartTLS alongside existing local and OpenID Connect sign in. Keycloak, Integrated Windows Authentication, and joining the application server to AD are not required.

## One domain first; additional domains are optional

AD is disabled on installation. Configure one trusted provider as both the default authentication provider and the sole authorization authority. Leave **Enable multiple authentication domains** unchecked. Only that provider can receive user credentials; the UI does not show a domain selector.

To support mirrored accounts, explicitly enable multiple authentication domains, add a second trusted provider, and optionally enable password fallback. Fallback is off by default; the default bind budget is one. A maximum of two user binds per login is supported. Domain selection is optional, behind a disclosure control; it narrows password attempts but never bypasses identity conflict checks. Service-account searches are not user-password attempts.

A corporate login is separate from local/OIDC login. Failed AD authentication never tries the supplied password against the local password store. Existing local/OIDC users keep their original login methods until an administrator explicitly enrolls their records in AD. A verified new corporate email cannot claim an existing account.

## Installation and upgrades

1. Back up MySQL and the encryption key before upgrading.
2. Build the PHP 8.4 image; the Dockerfile installs `libldap2-dev` and PHP `ldap`. No new Composer dependencies are needed.
3. For an existing database, apply `database/migrations/20261103_active_directory.sql` with your normal authenticated MySQL deployment tooling. It is additive and repeatable. Setup → DB Verify also executes this migration. Fresh installations include the same tables in `database/freeitsm.sql`.
4. Start `podman compose up -d app db` (or `docker compose up -d app db`). Keycloak and its database now use the optional `sso-lab` profile: `podman compose --profile sso-lab up -d`. Existing OIDC configurations continue to work with external identity providers.
5. Use System → **Active Directory & User Authorization** to configure providers, permissions and registration. Initially use a local unrestricted administrator.
6. Preserve the `includes/ad/guard.php` require at the end of your deployed `config.php`. The bundled Docker config already includes it. Custom configurations must include it **after defining DB credentials and BASE_URL**, on every page/API request; it must not be skipped by a custom bootstrap. For example: `require_once __DIR__ . '/includes/ad/guard.php';`.

The cloud onboarding install script is independent of this feature; it is not changed or republished by the PR. Rebuild deployed images using the updated repository Dockerfile.

## Provider configuration

Example single-domain configuration (fictional):

| Setting | Example |
| --- | --- |
| Name | Corporate AD |
| Domain DNS / UPN suffix | corp.example.test |
| Controller | dc1.corp.example.test |
| Secondary controller | dc2.corp.example.test |
| Transport / port | LDAPS / 636 |
| Base DN | DC=corp,DC=example,DC=test |
| User search base | OU=Employees,DC=corp,DC=example,DC=test |
| Group search base | OU=Security Groups,DC=corp,DC=example,DC=test |
| Service identity | svc-freeitsm@corp.example.test |
| Username / email / display | sAMAccountName / mail / displayName |
| Nested Security Groups | Enabled |
| Trusted / enabled / authentication | Enabled |

The read-only service account needs permission to read user identity/account-state attributes and Security Groups. It must not be Domain Admin. Service passwords use the existing AES-256-GCM encryption infrastructure and are never returned to the browser. Blank password fields preserve the stored value.

Controllers must be hostnames that match their certificates; credentials cannot be sent to arbitrary per-request servers. LDAP v3, disabled referrals, mandatory certificate validation, and bounded network/search timeouts apply to every connection. A secondary controller is tried for service connection failures. A user bind is attempted once per candidate; rejection never causes a second user bind against another controller. Account-disabled/locked/expired diagnostics stop fallback. LDAP filters escape values; configurable attribute names are validated.

**Trust settings:** set a provider's CA file to a readable PEM bundle mounted into the application container, or install enterprise roots in the system trust store. No validation-disable option exists. For example, add a local Compose override:

```yaml
services:
  app:
    volumes:
      - /srv/freeitsm/trust/corporate-ca.pem:/run/freeitsm-trust/corporate-ca.pem:ro
```

Then set CA file to `/run/freeitsm-trust/corporate-ca.pem`. On Podman systems with SELinux, apply the deployment's appropriate bind-mount labeling. Allow network access from the app to the configured controllers on TCP 636 (or 389 with StartTLS). HTTP proxies do not generally carry LDAP; configure your platform's supported TCP/private-network route. Also allow encrypted SMTP to the existing configured mail service.

For offline installations, build and export the app image in a trusted build environment with dependency mirrors and verified artifacts, then import that image into the disconnected Podman/Docker environment. Keep the image's vendor dependencies, LDAP extension, migration, CA bundle and encryption-key volume. No public discovery service or Keycloak call is made for AD login. The existing Composer manifest has no lockfile; pin and retain your verified release image for repeatable offline deployments.

## Registration and unified identity

Configure allowed email domains as exact comma-separated suffixes (for example `example.test`), a public HTTPS application base URL including any installation subpath, and an existing active SMTP mailbox ID with outbound enabled and TLS/SSL. The same SMTP transport and encrypted mailbox credentials used elsewhere in the application deliver verification mail. The base URL is administrator-configured; it is never derived from the untrusted request Host header.

Employees use **Corporate Active Directory sign in → Create an account or resend verification**. Registration responses are generic. Tokens are random, stored only as SHA-256 hashes, expire after 5–30 minutes (default 20), and are consumed atomically. Confirming a link consumes sibling resend tokens too. A confirmation POST avoids consuming links when a mail scanner fetches them. Verification creates only a pending corporate profile, never a session, requester, analyst, ticket grant, or module grant. Successful trusted AD authentication is still required.

A new profile receives one existing-model requester ID at first successful AD login and, only if mapped module access requires it, one analyst ID. Those IDs never change between domains or role changes. Existing ticket, comment, requester and assignment relationships remain in their original tables. User preferences are not overwritten; directory display names are synchronized.

Each linked external identity is keyed by provider ID plus raw 16-byte `objectGUID` represented as 32 lowercase hexadecimal characters. These are raw LDAP bytes, not the byte-order-adjusted display form of a Windows GUID. Trusted mirrored accounts must have the same normalized corporate email and username. Searches must return exactly one directory user and a single matching mail value. On first proof, resolved mirrors and the authorization identity are pinned to immutable GUIDs. A conflicting GUID, changed email, missing established mirror or ambiguous search requires administrator review; choosing another domain does not bypass this check.

Email possession alone never merges legacy profiles. Administrators can create a pending identity review, inspect the employee out of band, and explicitly attach existing requester and/or analyst IDs with a recorded review reason. This retains historical tickets and prevents duplicate accounts. Existing OIDC assignments must be reviewed separately before adopting an account. Controlled email changes require all previously linked providers to resolve the new address to the same established GUIDs. Existing internal IDs and GUIDs cannot be replaced via this screen. For an actual recycled account or conflicting person, do not reassign old history; create a separate reviewed identity after investigating the directory data.

## Centralized authorization and Guest

There is exactly one configured authority ID. In single-domain mode it must be the authentication provider. In multi-domain mode every elevated grant still comes exclusively from that authority; other domains' groups are never read for authorization. Directory-sourced email and immutable links are checked before using the dedicated read-only service account to fetch Security Groups.

Map authority group GUIDs to existing `access_groups`. Configure the existing group's explicit module keys, for example `tickets,knowledge` for Support, and `system` only for administrative roles. Changes to these shared groups also affect existing OIDC group members. All matching groups' module grants combine; the lowest numeric precedence determines the displayed role. Guest is implicit when there are no effective modules, including an empty mapped group. No hardcoded group names or automatic System grant exists for AD users.

Nested membership uses AD's LDAP_MATCHING_RULE_IN_CHAIN against Security Groups. Direct mode searches direct `member` values. Primary-group membership (which AD does not expose via `member`) is not used for elevated role mapping; use explicit application Security Groups. LDAP errors, timeouts, truncation/size limits and partial results fail closed rather than using an incomplete group list. A large directory may require narrower group search bases or higher server-side limits.

Guest uses the existing self-service portal and its requester ownership checks. It has no analyst session. A successful authoritative search returning no account or no mapped groups yields Guest. A failed lookup yields **Authorization unavailable**, not Guest. The last persisted successful authorization is retained for diagnostics, but its grants cannot be used while the status is unavailable. No directory-outage Guest operations are enabled by default.

## Refresh and sessions

Every successful AD login refreshes authorization. The next request after the configurable interval (default 900 seconds) refreshes it again. Administrative/security/integration/automation paths use a shorter freshness budget (default 60 seconds, configurable to 0 for every request). Settings and mapping revisions invalidate otherwise fresh grants. Request guards run before endpoint/page handlers, refresh persisted state, update the current session and enforce explicit module grants. Privileged requests fail closed on refresh failure.

**My Profile → Refresh My Permissions** is linked from the analyst and requester account menus. It shows effective access, source, refresh time and status, and limits manual refresh to once per 30 seconds. Administrators can inspect linked identities, matched groups, mappings, authentication/authorization history and force a lookup for any profile. Lookup/refresh buttons use the same authoritative service account and do not accept browser-supplied groups or roles. Sessions for other browsers pick up persisted changes on their next request.

AD-managed accounts cannot use legacy local login, password reset, local registration, OIDC auto-linking or local module-assignment endpoints to bypass enrollment. Generic local/OIDC accounts otherwise retain existing behavior. All new state-changing forms use CSRF tokens. Authentication session IDs are regenerated. AD passwords are never persisted or audited.

Before enabling AD, verify a working unrestricted, active local emergency administrator by ID and password. It must have no OIDC or AD enrollment. Its deletion, reassignment and local module changes are protected while AD is enabled. Keep its credentials securely offline. Local sign in remains reachable with `login.php?local=1`; disabling AD does not remove this recovery route. Reassign the protected ID through AD administration before modifying/removing that account. Production deployment should also enforce an appropriate MFA policy for emergency access.

## Diagnostics and troubleshooting

The administration screen provides latest profile status, last authentication provider, authorization authority/account, effective modules/role, linked GUIDs, matched groups, mapping rules and the latest 100 audit events per profile. Timestamps are UTC. Service secrets, user passwords and verification tokens are not displayed in diagnostics or audit records.

- `tls_*` / connection failure: check mounted CA readability, certificate hostname/chain, clock, TCP access and controllers. TLS failures are not treated as missing groups.
- `service_bind_failed`: check the read-only account and encrypted password; invalid service credentials are not retried across controllers.
- `ambiguous_identity` / `identity_review_required`: inspect duplicate mail/username attributes, reassigned emails and GUID links. Do not fix by bypassing verification or deleting historical IDs.
- `partial_directory_result` / `partial_group_resolution`: narrow the search bases or correct directory-side limits/permissions. Partial groups cannot grant privileges.
- `rate_limited`: wait for the fixed window. User login is limited per normalized email (5/15 minutes) and IP (30/15 minutes), registration per email (3/15 minutes) and IP (10/15 minutes), and refresh per profile. Configure AD lockout policies in conjunction with the optional second bind; these limits do not replace domain-controller lockout policy.
- Empty analyst permissions: ensure the mapped existing access group has explicit module rows. Empty never means full access for AD.
- New custom endpoints: include the standard config before authorization/output. Register any new module alias/sensitive administration route in `includes/ad/guard.php` and add access-boundary tests.

Audit events are in `ad_audit`; the existing data is not overwritten on failures. Set retention/purge policies for consumed/expired `ad_verifications`, aged `ad_rate_limits` rows, and `ad_audit` according to organizational policy. The feature deliberately retains immutable identity links and history; do not purge links merely to work around a conflict.

## Automated validation and rollout limits

`tests/ad/run.php` uses the production service and a mock `AdDirectory` with a real disposable MySQL schema. It **drops and recreates only `freeitsm_ad_test`**; never point it at a production server/account. It exercises one/two domains, fallback, disabled/locked/expired accounts, conflicting GUIDs, registration/token replay, authorization changes/outages, session freshness, rate limiting, encryption and local/OIDC compatibility. Directory wire behavior still requires staging validation with your real AD and certificates.

Example using the built image on a test Compose network:

```sh
docker run --rm --network freeitsm_default --entrypoint php \
  -v "$PWD:/src:ro" -e ENCRYPTION_KEY_PATH=/tmp/ad-test.key \
  -e AD_TEST_DB_HOST=db -e AD_TEST_DB_USER=root -e AD_TEST_DB_PASSWORD \
  freeitsm-app -r 'file_put_contents("/tmp/ad-test.key", bin2hex(random_bytes(32))); require "/src/tests/ad/run.php";'
```

For `tests/ad/http_smoke.py`, start an isolated HTTP container from the current image named `freeitsm-ad-http`, connected to the test database network, with DB_NAME=`freeitsm_ad_test`, test database credentials, and port `127.0.0.1:8088:80`. Use container-owned application files readable by Apache. Then run `python3 tests/ad/http_smoke.py`. The runner asserts the disposable database name before fixture writes. It verifies local login, administration/CSRF, anonymous and Support restrictions, requester access, stale authorization, generic errors and logout. It does not send mail or connect to production AD.

Before rollout, validate actual primary/secondary controller failover, LDAPS and StartTLS certificate rejection, nested groups, SMTP verification delivery, both mirrored passwords, and your OIDC provider in staging. Confirm reverse-proxy HTTPS, session-cookie settings and all sensitive module routes in your deployment. Real corporate AD/SMTP/IdP connectivity is not established by automated mocks. This implementation does not perform bulk AD synchronization, profile-history merging, or require a background worker.
