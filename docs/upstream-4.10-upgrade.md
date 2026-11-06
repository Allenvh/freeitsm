# Full upstream 4.10 upgrade for this fork

Imports edmozley/freeitsm at `61163c1a1bf17071079765a44d8fdef14708091e`
(4.10.0), including all upstream modules, self-service Request Catalogue and
Help Centre, form approvals/actions, roles and settings capabilities.

This supersedes the earlier LDAP-only import. Authentication, module access,
roles, mailbox APIs and their UI now use the upstream implementations together.
The retired custom mail/debug APIs and Webklex client are removed. Existing
native LDAP providers and linked identities keep their IDs. Historical custom AD
tables are retained; unmigrated custom AD profiles linked to accounts block the bridge rather than
silently falling back to local passwords. Resolve those with the previous release's
`scripts/migrate_legacy_ad.php` before upgrading.
Pending lookups that never linked an account can remain archived without blocking.

## What remains specific to the fork

- Existing Podman Compose services, ports, environment-based database credentials,
  volume names, GreenMail and Roundcube settings are preserved. Files and TLS
  receive new named volumes; existing database, attachments and key volumes retain
  their names. Keep the same Compose project name and working directory.
- The image uses PHP 8.4 on Debian bookworm and builds PECL IMAP 1.0.3 with TLS
  and Kerberos. Upstream's image omits IMAP; this image deliberately supports the
  Basic IMAP mailbox used by the local lab.
- Local corporate `.crt` files in `certs/` are installed into the container trust
  store. Certificate files stay untracked. Session-specific cloud test certificates
  are only mounted as build secrets, never committed or copied into the image.
- An explicit bridge preserves mailbox IDs, folder, host, ports, encryption,
  independently encrypted IMAP/SMTP passwords and existing analyst access.
- Migrated mailboxes retain their old UID-based message keys, preventing a message
  imported before the upgrade from creating another ticket if it is fetched again.
- Migrated mailbox rows retain the fork's optional SMTP sender address/name.
  The upstream UI uses the mailbox address/name for new mailboxes.

## Back up and prepare

Do this during a maintenance window. Back up the database, encryption-key volume,
attachments and any other uploads. Preserve the SAME encryption key: a new key
cannot decrypt existing mailbox or LDAP secrets. Test restoring the backup.

Upstream verification changes schema and includes some column/index drops after
moving old data. Rolling the image back alone does **not** roll the database back.
Recovery means restoring the database and files/key backups with the previous image.

Do not start the normal app or run background jobs while migrating. Build first:

```powershell
python -m podman_compose --env-file .env build app
python -m podman_compose --env-file .env stop app
```

Use your existing Compose command/env file if different. The commands below run
one-off PHP containers with the existing volumes; `--entrypoint php` deliberately
bypasses Apache and the background scheduler. Never use `down -v` for an upgrade.

## Migrate, in order

First preview the fork bridge. It prints only counts and identifiers, no credentials:

```powershell
python -m podman_compose --env-file .env run --rm --no-deps --entrypoint php app scripts/upgrade_fork.php
```

Review any blockers before applying. Supported legacy `imap_smtp` rows become
upstream `imap` rows with canonical encrypted password/server fields. Original
legacy fields remain in the database for reference; the new APIs do not return them.
Conflicting canonical values stop the migration. Independent intake/outbound-disabled
configurations and unauthenticated SMTP relays also require review: upstream does not
represent those exactly, and blank SMTP credentials mean "use IMAP credentials".

Then prepare compatibility data, apply upstream schema, and finish group conversion:

```powershell
python -m podman_compose --env-file .env run --rm --no-deps --entrypoint php app scripts/upgrade_fork.php --apply
python -m podman_compose --env-file .env run --rm --no-deps --entrypoint php app scripts/db_verify_cli.php
python -m podman_compose --env-file .env run --rm --no-deps --entrypoint php app scripts/db_verify_cli.php --apply
python -m podman_compose --env-file .env run --rm --no-deps --entrypoint php app scripts/upgrade_fork.php --finish
python -m podman_compose --env-file .env run --rm --no-deps --entrypoint php app scripts/upgrade_fork.php --finish --apply
```

