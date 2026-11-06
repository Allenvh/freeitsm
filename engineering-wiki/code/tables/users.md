# users

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `email` | `VARCHAR(255) NULL` | NULL because a directory (LDAP) user may genuinely have no mailbox — warehouse and shop-floor staff are never given one. The UNIQUE index stays: MySQL permits many NULLs in a unique index, so any number of mailbox-less people coexist while real addresses stay unique.  ⚠️ ABSENT MUST BE NULL, NEVER ''. The analyst side gets away with '' (#872) only because analysts.email is NOT unique; here the second empty string collides. Enforced in api/tickets/save_user.php, which converts a blank address to NULL before it ever reaches this column. |
| `username` | `VARCHAR(50) NULL` | What a directory user types to sign in when they have no email. NULL for every local/registered account, which is why it is nullable and why UNIQUE tolerates it repeatedly. |
| `display_name` | `VARCHAR(255) NULL` |  |
| `preferred_name` | `VARCHAR(100) NULL` |  |
| `password_hash` | `VARCHAR(255) NULL` |  |
| `totp_secret` | `VARCHAR(500) NULL` |  |
| `totp_enabled` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `mfa_failed_count` | `INT NOT NULL DEFAULT 0` | The portal twin of the analyst MFA throttle (S6). The portal login has the same second factor, so it had the same session-scoped counter and the same hole. See includes/mfa_throttle.php. |
| `mfa_locked_until` | `DATETIME NULL` |  |
| `auth_provider_id` | `INT NULL` |  |
| `theme_preference` | `VARCHAR(32) NULL` | Portal user's colour palette ('default' \| 'dark'); NULL = install default. Analysts use user_preferences, which is keyed by analyst_id and so unavailable to portal users. |
| `tenant_id` | `INT NULL` | The company this requester belongs to. NULL = unknown → their tickets land in triage, the same meaning NULL carries on `tickets`. Scoped-data shape, not config: NULL is "not yet known", never "shared". |
| `cost_centre_id` | `INT NULL` | GH #160: the cost centre it is charged to - see includes/cost_centre_assign.php |
| `created_at` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` | the person, as opposed to the login (directory sync slice 1) Everything above describes how somebody authenticates; these describe who they are, which is what an asset register and an approval chain need. Filled in by hand today, by a directory sync later.  DEFAULT 1 matters on an upgrade: every existing row becomes active without a data migration, which is the only sane reading of a column that did not exist yesterday. |
| `deactivated_datetime` | `DATETIME NULL` |  |
| `job_title` | `VARCHAR(150) NULL` |  |
| `department` | `VARCHAR(150) NULL` |  |
| `office` | `VARCHAR(150) NULL` | The ticket asset-picker searches an asset's location and almost nobody sets one by hand; a directory knows this and never forgets it. |
| `phone` | `VARCHAR(50) NULL` |  |
| `mobile` | `VARCHAR(50) NULL` |  |
| `employee_id` | `VARCHAR(64) NULL` |  |
| `manager_id` | `INT NULL` | Self-referencing manager. FK is ON DELETE SET NULL — deleting a manager must never delete their reports. |
| `directory_username` | `VARCHAR(255) NULL` | What the DIRECTORY calls them, which is a different fact from what they type into the portal: sAMAccountName is unique per directory, `username` is unique per install. Keeping them apart is what stops a sync ever having to mangle somebody's name into `smithj2`. |
| `is_managed` | `TINYINT(1) NOT NULL DEFAULT 0` | Explicit, not inferred from auth_provider_id: an account may be pinned to a provider for SIGN-IN without being owned by a sync, and only the latter should make its fields read-only. |
| `last_seen_in_source` | `DATETIME NULL` |  |
| `sync_missed_count` | `INT NOT NULL DEFAULT 0` | Consecutive runs that failed to find this person. Missing once is noise; missing repeatedly is a fact. Any sighting resets it to 0. |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md):515 | `completeSelfServiceSso` | update | `auth_provider_id` |  |
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md):540 | `completeSelfServiceSso` | insert | `email`, `display_name`, `job_title`, `department`, `office`, `phone`, `mobile`, `auth_provider_id`, `tenant_id`, `created_at` |  |
| [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md):239 | `simCleanup` | delete |  |  |
| [api/self-service/change_password.php](/code/files/api/self-service/change_password.php.md):69 |  | update | `password_hash` |  |
| [api/self-service/reset_password.php](/code/files/api/self-service/reset_password.php.md):108 |  | update | `password_hash` |  |
| [api/self-service/save_theme.php](/code/files/api/self-service/save_theme.php.md):34 |  | update | `theme_preference` |  |
| [api/self-service/update_profile.php](/code/files/api/self-service/update_profile.php.md):148 |  | update |  |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):233 |  | update | `email` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):568 |  | update | `tenant_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):573 |  | update | `tenant_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2279 |  | insert | `email`, `display_name`, `created_at` |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):904 | `getOrCreateUser` | update | `display_name` |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):912 | `getOrCreateUser` | insert | `email`, `display_name`, `created_at` |  |
| [api/tickets/delete_user.php](/code/files/api/tickets/delete_user.php.md):80 |  | delete |  |  |
| [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md):291 |  | update |  |  |
| [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md):350 |  | insert |  |  |
| [api/tickets/unlink_user_signin.php](/code/files/api/tickets/unlink_user_signin.php.md):75 |  | update | `auth_provider_id` |  |
| [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md):203 | `apiUsersCreate` | insert | `email`, `username`, `display_name`, `preferred_name`, `tenant_id`, `created_at` |  |
| [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md):206 | `apiUsersCreate` | update | `cost_centre_id` |  |
| [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md):304 | `apiUsersUpdate` | update |  |  |
| [includes/carddav_create.php](/code/files/includes/carddav_create.php.md):336 | `cardDavCreateContact` | update | `is_managed`, `auth_provider_id`, `last_seen_in_source`, `sync_missed_count` |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md):240 | `catalogueCreateTicketFromSubmission` | insert | `email`, `display_name`, `created_at` |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):819 | `dsyncApplyToExisting` | update |  |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):838 | `dsyncCreate` | insert | `email`, `display_name`, `job_title`, `department`, `office`, `phone`, `mobile`, `employee_id`, `directory_username`, `auth_provider_id` |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):938 | `dsyncHandleMissing` | update | `is_active`, `deactivated_datetime`, `sync_missed_count` |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):947 | `dsyncHandleMissing` | update | `sync_missed_count` |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):1001 | `dsyncResolveManagers` | update | `manager_id` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):783 | `ldapResolveUser` | update | `email` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):808 | `ldapResolveUser` | update | `auth_provider_id` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):816 | `ldapResolveUser` | update | `username` |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md):848 | `ldapResolveUser` | insert | `email`, `username`, `display_name`, `auth_provider_id`, `tenant_id`, `created_at` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):385 | `resolveSlackRequester` | update | `display_name` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):475 | `resolveDirectoryRequester` | update | `display_name` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):727 | `messagingTelegramLinkPhone` | delete |  |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):753 | `messagingTelegramLinkPhone` | update | `mobile` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):964 | `getOrCreateChannelUser` | insert | `email`, `display_name`, `created_at` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):223 | `TicketsService::createTicket` | insert | `email`, `display_name`, `tenant_id`, `created_at` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):186 | `webchatGetOrCreateUser` | update | `display_name` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):191 | `webchatGetOrCreateUser` | insert | `email`, `display_name`, `created_at` |  |
| [self-service/verify-email.php](/code/files/self-service/verify-email.php.md):61 |  | update | `password_hash`, `display_name` |  |
| [self-service/verify-email.php](/code/files/self-service/verify-email.php.md):65 |  | update | `tenant_id` |  |
| [self-service/verify-email.php](/code/files/self-service/verify-email.php.md):69 |  | insert | `email`, `display_name`, `password_hash`, `tenant_id`, `created_at` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2730 | `WorkflowEngine::action_create_ticket` | insert | `email`, `display_name`, `created_at` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_users.php](/code/files/api/assets/get_asset_users.php.md) | L60 |  |
| [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) | ssLoadUser L341, completeSelfServiceSso L505 |  |
| [api/auth/resolve_login.php](/code/files/api/auth/resolve_login.php.md) | L50, L84 |  |
| [api/change-management/list_linkable_tickets.php](/code/files/api/change-management/list_linkable_tickets.php.md) | L49 |  |
| [api/contracts/customer_lookup.php](/code/files/api/contracts/customer_lookup.php.md) | L45 |  |
| [api/forms/get_submissions.php](/code/files/api/forms/get_submissions.php.md) | L75 |  |
| [api/forms/lookup_search.php](/code/files/api/forms/lookup_search.php.md) | L82 |  |
| [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md) | L83 |  |
| [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) | handleSearch L371 |  |
| [api/lms/assignment_targets.php](/code/files/api/lms/assignment_targets.php.md) | L51, L78 |  |
| [api/lms/assignments.php](/code/files/api/lms/assignments.php.md) | L53 |  |
| [api/lms/learner_data.php](/code/files/api/lms/learner_data.php.md) | L60 |  |
| [api/lms/progress.php](/code/files/api/lms/progress.php.md) | L103 |  |
| [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md) | simCleanup L232 |  |
| [api/problem-management/list_linkable_tickets.php](/code/files/api/problem-management/list_linkable_tickets.php.md) | L49 |  |
| [api/projects/tools.php](/code/files/api/projects/tools.php.md) | L69 |  |
| [api/self-service/change_password.php](/code/files/api/self-service/change_password.php.md) | L58 |  |
| [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md) | L98 |  |
| [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md) | L72 |  |
| [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md) | L42 |  |
| [api/self-service/get_profile.php](/code/files/api/self-service/get_profile.php.md) | L25 |  |
| [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md) | L65, L101 |  |
| [api/self-service/get_ticket_categories.php](/code/files/api/self-service/get_ticket_categories.php.md) | L42 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L81, L128 |  |
| [api/self-service/login.php](/code/files/api/self-service/login.php.md) | L63, L135 |  |
| [api/self-service/register.php](/code/files/api/self-service/register.php.md) | L65 |  |
| [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md) | L74, L98 |  |
| [api/self-service/request_password_reset.php](/code/files/api/self-service/request_password_reset.php.md) | L60 |  |
| [api/self-service/reset_password.php](/code/files/api/self-service/reset_password.php.md) | L73 |  |
| [api/self-service/update_profile.php](/code/files/api/self-service/update_profile.php.md) | L159 |  |
| [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md) | L37 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L591, L1668, L2273, L2279, L2289 |  |
| [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md) | L315 |  |
| [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md) | L206 |  |
| [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md) | L453, L454, L455, L457 |  |
| [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md) | L228 |  |
| [api/system/debug-tools/D016_carddav_drift.php](/code/files/api/system/debug-tools/D016_carddav_drift.php.md) | L143 |  |
| [api/system/get_orphaned_tickets.php](/code/files/api/system/get_orphaned_tickets.php.md) | L27 |  |
| [api/system/get_topology.php](/code/files/api/system/get_topology.php.md) | L71, L141 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L329 |  |
| [api/system/managers.php](/code/files/api/system/managers.php.md) | L100, L107, L113 |  |
| [api/tickets/address_book_add.php](/code/files/api/tickets/address_book_add.php.md) | L46 |  |
| [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md) | L123 |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) | getOrCreateUser L896, saveEmailToDatabase L1270 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L49 |  |
| [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md) | L56 |  |
| [api/tickets/get_person.php](/code/files/api/tickets/get_person.php.md) | L41, L68 |  |
| [api/tickets/get_scheduled_tickets.php](/code/files/api/tickets/get_scheduled_tickets.php.md) | L45 |  |
| [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md) | L59 |  |
| [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) | maManager L58, maState L108, maState L149, L186, L200, L260 ... |  |
| [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md) | L78, L145, L183, L229 |  |
| [api/tickets/unlink_user_signin.php](/code/files/api/tickets/unlink_user_signin.php.md) | L49 |  |
| [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md) | handleGet L159, handleSearch L246, handleAddMember L386 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetsGet L353, apiAssetAssignmentsList L419 |  |
| [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) | apiDomainSelect L38 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketSelect L38, apiTicketsList L228 |  |
| [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) | apiUsersList L98, apiUsersList L105, apiUsersGet L124, apiUsersCreate L175, apiUsersCreate L182, apiUsersCreate L208 ... |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) | calendarSyncReconcileTicket L51 |  |
| [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) | cardDavCreateContact L191 |  |
| [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) | cardDavPushPersonChanges L701 |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) | catalogueCreateTicketFromSubmission L236, catalogueCreateTicketFromSubmission L247, catalogueApprovalsList L568 |  |
| [includes/contract_party.php](/code/files/includes/contract_party.php.md) | contractCustomerTenantExpr L79, contractPartySql L187, contractNormaliseParty L252 |  |
| [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md) | costCentreUsage L164 |  |
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) | dsyncFindExisting L430, dsyncFindExisting L439, dsyncFindExisting L452, dsyncFindExisting L455, dsyncHandleMissing L915 |  |
| [includes/domains/customer.php](/code/files/includes/domains/customer.php.md) | domainCustomerPersonOk L51, domainCustomerSearch L106 |  |
| [includes/domains/read.php](/code/files/includes/domains/read.php.md) | domainListSelect L25 |  |
| [includes/functions.php](/code/files/includes/functions.php.md) | requesterIsActive L233 |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) | integrationsTicketPayload L869 |  |
| [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md) | knowledgeAuditHistory L140 |  |
| [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md) | portalUserTenantId L50 |  |
| [includes/ldap.php](/code/files/includes/ldap.php.md) | ldapResolveUser L762, ldapResolveUser L797 |  |
| [includes/leases.php](/code/files/includes/leases.php.md) | leaseGet L419, leaseDueBack L778 |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md) | lmsAssignedLearnersSql L534, lmsAssignedLearnersSql L550, lmsAssignmentTargetProblem L608 |  |
| [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) | lmsRemindersDue L236 |  |
| [includes/managers.php](/code/files/includes/managers.php.md) | managerRow L115, managerGrantMembers L191, managerGrantMembers L193, managerGrantMembers L198, managerGrantMembers L208, managerGrantMembers L219 ... |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) | messagingKnownPersonByEmail L414, messagingTelegramIsPlaceholder L553, messagingTelegramLinkPhone L745, messagingUserLabel L793, messagingFindUsersByPhone L914, getOrCreateChannelUser L958 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleListRows L106, peopleCompanies L135, peopleCompanyCards L183, personDetail L219, personDetail L245, personDetail L250 ... |  |
| [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) | projectAiFacts L286 |  |
| [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) | projectCreateFromAction L173 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L503 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewTicket L141, recordPreviewAsset L306 |  |
| [includes/self_service_mailboxes.php](/code/files/includes/self_service_mailboxes.php.md) | selfServiceMailboxes L40 |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::assignUser L405, AssetsService::assignUser L408, AssetsService::assignUser L445, AssetsService::unassignUser L480, AssetsService::moveToCompany L652, AssetsService::usersHoldingAssets L913 ... |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md) | DomainsService::auditDisplay L1021 |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::collectionSubmissions L747 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::addMember L49, ProjectToolsService::members L1124, ProjectToolsService::memberName L1797 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::createTicket L193, TicketsService::createTicket L205, TicketsService::updateTicket L740 |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md) | buildTicketMergeData L350 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessUser L316 |  |
| [includes/theme.php](/code/files/includes/theme.php.md) | Theme::active L105 |  |
| [includes/ticket_events.php](/code/files/includes/ticket_events.php.md) | ticketDispatchCreated L55 |  |
| [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) | ticketEmailRecipientsAllowed L300 |  |
| [includes/ticket_views.php](/code/files/includes/ticket_views.php.md) | ticketViewsAsAuditRows L88, ticketViewsForRequester L136 |  |
| [includes/typed_fields.php](/code/files/includes/typed_fields.php.md) | L514, L521 |  |
| [includes/users.php](/code/files/includes/users.php.md) | userSourcesInScope L224, portalProfileAccess L318, userManagerIsSafe L410 |  |
| [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md) | renderHeaderRight L1510 |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) | webchatGetOrCreateUser L181 |  |
| [self-service/verify-email.php](/code/files/self-service/verify-email.php.md) | L40 |  |
| [tickets/csat/index.php](/code/files/tickets/csat/index.php.md) | L166, L195 |  |
| [tickets/csat/survey.php](/code/files/tickets/csat/survey.php.md) | L32 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::action_create_ticket L2726 |  |

