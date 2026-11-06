# auth_providers

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `display_name` | `VARCHAR(100) NOT NULL` |  |
| `protocol` | `VARCHAR(20) NOT NULL DEFAULT 'oidc'` |  |
| `issuer_url` | `VARCHAR(500) NOT NULL` |  |
| `client_id` | `VARCHAR(255) NOT NULL` |  |
| `client_secret` | `VARCHAR(500) NULL` |  |
| `scopes` | `VARCHAR(255) NOT NULL DEFAULT 'openid email profile'` |  |
| `ldap_host` | `VARCHAR(255) NULL` | LDAP / Active Directory (protocol = 'ldap') Mutually exclusive with the OIDC columns above. ldap_bind_password is the service account's password, encrypted at rest via `encryptValue()`. ldap_attr_guid names the immutable id attribute (objectGUID on AD, entryUUID on OpenLDAP) used as the stable `subject` link — a DN is not safe for that, it changes when a user is renamed or moved between OUs. |
| `ldap_port` | `INT NULL` |  |
| `ldap_encryption` | `VARCHAR(10) NULL` |  |
| `ldap_bind_dn` | `VARCHAR(255) NULL` |  |
| `ldap_bind_password` | `VARCHAR(500) NULL` |  |
| `ldap_base_dn` | `VARCHAR(255) NULL` |  |
| `ldap_user_filter` | `VARCHAR(500) NULL` |  |
| `ldap_attr_username` | `VARCHAR(64) NULL` |  |
| `ldap_attr_email` | `VARCHAR(64) NULL` |  |
| `ldap_attr_name` | `VARCHAR(64) NULL` |  |
| `ldap_attr_guid` | `VARCHAR(64) NULL` |  |
| `ldap_group_base_dn` | `VARCHAR(255) NULL` | Group gating (issue #47). ldap_analyst_group / ldap_user_group name the directory groups that grant access; both blank = gate off (anyone the directory authenticates becomes an analyst). ldap_group_filter finds the groups a user is in — %s is their DN. |
| `ldap_group_filter` | `VARCHAR(500) NULL` |  |
| `ldap_analyst_group` | `VARCHAR(255) NULL` |  |
| `ldap_user_group` | `VARCHAR(255) NULL` |  |
| `sync_enabled` | `TINYINT(1) NOT NULL DEFAULT 0` | directory sync (slice 2) Sign-in asks about ONE person who is standing there; sync enumerates everybody, so they exist before anyone signs in. |
| `sync_base_dn` | `VARCHAR(255) NULL` |  |
| `sync_ou_includes` | `TEXT NULL` | Ticked branches, one DN per line, and the carve-outs within them. TEXT rather than a child table: this is a setting belonging to one provider, never queried across providers and never joined to. ⚠️ Both NULL is the ONLY state an upgraded install can be in, so it must keep meaning exactly what sync_base_dn alone used to mean. |
| `sync_ou_excludes` | `TEXT NULL` |  |
| `sync_filter` | `VARCHAR(500) NULL` |  |
| `sync_on_conflict` | `VARCHAR(20) NOT NULL DEFAULT 'adopt'` | adopt \| flag — see the note in freeitsm.sql: adopting stops their local portal password working, which is why it is a choice. |
| `sync_deactivate_after` | `INT NOT NULL DEFAULT 3` |  |
| `sync_brake_percent` | `INT NOT NULL DEFAULT 20` | The sanity brake. A run finding this many percent fewer people than the last good one stops and changes nothing. |
| `ldap_attr_job_title` | `VARCHAR(64) NULL` |  |
| `ldap_attr_department` | `VARCHAR(64) NULL` |  |
| `ldap_attr_office` | `VARCHAR(64) NULL` |  |
| `ldap_attr_phone` | `VARCHAR(64) NULL` |  |
| `ldap_attr_mobile` | `VARCHAR(64) NULL` |  |
| `ldap_attr_employee_id` | `VARCHAR(64) NULL` |  |
| `ldap_attr_manager` | `VARCHAR(64) NULL` |  |
| `carddav_url` | `VARCHAR(500) NULL` | CardDAV address book (protocol = 'carddav') A contact source rather than a sign-in method: an address book cannot authenticate anybody, so a carddav provider has no Signing in tab and never appears on the login page. See the wiki: CardDAV Contact Sync.  🔑 The `sync_*` columns above are reused UNCHANGED — on_conflict, deactivate_after and brake_percent are policy, not transport, and that policy layer is the expensive part nobody should rebuild. Only the LDAP-shaped ones (`sync_base_dn`, `sync_ou_*`, `sync_filter`) do not apply; `carddav_addressbook` is this transport's equivalent. |
| `carddav_username` | `VARCHAR(255) NULL` |  |
| `carddav_password` | `VARCHAR(500) NULL` | Encrypted at rest with `encryptValue()`, exactly like `ldap_bind_password`, and never returned by a GET endpoint — those report `has_carddav_password` instead. |
| `carddav_addressbook` | `VARCHAR(500) NULL` | Which address book to read. NULL means "every book this account can see", which is almost never what somebody wants — the request behind this feature was explicitly to scope it to one group. |
| `carddav_auth` | `VARCHAR(10) NOT NULL DEFAULT 'auto'` | 'auto' \| 'digest' \| 'basic'. ⚠️ Defaults to auto and should stay there: a stock Baikal ships Digest, and Basic against it is a flat 401 that reads to an operator as a wrong password. The override exists for a server that mis-advertises, not for normal use. |
| `carddav_scope` | `VARCHAR(10) NOT NULL DEFAULT 'all'` | WHICH records to bring in, within the chosen address book. 'all' \| 'group' \| 'category' — the three things "a specific contact group" can mean in CardDAV, because it means three different things and only the operator knows which one their server uses: all       every ordinary card in the book group     the MEMBERs of a KIND:group card (how Apple stores one) category  cards carrying a CATEGORIES value (Thunderbird, Android) `carddav_scope_value` holds the group's UID or the category name. |
| `carddav_scope_value` | `TEXT NULL` | ⚠️ TEXT and newline-separated, holding as MANY groups or tags as the operator ticks — not one. Restricting an import to a single group was an arbitrary limit: a service desk plausibly wants "itsm" and "partners" and not the other forty. Same shape and same storage convention as `sync_ou_includes`, which has held a newline-separated list since directory sync shipped, so there is one idiom rather than two. Read with `cardDavScopeList()`. |
| `carddav_write_back` | `TINYINT(1) NOT NULL DEFAULT 0` | 🔴 DEFAULT 0, and it must stay 0. This is the switch that turns a provably read-only integration into one that can modify somebody else's address book, and an upgrade that silently enabled it would be the worst kind of surprise. An operator who wants write-back says so.  ⚠️ Only ever set after `cardDavCanWrite()` has confirmed the account actually holds the privilege — see the note there on why the server is asked rather than a list of known-good products kept. |
| `carddav_allow_create` | `TINYINT(1) NOT NULL DEFAULT 0` | Whether an analyst may ADD a person to this address book (a new card), as opposed to correcting one that is already there. Off by default and a separate decision from write-back: being in the address book is, for some organisations, the record that they may hold somebody's details. Meaningless unless write-back is on; the save forces it off otherwise. |
| `sync_last_run_datetime` | `DATETIME NULL` |  |
| `sync_last_count` | `INT NULL` |  |
| `enabled` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `auto_create_users` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `auto_create_analysts` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `analyst_fallback_mode` | `ENUM('confirm','redirect','block') NOT NULL DEFAULT 'confirm'` |  |
| `require_verified_email` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `profile_sync_mode` | `ENUM('always','initial','never') NOT NULL DEFAULT 'never'` |  |
| `default_modules` | `VARCHAR(500) NULL` |  |
| `jit_company_access` | `VARCHAR(10) NOT NULL DEFAULT 'all'` | 3.5.0: companies an auto-created analyst can see - all \| provider \| none. See `ssoApplyJitCompanies()`. |
| `oidc_group_claim` | `VARCHAR(100) NULL` | SSO claim mapping (3.5.0, OIDC only) - see includes/sso_claims.php. |
| `oidc_analyst_group` | `VARCHAR(255) NULL` |  |
| `oidc_admin_group` | `VARCHAR(255) NULL` |  |
| `oidc_groups_last_seen` | `TEXT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `tenant_id` | `INT NULL` |  |
| `portal_email_domains` | `TEXT NULL` | GH #147 - portal email-first routing. Domains one per line; an address on one goes straight to this provider. NULL = none. |
| `portal_show_button` | `TINYINT(1) NOT NULL DEFAULT 1` | DEFAULT 1 so an upgrade keeps every portal button where it was. |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `last_modified_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):515 |  | update | `auto_create_analysts` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):516 |  | update | `analyst_fallback_mode` |  |
| [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md):410 |  | update | `protocol` |  |
| [api/system/delete_sso_provider.php](/code/files/api/system/delete_sso_provider.php.md):50 |  | delete |  |  |
| [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md):531 |  | update | `last_modified_datetime` |  |
| [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md):550 |  | insert | `$names` |  |
| [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md):495 | `cardDavSyncRun` | update | `sync_last_run_datetime`, `sync_last_count` |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):701 | `directorySyncRun` | update | `sync_last_run_datetime`, `sync_last_count` |  |
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md):219 | `ssoRememberGroups` | update | `oidc_groups_last_seen` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) | L55 |  |
| [api/auth/resolve_login.php](/code/files/api/auth/resolve_login.php.md) | L50, L56, L103 |  |
| [api/self-service/reset_password.php](/code/files/api/self-service/reset_password.php.md) | L73 |  |
| [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md) | L50 |  |
| [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md) | L272, L273, L274, L275, L323, L324 ... |  |
| [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md) | L290 |  |
| [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md) | L311, L347 |  |
| [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md) | L362 |  |
| [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md) | L115, L129 |  |
| [api/system/debug-tools/D016_carddav_drift.php](/code/files/api/system/debug-tools/D016_carddav_drift.php.md) | L89 |  |
| [api/system/get_carddav_write_log.php](/code/files/api/system/get_carddav_write_log.php.md) | L40 |  |
| [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md) | L45 |  |
| [api/system/get_topology.php](/code/files/api/system/get_topology.php.md) | L64 |  |
| [api/system/portal_profile.php](/code/files/api/system/portal_profile.php.md) | portalProfileState L36 |  |
| [api/system/run_directory_sync.php](/code/files/api/system/run_directory_sync.php.md) | L43 |  |
| [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md) | L392, L439, L495 |  |
| [api/system/test_carddav_connection.php](/code/files/api/system/test_carddav_connection.php.md) | L57 |  |
| [api/system/test_carddav_write.php](/code/files/api/system/test_carddav_write.php.md) | L50 |  |
| [api/system/test_directory_mapping.php](/code/files/api/system/test_directory_mapping.php.md) | L45 |  |
| [api/tickets/get_person.php](/code/files/api/tickets/get_person.php.md) | L41 |  |
| [api/tickets/get_teams.php](/code/files/api/tickets/get_teams.php.md) | L40 |  |
| [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md) | L59 |  |
| [api/tickets/save_team.php](/code/files/api/tickets/save_team.php.md) | L86 |  |
| [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md) | L183 |  |
| [api/tickets/unlink_user_signin.php](/code/files/api/tickets/unlink_user_signin.php.md) | L49 |  |
| [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md) | analystSignInTeamMethods L73 |  |
| [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) | cardDavCreateTargets L58, cardDavCreateTargets L67 |  |
| [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) | cardDavPushPersonChanges L701 |  |
| [includes/feature_bingo/cards/system-core.php](/code/files/includes/feature_bingo/cards/system-core.php.md) | L703 |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md) | ldapGetProvider L76, ldapPortalProviders L474, ldapPortalProviders L488, ldapPortalProviders L496, ldapAnalystProviders L523 |  |
| [includes/oidc.php](/code/files/includes/oidc.php.md) | oidcGetProvider L70 |  |
| [includes/people.php](/code/files/includes/people.php.md) | personDetail L219 |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) | AnalystsService::providerId L427 |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::people L1008, AssetsService::assetsForUser L1081 |  |
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) | ssoClaimsReady L42 |  |
| [includes/sso_identity.php](/code/files/includes/sso_identity.php.md) | ssoJitColumnsReady L67, ssoAnalystProfileLocked L100, ssoPortalRoutingColumnsReady L120, ssoPortalProviderForEmail L179, ssoJitCompanyReady L196, ssoApplyJitCompanies L223 |  |
| [includes/users.php](/code/files/includes/users.php.md) | userSignsInElsewhere L180, userSourcesInScope L224, portalProfileAccess L318 |  |
| [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md) | renderHeaderRight L1510 |  |
| [self-service/login.php](/code/files/self-service/login.php.md) | L69, L77, L88 |  |
| [system/analysts/index.php](/code/files/system/analysts/index.php.md) | L290 |  |
| [system/sso/carddav.php](/code/files/system/sso/carddav.php.md) | L59 |  |
| [system/sso/provider.php](/code/files/system/sso/provider.php.md) | L47 |  |
| [system/teams/index.php](/code/files/system/teams/index.php.md) | L199 |  |

