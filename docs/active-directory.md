# Native Active Directory authentication

FreeITSM can authenticate directly to AD over LDAPS or StartTLS. Keycloak, Windows
Integrated Authentication, server domain membership, and a second domain are not
required. Existing local and OIDC sign-in remain available. New settings default
to **disabled**, with multi-domain authentication **off** and no authorization
domain selected.

## Deployment and upgrades

1. Rebuild the PHP 8.4 image (`podman compose build app`, or Docker Compose). The
   Dockerfile installs `libldap2-dev` and the PHP LDAP extension.
2. Back up the database. On existing installations run
   `database/migrations/20261102_active_directory.sql` with your normal MySQL
   migration tooling. The migration is additive and repeatable. Fresh installations
   receive the same tables from `database/freeitsm.sql`. It does not rewrite existing
   employee, analyst, ticket, OIDC, or module assignments. The existing DB Verify
   tool does not apply this migration; run the SQL explicitly.
3. Mount enterprise CA certificates **read-only**, outside the web root, into the
   app container. Configure `ca_file` to the mounted PEM bundle's absolute path.
   An empty path uses the system trust store. Never disable certificate validation.
4. Allow the app to reach configured DCs over TCP 636 (LDAPS) or TCP 389 (StartTLS).
   Managed cloud deployments need the platform's supported private-network/TCP
   configuration. HTTP egress alone does not provide LDAP connectivity.
5. Sign in with an existing unrestricted local administrator, open **System →
   Security → Active Directory**, and create a provider. The initial protected local
   administrator ID is 1; choose another active unrestricted local account if
   needed. The selected account cannot be linked to AD, disabled, deleted, assigned
   an SSO provider, or restricted through the standard analyst-management handlers.
6. Test a directory lookup, choose the default authentication provider, and enable
   AD. Corporate sign-in is linked from both existing login pages. Employees may
   also open `ad-sign-in.php` directly.

Offline installations must preload the PHP/MySQL images and Composer dependencies
through their approved artifact mirror/cache. No runtime public identity service
is required. Email verification still requires an enterprise SMTP mailbox.

Example provider connection JSON (fictional names):

```json
{
  "host": "dc1.corp.example",
  "secondary_host": "dc2.corp.example",
  "port": 636,
  "transport": "ldaps",
  "timeout": 5,
  "ca_file": "/run/enterprise-ca/ca-bundle.pem",
  "user_base": "OU=Employees,DC=corp,DC=example",
  "group_base": "DC=corp,DC=example",
  "bind_dn": "svc-freeitsm@corp.example",
  "email_attribute": "mail",
  "name_attribute": "displayName",
  "nested_groups": true
}
```

Searches use a dedicated read-only bind identity, including authentication account
resolution. Its password uses the existing AES-256-GCM encryption-key infrastructure.
No Domain Admin privileges are required. LDAP v3, bounded search/network timeouts,
validated TLS, referral suppression, escaped filters and unambiguous account
resolution are mandatory. An invalid user password does not trigger DC failover
or a local-password fallback. Connection/service-bind failures can use the secondary
DC; incomplete searches fail closed. Group queries require an untruncated result;
large directories may need narrower group search bases. Nested security-group
membership uses AD's matching-rule-in-chain, including the user's primary group.

## Four independent operating modes

| Authentication | Authorization domain | Behavior |
| --- | --- | --- |
| Single domain (default) | Unset (default) | Only the default provider receives credentials; existing FreeITSM permissions apply. |
| Multiple trusted domains | Unset | Linked mirrored accounts share an employee; no AD group authorization queries occur. |
| Single domain | Selected | The selected authority supplies AD-managed grants; it may be the same domain. |
| Multiple trusted domains | Selected | Either permitted authenticator can sign in; only the selected authority supplies AD-managed grants. |

A deployment may store multiple providers while single-domain mode is active.
Only the explicitly selected default provider can authenticate in that mode.
Multi-domain mode uses administrator-defined priority, a maximum of one or two
password attempts per login, and explicit trust before automatic fallback/linking.
Automatic resolution considers at most two trusted providers in priority order.
Default attempt limit is one; increase it deliberately if automatic two-password
fallback is needed. Users can select a configured provider when resolution is
ambiguous. Credentials are never broadcast to arbitrary servers. Directory errors
stop the attempt; account lockout, disabled/expired accounts and invalid credentials
never fall back to local passwords.

## Registration and unified identity

Set allowed corporate email domains, the fixed HTTPS public deployment URL, the
existing outbound SMTP mailbox ID, and verification expiry (15–30 minutes). The
SMTP mailbox must be active, send-capable, and configured for TLS/SSL. The existing
SMTP mailer and encrypted mailbox settings are reused. Links use the fixed URL,
not a browser-supplied Host header. The web UI offers initial delivery and resend.
Replies do not disclose whether the address exists, is disallowed, is throttled,
or delivery failed; administrators can inspect delivery-failure audit events.

Verification links contain random 256-bit single-use tokens. Only SHA-256 hashes
are stored. Confirming the link creates a passwordless employee profile without an
authenticated session. A successful directory bind is still required. Token
consumption and profile creation are transactional; expired/replayed tokens fail.
The verification page requires explicit confirmation so mail scanners do not
consume tokens merely by fetching a URL. Clean up expired verification/rate-limit
records according to your database retention policy.