Stop if a command fails. Each bridge phase records completion and is safe to rerun;
it does not reset permissions or create duplicate imported teams on later runs.
DDL is not transactional in MySQL, so a backup remains essential even when a failed
data transaction rolls back. Do not rerun old custom SQL migrations after this upgrade.

Active legacy access groups become restricted upstream teams. Team membership and
provider/group mappings move to the upstream tables. Individual module grants remain
individual; team grants are not frozen as permanent individual grants.

**Review System → Authentication → Groups & teams before relying on OIDC group sync.**
The old fork combined `groups` and `roles` claims; upstream asks you to choose one
claim. Set it to what your provider actually sends. Existing memberships are retained,
but automatic membership refresh needs that claim configured. Old groups granting
System preserve current administrator access; configure the upstream administrator
group explicitly if future group changes should grant/revoke administrator status.

**Review System → Analysts.** When the old schema lacks `is_admin`, the bridge
preserves administrators according to the fork's effective System access instead of
upstream's blanket promotion of every old analyst. Existing explicit admin flags are
not overwritten. Legacy unrestricted analysts may therefore remain administrators;
demote ordinary staff deliberately. Roles cannot restrict a System administrator.

Restart and sign in again so old sessions are replaced:

```powershell
python -m podman_compose --env-file .env up -d --force-recreate app
```

## Validate your deployment

1. Sign in with the retained local administrator, then an existing LDAP analyst and
   requester. Check provider settings, linked identities and company scope. Keep a
   working local administrator while testing directory sign-in.
2. Open the waffle menu: the registry now has 28 modules, including System. Use
   System → Module Access to grant ordinary analysts the modules they need; use
   System → Roles for settings capabilities. A non-admin with Knowledge access
   should read it but be refused Knowledge settings without the corresponding role.
3. Edit a form, expose it in the self-service Request Catalogue, select its approval
   policy and follow-up actions. Submit as a requester, approve/reject as appropriate,
   and confirm the expected ticket/task/email is created once.
4. Publish an appropriate Knowledge article for customers and open the self-service
   Help Centre. Confirm internal-only, archived and other-company articles stay hidden.
   System Wiki remains internal code documentation.
5. Open Tickets → Settings → Mailboxes. The migrated lab mailbox should show IMAP.
   Check `greenmail`, IMAP 3143 / SMTP 3025, encryption None for both, independent
   credentials and the intended folder. Verify the folder; no OAuth is required.
6. Send a uniquely titled email from Roundcube, check the mailbox and confirm one
   ticket. Reply from the ticket and confirm arrival in Roundcube. Check any intended
   notification/approval email separately; delivery of a reply is not proof every
   background notification is configured.
7. Check attachment downloads, Files persistence, and the scheduler's status before
   opening the deployment to users.

Automated regression results and remaining deployment checks are recorded in the PR.

## Reproducing the fork-specific tests

Use disposable databases only. `tests/fork-upgrade.php` refuses to run unless
`FREEITSM_TEST_DATABASE` equals the configured database. Load the pre-upgrade fork
schema, run `php tests/fork-upgrade.php prepare`, run upstream
`php scripts/db_verify_cli.php --apply`, then `php tests/fork-upgrade.php finish`.
The fixture deliberately leaves its rows in that throwaway database for inspection.

`php tests/mail/upstream-smtp.php` needs no database or external mail server.

`python3 tests/fork-upgrade-greenmail.py` and
`python3 tests/fork-upgrade-ldap.py` use the disposable `freeitsm-upgrade-test`
container on `127.0.0.1:8092`, configured for `freeitsm_upgrade_test`, on Docker's
`freeitsm-cloud_default` network. They start their own GreenMail/OpenLDAP fixtures,
generate credentials, and remove those server containers when finished. The app
container needs its encryption key in place and writable test PHP sessions under
`/tmp`; run the app with its scheduler disabled. Do not reuse these names for normal
installations. These tests leave fixture records in the throwaway app database.

The upstream suites under `tests/` document their own requirements. Some older
Knowledge visibility scripts assume named demo companies and article defaults that
are absent from a clean upgraded fixture. The maintained Help Centre document suite
does create its own articles; run it on a two-company fixture to exercise isolation.
The older LDAP empty-password suite also assumes an unreachable host times out
slowly; an immediate network rejection invalidates that timing control. The real
LDAP fixture above checks valid and invalid binds without that assumption.
