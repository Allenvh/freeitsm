# analysts

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `username` | `VARCHAR(50) NOT NULL` |  |
| `password_hash` | `VARCHAR(255) NOT NULL` |  |
| `full_name` | `VARCHAR(100) NOT NULL` |  |
| `email` | `VARCHAR(100) NOT NULL` |  |
| `job_title` | `VARCHAR(100) NULL` | Profile details an analyst can put in their signature (#80). Deliberately on the analyst rather than borrowed from a users row: an analyst may have no person record at all, and a signature that silently loses its phone number because a link is missing is worse than one that never had it. |
| `department` | `VARCHAR(100) NULL` |  |
| `phone` | `VARCHAR(50) NULL` |  |
| `mobile` | `VARCHAR(50) NULL` |  |
| `is_active` | `TINYINT(1) NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `last_login_datetime` | `DATETIME NULL` |  |
| `last_modified_datetime` | `DATETIME NULL` |  |
| `totp_secret` | `VARCHAR(500) NULL` |  |
| `totp_enabled` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `trust_device_enabled` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `password_changed_datetime` | `DATETIME NULL` |  |
| `failed_login_count` | `INT NOT NULL DEFAULT 0` |  |
| `locked_until` | `DATETIME NULL` |  |
| `auth_provider_id` | `INT NULL` |  |
| `auth_follow_team` | `TINYINT(1) NOT NULL DEFAULT 0` | 1 = take the sign-in method from their teams (GH #41); auth_provider_id then holds the result, copied in by includes/analyst_signin.php. Defaults to 0 so nobody's sign-in changes on upgrade. |
| `can_access_all_tenants` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `is_admin` | `TINYINT(1) NOT NULL DEFAULT 0` | Only administrators may enter the System module. New analysts default to non-admin; existing analysts are grandfathered to admin on first upgrade (see the one-time backfill below) so nobody is locked out. |
| `can_access_all_modules` | `TINYINT(1) NOT NULL DEFAULT 1` | Module access (issue #30) — mirrors can_access_all_tenants. 1 = every module; 0 = restricted to analyst_modules (+ any team grants). Defaults to 1 so a new analyst is unrestricted; the upgrade back-fill sets it to 0 for analysts who already had analyst_modules rows (i.e. were restricted). |
| `must_change_password` | `TINYINT(1) NOT NULL DEFAULT 0` | Make this analyst change their password before they can do anything else. Set on the seeded `admin` account, because admin/freeitsm was permanent: there was no column like this and nothing anywhere forced, warned about or even nagged on the change. Cleared the moment they set a new one. |
| `mfa_failed_count` | `INT NOT NULL DEFAULT 0` | MFA code-step throttling (S6). Deliberately SEPARATE from failed_login_count / locked_until above, and that separation is the fix: a successful password step resets those two, so an attacker holding a valid password never tripped them, and the MFA count had nowhere durable to live. Only entering a correct code clears these. See includes/mfa_throttle.php. |
| `mfa_locked_until` | `DATETIME NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md):285 |  | update | `last_login_datetime`, `failed_login_count` |  |
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md):624 | `oidcCreateAnalyst` | insert | `username`, `password_hash`, `full_name`, `email`, `job_title`, `department`, `phone`, `mobile`, `is_active`, `created_datetime` |  |
| [api/auth/reset_password.php](/code/files/api/auth/reset_password.php.md):71 |  | update | `password_hash`, `password_changed_datetime`, `failed_login_count`, `locked_until` |  |
| [api/myaccount/change_password.php](/code/files/api/myaccount/change_password.php.md):55 |  | update | `password_hash`, `last_modified_datetime`, `password_changed_datetime` |  |
| [api/myaccount/change_password.php](/code/files/api/myaccount/change_password.php.md):60 |  | update | `password_hash`, `last_modified_datetime` |  |
| [api/myaccount/change_password.php](/code/files/api/myaccount/change_password.php.md):73 |  | update | `must_change_password` |  |
| [api/myaccount/save_profile.php](/code/files/api/myaccount/save_profile.php.md):41 |  | update | `job_title`, `department`, `phone`, `mobile`, `last_modified_datetime` |  |
| [api/myaccount/toggle_trust_device.php](/code/files/api/myaccount/toggle_trust_device.php.md):34 |  | update | `trust_device_enabled` |  |
| [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md):172 |  | update | `last_login_datetime` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):529 |  | update | `is_admin` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):543 |  | update | `can_access_all_modules` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):614 |  | insert | `username`, `password_hash`, `full_name`, `email`, `is_active`, `is_admin`, `must_change_password`, `created_datetime` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):641 |  | update | `must_change_password` |  |
| [api/system/delete_sso_provider.php](/code/files/api/system/delete_sso_provider.php.md):57 |  | update | `auth_provider_id` |  |
| [api/tickets/reset_analyst_password.php](/code/files/api/tickets/reset_analyst_password.php.md):50 |  | update | `password_hash`, `last_modified_datetime` |  |
| [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md):112 |  | update | `username`, `full_name`, `email`, `password_hash`, `is_active`, `auth_provider_id`, `is_admin`, `last_modified_datetime` |  |
| [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md):126 |  | update | `username`, `full_name`, `email`, `is_active`, `auth_provider_id`, `is_admin`, `last_modified_datetime` |  |
| [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md):144 |  | insert | `username`, `password_hash`, `full_name`, `email`, `is_active`, `auth_provider_id`, `is_admin`, `created_datetime`, `last_modified_datetime` |  |
| [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md):154 |  | update | `auth_follow_team` |  |
| [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md):163 |  | update | `can_access_all_modules` |  |
| [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md):173 |  | update | `can_access_all_tenants` |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md):587 | `analystDeactivate` | update | `is_active`, `last_modified_datetime` |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md):612 | `analystAnonymise` | update | `username`, `full_name`, `email`, `job_title`, `department`, `phone`, `mobile`, `password_hash`, `totp_secret`, `totp_enabled` |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md):666 | `analystDelete` | delete |  |  |
| [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md):151 | `analystSignInApply` | update | `auth_provider_id`, `last_modified_datetime` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):585 | `ldapCreateAnalyst` | insert | `username`, `password_hash`, `full_name`, `email`, `is_active`, `created_datetime`, `auth_provider_id`, `can_access_all_modules` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):651 | `ldapResolveAnalyst` | update | `email` |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):77 | `AnalystsService::create` | insert | `username`, `password_hash`, `full_name`, `email`, `is_active`, `auth_provider_id`, `is_admin`, `can_access_all_modules`, `can_access_all_tenants`, `created_datetime` |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):139 | `AnalystsService::update` | update |  |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):219 | `AnalystsService::setAccess` | update | `is_admin`, `can_access_all_modules`, `can_access_all_tenants`, `last_modified_datetime` |  |
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md):301 | `ssoJitStartForTeams` | update | `can_access_all_modules` |  |
| [includes/sso_identity.php](/code/files/includes/sso_identity.php.md):228 | `ssoApplyJitCompanies` | update | `can_access_all_tenants` |  |
| [includes/sso_identity.php](/code/files/includes/sso_identity.php.md):237 | `ssoApplyJitCompanies` | update | `can_access_all_tenants` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_analysts.php](/code/files/api/assets/get_analysts.php.md) | L37 |  |
| [api/assets/get_asset_checkout_log.php](/code/files/api/assets/get_asset_checkout_log.php.md) | L44 |  |
| [api/assets/get_asset_history.php](/code/files/api/assets/get_asset_history.php.md) | L34 |  |
| [api/assets/get_asset_tickets.php](/code/files/api/assets/get_asset_tickets.php.md) | L50 |  |
| [api/assets/get_asset_users.php](/code/files/api/assets/get_asset_users.php.md) | L60 |  |
| [api/assets/import_history.php](/code/files/api/assets/import_history.php.md) | L50 |  |
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) | L204, oidcLoadAnalyst L330, oidcCreateAnalyst L603 |  |
| [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md) | L35 |  |
| [api/auth/resolve_login.php](/code/files/api/auth/resolve_login.php.md) | L56 |  |
| [api/calendar/get_event.php](/code/files/api/calendar/get_event.php.md) | L27 |  |
| [api/calendar/get_events.php](/code/files/api/calendar/get_events.php.md) | L34 |  |
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L34, L77, L103 |  |
| [api/change-management/get_approvals.php](/code/files/api/change-management/get_approvals.php.md) | L32, L55 |  |
| [api/change-management/get_audit.php](/code/files/api/change-management/get_audit.php.md) | L33 |  |
| [api/change-management/get_calendar_changes.php](/code/files/api/change-management/get_calendar_changes.php.md) | L31 |  |
| [api/change-management/get_comments.php](/code/files/api/change-management/get_comments.php.md) | L33 |  |
| [api/change-management/list.php](/code/files/api/change-management/list.php.md) | L23, L40 |  |
| [api/cmdb/get_object_tickets.php](/code/files/api/cmdb/get_object_tickets.php.md) | L45 |  |
| [api/contracts/get_contract.php](/code/files/api/contracts/get_contract.php.md) | L39 |  |
| [api/contracts/get_contracts.php](/code/files/api/contracts/get_contracts.php.md) | L37 |  |
| [api/documents/links.php](/code/files/api/documents/links.php.md) | L49 |  |
| [api/documents/list.php](/code/files/api/documents/list.php.md) | L72 |  |
| [api/domains/accounts.php](/code/files/api/domains/accounts.php.md) | L20 |  |
| [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md) | domainImportResolveNames L61 |  |
| [api/domains/get.php](/code/files/api/domains/get.php.md) | L17 |  |
| [api/files/list.php](/code/files/api/files/list.php.md) | L62 |  |
| [api/files/permissions.php](/code/files/api/files/permissions.php.md) | L69, L107 |  |
| [api/files/principals.php](/code/files/api/files/principals.php.md) | L19 |  |
| [api/files/properties.php](/code/files/api/files/properties.php.md) | L16, L46, L50 |  |
| [api/forms/get_collection_submissions.php](/code/files/api/forms/get_collection_submissions.php.md) | L28 |  |
| [api/forms/get_form.php](/code/files/api/forms/get_form.php.md) | L27 |  |
| [api/forms/get_forms.php](/code/files/api/forms/get_forms.php.md) | L62 |  |
| [api/forms/get_submissions.php](/code/files/api/forms/get_submissions.php.md) | L75 |  |
| [api/forms/list_versions.php](/code/files/api/forms/list_versions.php.md) | L68 |  |
| [api/knowledge/get_analysts.php](/code/files/api/knowledge/get_analysts.php.md) | L20 |  |
| [api/knowledge/get_review_list.php](/code/files/api/knowledge/get_review_list.php.md) | L34 |  |
| [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md) | handleList L68 |  |
| [api/knowledge/knowledge_article.php](/code/files/api/knowledge/knowledge_article.php.md) | L35 |  |
| [api/knowledge/knowledge_articles.php](/code/files/api/knowledge/knowledge_articles.php.md) | L39 |  |
| [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md) | handlePreview L109 |  |
| [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) | handleSearch L369 |  |
| [api/lms/analysts.php](/code/files/api/lms/analysts.php.md) | L20 |  |
| [api/lms/assignment_targets.php](/code/files/api/lms/assignment_targets.php.md) | L41 |  |
| [api/lms/assignments.php](/code/files/api/lms/assignments.php.md) | L52, L55 |  |
| [api/lms/courses.php](/code/files/api/lms/courses.php.md) | L23 |  |
| [api/lms/groups.php](/code/files/api/lms/groups.php.md) | L29 |  |
| [api/lms/learner_data.php](/code/files/api/lms/learner_data.php.md) | L58 |  |
| [api/lms/progress.php](/code/files/api/lms/progress.php.md) | L97 |  |
| [api/lms/reminder_settings.php](/code/files/api/lms/reminder_settings.php.md) | L91 |  |
| [api/morning-checks/get_todays_checks.php](/code/files/api/morning-checks/get_todays_checks.php.md) | L39 |  |
| [api/myaccount/change_password.php](/code/files/api/myaccount/change_password.php.md) | L42 |  |
| [api/myaccount/get_signatures.php](/code/files/api/myaccount/get_signatures.php.md) | L28 |  |
| [api/myaccount/toggle_trust_device.php](/code/files/api/myaccount/toggle_trust_device.php.md) | L22 |  |
| [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md) | L37, L41, L238 |  |
| [api/network-mapper/get_diagram.php](/code/files/api/network-mapper/get_diagram.php.md) | L34 |  |
| [api/network-mapper/list_diagrams.php](/code/files/api/network-mapper/list_diagrams.php.md) | L31 |  |
| [api/network-mapper/list_versions.php](/code/files/api/network-mapper/list_versions.php.md) | L60 |  |
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L22, L64, L75 |  |
| [api/problem-management/list.php](/code/files/api/problem-management/list.php.md) | L28 |  |
| [api/projects/lookups.php](/code/files/api/projects/lookups.php.md) | L13 |  |
| [api/projects/settings.php](/code/files/api/projects/settings.php.md) | projectRatesForSettings L43, L77, L94 |  |
| [api/reporting/get_system_logs.php](/code/files/api/reporting/get_system_logs.php.md) | L27 |  |
| [api/reporting/packs/get.php](/code/files/api/reporting/packs/get.php.md) | L18 |  |
| [api/reporting/packs/save.php](/code/files/api/reporting/packs/save.php.md) | L31 |  |
| [api/reporting/packs/shares.php](/code/files/api/reporting/packs/shares.php.md) | L32 |  |
| [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md) | L87 |  |
| [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md) | L38 |  |
| [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md) | L35 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L201 |  |
| [api/self-service/get_tickets.php](/code/files/api/self-service/get_tickets.php.md) | L30 |  |
| [api/self-service/register.php](/code/files/api/self-service/register.php.md) | L57 |  |
| [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md) | L57 |  |
| [api/service-status/get_incident_updates.php](/code/files/api/service-status/get_incident_updates.php.md) | L42 |  |
| [api/software/get_apikeys.php](/code/files/api/software/get_apikeys.php.md) | L21 |  |
| [api/software/get_licences.php](/code/files/api/software/get_licences.php.md) | L21 |  |
| [api/system/api_keys/create_key.php](/code/files/api/system/api_keys/create_key.php.md) | L69 |  |
| [api/system/api_keys/list_keys.php](/code/files/api/system/api_keys/list_keys.php.md) | L27, L59 |  |
| [api/system/api_keys/update_key.php](/code/files/api/system/api_keys/update_key.php.md) | L54 |  |
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md) | L212 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L606, L636, L3179, L3282 |  |
| [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md) | L204 |  |
| [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md) | L263, L441, L442, L443, L444, L446 |  |
| [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md) | L859 |  |
| [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md) | L156, L383, L416, L421 |  |
| [api/system/get_carddav_write_log.php](/code/files/api/system/get_carddav_write_log.php.md) | L50 |  |
| [api/system/get_directory_sync_log.php](/code/files/api/system/get_directory_sync_log.php.md) | L67 |  |
| [api/system/get_effective_module_access.php](/code/files/api/system/get_effective_module_access.php.md) | L25 |  |
| [api/system/get_module_access.php](/code/files/api/system/get_module_access.php.md) | L47 |  |
| [api/system/get_topology.php](/code/files/api/system/get_topology.php.md) | L65, L89, L140 |  |
| [api/system/roles.php](/code/files/api/system/roles.php.md) | L26 |  |
| [api/system/save_module_grants.php](/code/files/api/system/save_module_grants.php.md) | L49 |  |
| [api/tasks/collaborators.php](/code/files/api/tasks/collaborators.php.md) | collaboratorCandidates L95 |  |
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L39, L107, L125 |  |
| [api/tasks/list.php](/code/files/api/tasks/list.php.md) | L25, L125, L223 |  |
| [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md) | L117 |  |
| [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md) | L103 |  |
| [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md) | L89 |  |
| [api/tickets/analyst_leaving.php](/code/files/api/tickets/analyst_leaving.php.md) | L39 |  |
| [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) | L401 |  |
| [api/tickets/clear_rota_cells.php](/code/files/api/tickets/clear_rota_cells.php.md) | L56 |  |
| [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md) | L21, L80 |  |
| [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md) | L56 |  |
| [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md) | L37 |  |
| [api/tickets/get_rota.php](/code/files/api/tickets/get_rota.php.md) | L40 |  |
| [api/tickets/get_scheduled_tickets.php](/code/files/api/tickets/get_scheduled_tickets.php.md) | L45 |  |
| [api/tickets/get_sla_notification_rules.php](/code/files/api/tickets/get_sla_notification_rules.php.md) | L24, L43 |  |
| [api/tickets/get_team_analysts.php](/code/files/api/tickets/get_team_analysts.php.md) | L28 |  |
| [api/tickets/get_ticket_audit.php](/code/files/api/tickets/get_ticket_audit.php.md) | L36 |  |
| [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md) | L337, L348 |  |
| [api/tickets/get_ticket_tasks.php](/code/files/api/tickets/get_ticket_tasks.php.md) | L44 |  |
| [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) | getCategoricalData L345, getCategoricalData L347, getCategoricalWithSeries L391, getCategoricalWithSeries L394 |  |
| [api/tickets/get_time_entries.php](/code/files/api/tickets/get_time_entries.php.md) | L47 |  |
| [api/tickets/get_user_tickets.php](/code/files/api/tickets/get_user_tickets.php.md) | L31 |  |
| [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) | maState L108 |  |
| [api/tickets/paste_rota_cells.php](/code/files/api/tickets/paste_rota_cells.php.md) | L96 |  |
| [api/tickets/paste_rota_line.php](/code/files/api/tickets/paste_rota_line.php.md) | L76 |  |
| [api/tickets/paste_rota_week.php](/code/files/api/tickets/paste_rota_week.php.md) | L71 |  |
| [api/tickets/reset_analyst_password.php](/code/files/api/tickets/reset_analyst_password.php.md) | L39 |  |
| [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md) | L64, L75, L78, L100 |  |
| [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md) | L71 |  |
| [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md) | handleGet L147, handleSearch L230, handleAddMember L381 |  |
| [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) | apiAuthenticate L28 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetsGet L353, apiAssetAssignmentsList L419, apiAssetHistoryList L462, apiAssetCustodyList L484 |  |
| [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md) | apiCalendarEventSelect L71 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangeSelect L57, apiChangeCommentsList L389, apiChangeAuditList L426, apiChangeCabGet L451 |  |
| [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) | apiContractSelect L65 |  |
| [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) | apiDomainSelect L44, apiDomainSelect L48, apiDomainsHistory L393, apiDomainAccountsList L423 |  |
| [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) | apiFormSelect L61, apiLoadFormSubmission L225, apiFormSubmissionsSelect L347 |  |
| [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) | apiKnowledgeSelect L51, apiKnowledgeVersionsList L364, apiKnowledgeVersionsGet L386 |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmDiagramSelect L75 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemSelect L41, apiProblemNotesList L319, apiProblemAuditList L352 |  |
| [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) | apiProjectSelect L45, apiProjectTasks L656, apiProjectHistory L696 |  |
| [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md) | apiAnalystsList L22 |  |
| [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) | apiServiceIncidentSelect L122 |  |
| [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) | apiSoftwareLicenceSelect L229 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiTaskSelect L42, apiTasksGet L362, apiTaskCommentsList L429 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketSelect L38, apiTicketNotesList L335, apiTicketAuditList L403, apiTicketTimeEntriesList L462 |  |
| [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) | apiWorkflowSelect L41 |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) | analystFootprint L436, analystFootprint L466, analystReassignLive L497, analystAnonymise L603, analystLcAssertActionable L686, analystLcAssertActionable L691 |  |
| [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md) | analystSignInReady L53, analystSignInApply L135, analystSignInFollowerIds L191 |  |
| [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) | calendarSyncEnrolment L310 |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) | catalogueApprovalsList L568 |  |
| [includes/contract_report.php](/code/files/includes/contract_report.php.md) | contractReportLoad L155 |  |
| [includes/demo_data.php](/code/files/includes/demo_data.php.md) | demoHasUntaggedImport L223 |  |
| [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) | domainAlertsDue L104, domainAlertOnChange L363, domainRenewalActionsRun L411 |  |
| [includes/domains/read.php](/code/files/includes/domains/read.php.md) | domainListSelect L32, domainListSelect L39, domainApiLookups L199 |  |
| [includes/feature_bingo/cards/system-core.php](/code/files/includes/feature_bingo/cards/system-core.php.md) | L28 |  |
| [includes/files/acl.php](/code/files/includes/files/acl.php.md) | FilesAcl::entries L216 |  |
| [includes/files/audit.php](/code/files/includes/files/audit.php.md) | filesAudit L55 |  |
| [includes/functions.php](/code/files/includes/functions.php.md) | getAnalystAllowedModules L135, analystIsAdmin L217 |  |
| [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md) | knowledgeAuditHistory L137 |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md) | ldapCreateAnalyst L560, ldapResolveAnalyst L630, ldapResolveAnalyst L659 |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md) | lmsAssignedLearnersSql L514, lmsAssignedLearnersSql L544, lmsAssignmentTargetProblem L602 |  |
| [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) | lmsRemindersDue L230 |  |
| [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) | notificationsTaskAssignedEmail L438 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleTickets L343, peopleProjects L492, peopleProjects L500, peopleContractorTasks L793 |  |
| [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) | projectAiFacts L84, projectAiFacts L156, projectAiFacts L286, projectAiFacts L309 |  |
| [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) | projectAlertRows L131, projectAlertsStagesDue L238, projectAlertsMilestones L334 |  |
| [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) | projectAssistRows L43, projectAssistOverview L215, projectAssistTasks L278 |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatMessages L102, projectChatSystem L165, projectChatRead L255, projectChatRead L271, projectChatRead L293, projectChatTurn L408 |  |
| [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) | projectBenefits L78, projectBenefits L85, projectAlertsBenefits L166 |  |
| [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) | projectCapacity L213 |  |
| [includes/projects/control.php](/code/files/includes/projects/control.php.md) | projectControlDetail L315, projectControlDetail L333 |  |
| [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) | projectGateItems L72 |  |
| [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) | projectProposalNamedApprover L62, projectProposalApprovers L83, projectProposalDetail L108 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkProjectRows L402 |  |
| [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md) | projectMilestones L48 |  |
| [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) | projectAlertsOverdueDigest L39, projectNudgeProjects L178, projectNudgeDeciders L194 |  |
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectListRows L328, projectDetail L376, projectDetail L397, projectDetail L414 |  |
| [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) | projectTemplateList L367 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewTicket L141, recordPreviewTask L188, recordPreviewAsset L306, recordPreviewDomain L407 |  |
| [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md) | rpViewer L43, rpListPacks L134, rpListShares L178, rpKnownDepartments L252 |  |
| [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) | rpProjectRows L47, rpProjectsRisks L243 |  |
| [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) | rpStatusIncidentLog L133 |  |
| [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) | L28, rpTicketsList L261 |  |
| [includes/service_context.php](/code/files/includes/service_context.php.md) | ActorContext::sessionCompanyScope L123 |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) | statusPlannedList L165, statusPlannedActor L267 |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) | AnalystsService::load L44, AnalystsService::assertNotLastAdmin L264, AnalystsService::assertEmailFree L411, AnalystsService::uniqueUsername L445 |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::assignAnalyst L328, AssetsService::assignAnalyst L331, AssetsService::assignAnalyst L373, AssetsService::unassignAnalyst L523, AssetsService::assetsForUser L1135 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::saveCab L498, ChangesService::voteCab L561, ChangesService::loadJoined L746, ChangesService::resolveAnalyst L891 |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) | ContractsService::resolveAnalyst L630 |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md) | DomainsService::requireAnalyst L899, DomainsService::auditDisplay L1015, DomainsService::auditDisplay L1017 |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::listCollections L592, FormsService::collectionSubmissions L747 |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) | KnowledgeService::resolveAnalyst L544 |  |
| [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md) | MorningChecksService::listGroups L157 |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md) | ProblemsService::loadProblem L426, ProblemsService::resolveAnalyst L559 |  |
| [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) | ProjectReportsService::listFor L46, ProjectReportsService::latestBriefing L63, ProjectReportsService::recipients L268, ProjectReportsService::recipients L273, ProjectReportsService::runSchedules L389 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::addMember L44, ProjectToolsService::saveRaid L367, ProjectToolsService::issueToTicket L616, ProjectToolsService::raid L822, ProjectToolsService::raid L825, ProjectToolsService::raid L844 ... |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md) | ProjectsService::validateField L683, ProjectsService::auditDisplay L1058 |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) | TasksService::collaboratorsForMany L715, TasksService::timeEntriesFor L1587, TasksService::resolveAnalyst L1699 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::createTicket L166, TicketsService::updateTicket L499, TicketsService::loadTicket L1100 |  |
| [includes/setup_state.php](/code/files/includes/setup_state.php.md) | installIsUnprovisioned L41 |  |
| [includes/signatures.php](/code/files/includes/signatures.php.md) | signatureMergeData L37 |  |
| [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) | sla_resolve_recipients L245, sla_resolve_recipients L257, sla_resolve_recipients L272 |  |
| [includes/sso_identity.php](/code/files/includes/sso_identity.php.md) | ssoAnalystProfileLocked L100 |  |
| [includes/table_views.php](/code/files/includes/table_views.php.md) | tableViewSelect L60 |  |
| [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) | tasksContractorEmail L152 |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md) | buildTicketMergeData L350 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | getAccessibleTenantIds L206 |  |
| [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md) | ticketAiTranscript L151, ticketAiLatestSummary L235 |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) | mergeBuildSnapshotHtml L696 |  |
| [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md) | presenceOthers L103 |  |
| [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) | ticketEmailRecipientsAllowed L304 |  |
| [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md) | snoozeStateFor L291 |  |
| [includes/ticket_views.php](/code/files/includes/ticket_views.php.md) | ticketViewsAsAuditRows L88 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolOpenIncidents L410, warbotToolRecentChanges L494, warbotToolOnCall L532, warbotToolKnownErrors L665 |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md) | warRoomChannelList L132, warRoomChannel L220, warRoomAddMembers L346, warRoomOpenDm L414, warRoomDirectory L451, warRoomMessages L507 ... |  |
| [projects/settings/index.php](/code/files/projects/settings/index.php.md) | L102 |  |
| [self-service/includes/user-menu.php](/code/files/self-service/includes/user-menu.php.md) | L29 |  |
| [system/debug-tools/includes/tools.php](/code/files/system/debug-tools/includes/tools.php.md) | debugToolAnalystOptions L488 |  |
| [system/roles/index.php](/code/files/system/roles/index.php.md) | L35 |  |
| [tickets/csat/index.php](/code/files/tickets/csat/index.php.md) | L135, L166, L184 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::action_assign_ticket L2360 |  |