The existing `users.id` remains the ticket-owning employee ID. `ad_profiles` links
that employee to an optional analyst record, and `ad_identities` pins each provider
and raw AD objectGUID to one employee. Existing accounts are **not claimed or merged
by self-registration or an email match alone**. An administrator must review them
and use the reconciliation form with the existing employee ID and an independently
verified directory GUID. An existing analyst may be explicitly linked after review;
the emergency account cannot be linked. Do not approve a claim based only on email.

Automatic linking of a second domain requires a verified profile and explicit trust
on both providers. A different GUID in an already-linked provider is a conflict.
An email change on a linked GUID requires administrator review and a controlled
update of the existing employee email through existing user administration; keep
its user ID and review the authority pin. Reassigned email addresses cannot silently
replace pinned identities. To approve replacement of a linked account, supply its previous GUID explicitly in
the reconciliation form after independent review. The operation audits both GUIDs,
revokes existing AD grants and invalidates its authority pin. Merging unrelated
profiles remains prohibited; never use replacement to transfer one employee's
history to another.

New employees receive existing self-service access. They cannot claim a local
password on an AD-managed profile, and auto-created AD analysts cannot use local
login/password reset to bypass the directory. Existing manually linked local/OIDC
accounts keep their independent authentication methods.

## Optional group authorization

Leave **Authorization domain** empty to retain existing FreeITSM RBAC. No AD group
lookups or permission-refresh jobs run in this mode. Existing manual permissions
are retained; new employees stay in self-service until independently granted access.

Select one enabled provider to opt in. Test directory lookup and map security-group
GUIDs to **existing access groups**. GUIDs are the 32-character raw binary hex form
returned by the diagnostic adapter (not the differently ordered hyphenated AD display
GUID). Configure the group's modules in existing RBAC tables/tooling. Do not assume
that a seeded role name grants permissions: an empty access group grants none.
Higher mapping priority wins; equal-priority matches combine their module grants.
Only this authority's mappings participate. No match, or a successful lookup confirming
no account, supplies no elevated AD grant and leaves the employee in self-service
unless independently assigned permissions apply.

AD grants are stored in existing `analyst_access_groups` with source `ad`. Other
sources and explicit module assignments are preserved. Auto-provisioned analysts
never inherit the legacy "no module rows means unrestricted" behavior. An analyst
is created only when a mapped group is found, with a random unusable local password;
a conflicting existing analyst email requires administrative linking first.
Disabling/changing the authority removes AD grants and increments a configuration
revision. Existing sessions recalculate on their next request. Disabling AD or its
login provider revokes its directory sessions. Changing modes is explicit and audited;
a directory outage never activates local authorization automatically.

Authorization refreshes on login and on requests after the configured interval
(default 900 seconds). Analyst/sensitive requests require information at most
60 seconds old. A central guard in both application configurations checks every
PHP entry point using the configuration, recomputes modules from persisted grants,
and rejects unauthorized module requests. Administrative ticket APIs also require
explicit system access. Independent local sessions retain existing behavior.

Employees can open **My permissions** from the account menu and choose **Refresh
My Permissions**. Administrators can inspect linked identities, effective modules,
group GUIDs, refresh status and the latest 50 audit events, test a directory lookup,
and force-refresh an employee. Manual refreshes are rate-limited. Failures retain
last-known stored grants for diagnosis but deny elevated use. Only read-only
self-service operations remain available during an authorization outage. A later
successful lookup is required to restore AD-managed privilege. No background worker
is required. Refreshes do not overwrite preferences or ticket relationships.

## Validation and release checks

Run `tests/ad/run.php` against an isolated MySQL database; see `tests/ad/README.md`.
It exercises the real persistence, configuration, identity/linking, registration,
authorization and request middleware with an injected fake directory. Check all
changed PHP files with `php -l`, rebuild the LDAP-enabled image, verify
`extension_loaded('ldap')`, and run the normal setup/local-login smoke workflow.
The migration must succeed both on an existing schema and when reapplied.

Before production rollout, use a **dedicated staging AD**, never production account
lockout tests, to verify these external integration behaviors:

- LDAPS and StartTLS certificate-chain and hostname verification; untrusted/expired
  certificates and cleartext LDAP must fail.
- Primary DC outage and secondary DC service-bind availability, bounded timeouts,
  invalid-password lockout behavior, account expiration and password-expiry codes.
- Unique `mail` resolution, duplicate emails and immutable GUID behavior on renames.
- Direct, nested and primary security-group memberships, group search-base scope,
  and denial on partial/truncated group resolution.
- Enterprise SMTP delivery and verification URL correctness behind the actual proxy.
- Existing OIDC round trip, local emergency login, and tenant-scoped ticket access.

No corporate AD, SMTP, or external OIDC credentials are bundled with automated
checks. These live interoperability checks remain release gates until exercised
in the deployment's staging network. Existing legacy RBAC semantics still apply to
independently assigned permissions; audit those assignments before enabling AD.
