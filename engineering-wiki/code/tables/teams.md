# teams

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NULL DEFAULT 1` |  |
| `can_access_all_tenants` | `TINYINT(1) NOT NULL DEFAULT 0` | Team company access. Defaults to 0 (grants nothing) — NOT 1 — so existing teams don't silently widen their members' access on upgrade. |
| `can_access_all_modules` | `TINYINT(1) NOT NULL DEFAULT 0` | Team module access (issue #30). Defaults to 0 (grants no modules) — same reasoning: a team must be explicitly granted modules, and under the default 'most' (union) mode a team defaulting to all would blow away every member's individual restrictions. Grants are in team_modules. |
| `auth_method` | `VARCHAR(10) NULL` | Default sign-in method for members set to "Follow team" (GH #41). NULL = not set, 'local' = password, 'provider' = auth_provider_id. |
| `auth_provider_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/delete_sso_provider.php](/code/files/api/system/delete_sso_provider.php.md):59 |  | update | `auth_method`, `auth_provider_id` |  |
| [api/tickets/delete_team.php](/code/files/api/tickets/delete_team.php.md):37 |  | delete |  |  |
| [api/tickets/save_team.php](/code/files/api/tickets/save_team.php.md):60 |  | update | `name`, `description`, `display_order`, `is_active`, `updated_datetime` |  |
| [api/tickets/save_team.php](/code/files/api/tickets/save_team.php.md):66 |  | insert | `name`, `description`, `display_order`, `is_active` |  |
| [api/tickets/save_team.php](/code/files/api/tickets/save_team.php.md):76 |  | update | `can_access_all_modules` |  |
| [api/tickets/save_team.php](/code/files/api/tickets/save_team.php.md):94 |  | update | `auth_method`, `auth_provider_id` |  |
| [api/tickets/save_team_companies.php](/code/files/api/tickets/save_team_companies.php.md):36 |  | update | `can_access_all_tenants` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/files/permissions.php](/code/files/api/files/permissions.php.md) | L69, L107 |  |
| [api/files/principals.php](/code/files/api/files/principals.php.md) | L13 |  |
| [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) | handleSearch L370 |  |
| [api/morning-checks/get_todays_checks.php](/code/files/api/morning-checks/get_todays_checks.php.md) | L39 |  |
| [api/projects/lookups.php](/code/files/api/projects/lookups.php.md) | L27 |  |
| [api/reporting/packs/shares.php](/code/files/api/reporting/packs/shares.php.md) | L34 |  |
| [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md) | L194 |  |
| [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md) | L317, L376, L420, L422, L431 |  |
| [api/system/get_effective_module_access.php](/code/files/api/system/get_effective_module_access.php.md) | L35 |  |
| [api/system/get_module_access.php](/code/files/api/system/get_module_access.php.md) | L36 |  |
| [api/system/save_module_grants.php](/code/files/api/system/save_module_grants.php.md) | L36 |  |
| [api/table-views/list.php](/code/files/api/table-views/list.php.md) | L47 |  |
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L39 |  |
| [api/tasks/list.php](/code/files/api/tasks/list.php.md) | L32, L125 |  |
| [api/tickets/get_analyst_teams.php](/code/files/api/tickets/get_analyst_teams.php.md) | L28 |  |
| [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md) | L46 |  |
| [api/tickets/get_department_teams.php](/code/files/api/tickets/get_department_teams.php.md) | L28 |  |
| [api/tickets/get_team_companies.php](/code/files/api/tickets/get_team_companies.php.md) | L28 |  |
| [api/tickets/get_teams.php](/code/files/api/tickets/get_teams.php.md) | L21, L40 |  |
| [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md) | L509 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiTaskSelect L42 |  |
| [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md) | analystSignInReady L54, analystSignInTeamMethods L73 |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) | calendarSyncReconcileTask L184 |  |
| [includes/files/acl.php](/code/files/includes/files/acl.php.md) | FilesAcl::teamIds L117, FilesAcl::entries L216 |  |
| [includes/functions.php](/code/files/includes/functions.php.md) | getAnalystAllowedModules L155 |  |
| [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) | projectAiFacts L286 |  |
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectDetail L397 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewTask L188 |  |
| [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md) | rpListShares L178 |  |
| [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) | L29 |  |
| [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md) | MandatoryFieldsService::fieldAvailable L164 |  |
| [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md) | MorningChecksService::listGroups L157 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::addMember L45, ProjectToolsService::members L1124, ProjectToolsService::memberName L1797 |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) | TasksService::validateTeam L1349 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::updateTicket L471, TicketsService::teamName L1244 |  |
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) | ssoTeamMappings L127, ssoSaveTeamMappings L145 |  |
| [includes/table_views.php](/code/files/includes/table_views.php.md) | tableViewSelect L60 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | getAccessibleTenantIds L216 |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md) | warRoomEnsureChannels L96, warRoomChannelList L132, warRoomChannel L220, warRoomMyMentions L864, warRoomSearch L1066, warRoomTranscriptSince L1364 |  |
| [system/roles/index.php](/code/files/system/roles/index.php.md) | L36 |  |
| [system/sso/index.php](/code/files/system/sso/index.php.md) | L34 |  |

