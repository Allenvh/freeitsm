# api/system/db_verify.php

> API endpoint - 4190 lines

API Endpoint: Database Verification Checks all tables and columns exist, creates any that are missing.

## The endpoint

| | |
|---|---|
| URL | `api/system/db_verify.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 227 |  | **alter** [users](/code/tables/users.md) |  |
| 233 |  | **update** [users](/code/tables/users.md) (`email`) |  |
| 266 |  | **alter** [ticket_audit](/code/tables/ticket_audit.md) |  |
| 291 |  | **alter** [asset_history](/code/tables/asset_history.md) |  |
| 322 |  | **alter** [users_assets](/code/tables/users_assets.md) |  |
| 348 |  | **alter** [emails](/code/tables/emails.md) |  |
| 377 |  | **alter** [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 490 |  | **alter** [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 515 |  | **update** [auth_providers](/code/tables/auth_providers.md) (`auto_create_analysts`) |  |
| 516 |  | **update** [auth_providers](/code/tables/auth_providers.md) (`analyst_fallback_mode`) |  |
| 529 |  | **update** [analysts](/code/tables/analysts.md) (`is_admin`) |  |
| 543 |  | **update** [analysts](/code/tables/analysts.md) (`can_access_all_modules`), read [analyst_modules](/code/tables/analyst_modules.md) |  |
| 568 |  | **update** [users](/code/tables/users.md) (`tenant_id`) |  |
| 573 |  | **update** [users](/code/tables/users.md) (`tenant_id`) |  |
| 576 |  | read [tenant_domains](/code/tables/tenant_domains.md) |  |
| 591 |  | read [users](/code/tables/users.md) |  |
| 606 |  | read [analysts](/code/tables/analysts.md) |  |
| 614 |  | **insert** [analysts](/code/tables/analysts.md) (`username`, `password_hash`, `full_name`, `email`, `is_active`, `is_admin`, `must_change_password`, `created_datetime`) |  |
| 636 |  | read [analysts](/code/tables/analysts.md) |  |
| 641 |  | **update** [analysts](/code/tables/analysts.md) (`must_change_password`) |  |
| 661 |  | read [tenants](/code/tables/tenants.md) |  |
| 663 |  | **insert** [tenants](/code/tables/tenants.md) (`name`, `is_default`, `is_active`, `created_datetime`) |  |
| 676 |  | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 677 |  | **insert** [lease_outcomes](/code/tables/lease_outcomes.md) (`name`, `kind`, `is_default`, `display_order`) |  |
| 687 |  | read [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) |  |
| 689 |  | **insert** [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) (`id`, `title`, `description`, `chart_type`, `aggregate_property`, `is_status_filterable`, `default_status_id`, `display_order`) |  |
| 716 |  | read [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) |  |
| 727 |  | **update** [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) (`aggregate_property`, `time_grouping`, `date_range`) |  |
| 738 |  | read [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md) |  |
| 740 |  | **insert** [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md) (`id`, `title`, `description`, `chart_type`, `aggregate_property`, `app_id`, `exclude_system_components`, `display_order`) |  |
| 778 |  | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 780 |  | **insert** [ticket_statuses](/code/tables/ticket_statuses.md) (`name`, `is_closed`, `colour`, `is_default`, `display_order`) |  |
| 792 |  | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 794 |  | **insert** [ticket_priorities](/code/tables/ticket_priorities.md) (`name`, `colour`, `is_default`, `display_order`) |  |
| 811 |  | read [project_roles](/code/tables/project_roles.md) |  |
| 812 |  | **insert** [project_roles](/code/tables/project_roles.md) (`name`, `description`, `display_order`) |  |
| 830 |  | read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 832 |  | **insert** [domain_statuses](/code/tables/domain_statuses.md) (`name`, `colour`, `alerts_enabled`, `display_order`) |  |
| 843 |  | read [ticket_types](/code/tables/ticket_types.md) |  |
| 845 |  | **insert** [ticket_types](/code/tables/ticket_types.md) (`name`, `description`, `is_active`, `display_order`, `tenant_id`) |  |
| 857 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 859 |  | **insert** [ticket_origins](/code/tables/ticket_origins.md) (`name`, `description`, `display_order`, `is_active`, `tenant_id`) |  |
| 877 |  | **alter** [lms_lessons](/code/tables/lms_lessons.md) |  |
| 882 |  | **alter** [lms_questions](/code/tables/lms_questions.md) |  |
| 887 |  | **alter** [lms_answers](/code/tables/lms_answers.md) |  |
| 895 |  | **alter** [lms_ct_test_skills](/code/tables/lms_ct_test_skills.md) |  |
| 896 |  | **alter** [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md) |  |
| 897 |  | **alter** [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md) |  |
| 898 |  | **alter** [lms_ct_sittings](/code/tables/lms_ct_sittings.md) |  |
| 910 |  | **alter** [document_links](/code/tables/document_links.md) |  |
| 917 |  | **alter** [tenant_settings](/code/tables/tenant_settings.md) |  |
| 922 |  | **alter** [document_text](/code/tables/document_text.md) |  |
| 927 |  | **alter** [document_access_log](/code/tables/document_access_log.md) |  |
| 937 |  | **alter** [ticket_number_history](/code/tables/ticket_number_history.md) |  |
| 977 |  | **alter** [tickets](/code/tables/tickets.md) |  |
| 986 |  | **alter** [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 994 |  | **alter** [asset_types](/code/tables/asset_types.md) |  |
| 1006 |  | **alter** [asset_physical_disks](/code/tables/asset_physical_disks.md) |  |
| 1044 |  | **update** [assets](/code/tables/assets.md) (`last_seen`), read [asset_history](/code/tables/asset_history.md) |  |
| 1070 |  | **alter** [asset_disk_hide_rules](/code/tables/asset_disk_hide_rules.md) |  |
| 1127 |  | **alter** [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) |  |
| 1132 |  | **alter** [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md) |  |
| 1135 |  | **alter** [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md) |  |
| 1149 |  | **alter** [rbac_roles](/code/tables/rbac_roles.md) |  |
| 1158 |  | **alter** [search_documents](/code/tables/search_documents.md) |  |
| 1163 |  | **alter** [rbac_team_roles](/code/tables/rbac_team_roles.md) |  |
| 1166 |  | **alter** [rbac_team_roles](/code/tables/rbac_team_roles.md) |  |
| 1172 |  | **alter** [tenant_domains](/code/tables/tenant_domains.md) |  |
| 1175 |  | **alter** [tenant_domains](/code/tables/tenant_domains.md) |  |
| 1180 |  | **alter** [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |
| 1183 |  | **alter** [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |
| 1190 |  | **alter** [messaging_channels](/code/tables/messaging_channels.md) |  |
| 1193 |  | **alter** [messaging_channels](/code/tables/messaging_channels.md) |  |
| 1198 |  | **alter** [tenant_channel_senders](/code/tables/tenant_channel_senders.md) |  |
| 1201 |  | **alter** [tenant_channel_senders](/code/tables/tenant_channel_senders.md) |  |
| 1206 |  | **alter** [messaging_templates](/code/tables/messaging_templates.md) |  |
| 1209 |  | **alter** [messaging_templates](/code/tables/messaging_templates.md) |  |
| 1231 |  | **alter** [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |
| 1236 |  | **alter** [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |
| 1243 |  | **alter** [webchat_widgets](/code/tables/webchat_widgets.md) |  |
| 1250 |  | **alter** [webchat_conversations](/code/tables/webchat_conversations.md) |  |
| 1253 |  | **alter** [webchat_conversations](/code/tables/webchat_conversations.md) |  |
| 1256 |  | **alter** [webchat_conversations](/code/tables/webchat_conversations.md) |  |
| 1262 |  | **alter** [webchat_messages](/code/tables/webchat_messages.md) |  |
| 1265 |  | **alter** [webchat_messages](/code/tables/webchat_messages.md) |  |
| 1277 |  | **alter** [warroom_channels](/code/tables/warroom_channels.md) |  |
| 1280 |  | **alter** [warroom_channels](/code/tables/warroom_channels.md) |  |
| 1283 |  | **alter** [warroom_channels](/code/tables/warroom_channels.md) |  |
| 1288 |  | **alter** [warroom_channels](/code/tables/warroom_channels.md) |  |
| 1292 |  | **alter** [warroom_channels](/code/tables/warroom_channels.md) |  |
| 1298 |  | **insert** [warroom_channels](/code/tables/warroom_channels.md) (`kind`, `created_datetime`) |  |
| 1306 |  | **alter** [warroom_channel_members](/code/tables/warroom_channel_members.md) |  |
| 1309 |  | **alter** [warroom_channel_members](/code/tables/warroom_channel_members.md) |  |
| 1312 |  | **alter** [warroom_channel_members](/code/tables/warroom_channel_members.md) |  |
| 1315 |  | **alter** [warroom_channel_members](/code/tables/warroom_channel_members.md) |  |
| 1340 |  | **insert** [warroom_channels](/code/tables/warroom_channels.md) (`kind`, `team_id`, `created_datetime`), read [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1348 |  | **update** [warroom_messages](/code/tables/warroom_messages.md) (`channel_id`), read [warroom_channels](/code/tables/warroom_channels.md) |  |
| 1361 |  | read [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1363 |  | **alter** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1364 |  | **alter** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1365 |  | **alter** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1371 |  | **alter** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1375 |  | **alter** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1380 |  | **alter** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1383 |  | **alter** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1386 |  | **alter** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1393 |  | **alter** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1399 |  | **alter** [warroom_mentions](/code/tables/warroom_mentions.md) |  |
| 1403 |  | **alter** [warroom_mentions](/code/tables/warroom_mentions.md) |  |
| 1406 |  | **alter** [warroom_mentions](/code/tables/warroom_mentions.md) |  |
| 1409 |  | **alter** [warroom_mentions](/code/tables/warroom_mentions.md) |  |
| 1414 |  | **alter** [warroom_attachments](/code/tables/warroom_attachments.md) |  |
| 1418 |  | **alter** [warroom_attachments](/code/tables/warroom_attachments.md) |  |
| 1421 |  | **alter** [warroom_attachments](/code/tables/warroom_attachments.md) |  |
| 1427 |  | **alter** [warroom_reads](/code/tables/warroom_reads.md) |  |
| 1430 |  | **alter** [warroom_reads](/code/tables/warroom_reads.md) |  |
| 1433 |  | **alter** [warroom_reads](/code/tables/warroom_reads.md) |  |
| 1444 |  | **alter** [warroom_presence](/code/tables/warroom_presence.md) |  |
| 1445 |  | **alter** [warroom_presence](/code/tables/warroom_presence.md) |  |
| 1448 |  | **alter** [warroom_presence](/code/tables/warroom_presence.md) |  |
| 1451 |  | **alter** [warroom_presence](/code/tables/warroom_presence.md) |  |
| 1454 |  | **alter** [warroom_presence](/code/tables/warroom_presence.md) |  |
| 1457 |  | **alter** [warroom_presence](/code/tables/warroom_presence.md) |  |
| 1464 |  | **alter** [webchat_widgets](/code/tables/webchat_widgets.md) |  |
| 1472 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 1475 |  | **insert** [ticket_origins](/code/tables/ticket_origins.md) (`name`, `description`, `display_order`, `is_active`, `tenant_id`) |  |
| 1481 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 1484 |  | **insert** [ticket_origins](/code/tables/ticket_origins.md) (`name`, `description`, `display_order`, `is_active`, `tenant_id`) |  |
| 1492 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 1495 |  | **insert** [ticket_origins](/code/tables/ticket_origins.md) (`name`, `description`, `display_order`, `is_active`, `tenant_id`) |  |
| 1503 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 1506 |  | **insert** [ticket_origins](/code/tables/ticket_origins.md) (`name`, `description`, `display_order`, `is_active`, `tenant_id`) |  |
| 1514 |  | **alter** [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 1517 |  | **alter** [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 1522 |  | **alter** [ticket_types](/code/tables/ticket_types.md) |  |
| 1528 |  | **alter** [ticket_types](/code/tables/ticket_types.md) |  |
| 1531 |  | **alter** [ticket_types](/code/tables/ticket_types.md) |  |
| 1566 |  | **alter** [assets](/code/tables/assets.md) |  |
| 1569 |  | **alter** [assets](/code/tables/assets.md) |  |
| 1578 |  | **alter** [tasks](/code/tables/tasks.md) |  |
| 1581 |  | **alter** [tasks](/code/tables/tasks.md) |  |
| 1590 |  | **alter** [tasks](/code/tables/tasks.md) |  |
| 1596 |  | **alter** [tasks](/code/tables/tasks.md) |  |
| 1599 |  | **alter** [tasks](/code/tables/tasks.md) |  |
| 1604 |  | **alter** [tasks](/code/tables/tasks.md) |  |
| 1612 |  | **alter** [apikeys](/code/tables/apikeys.md) |  |
| 1624 |  | **alter** [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 1632 |  | **alter** [ticket_origins](/code/tables/ticket_origins.md) |  |
| 1637 |  | **alter** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 1640 |  | **alter** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 1643 |  | **alter** [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |
| 1648 |  | **alter** [team_tenant_access](/code/tables/team_tenant_access.md) |  |
| 1651 |  | **alter** [team_tenant_access](/code/tables/team_tenant_access.md) |  |
| 1654 |  | **alter** [team_tenant_access](/code/tables/team_tenant_access.md) |  |
| 1665 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 1668 |  | **update** [contracts](/code/tables/contracts.md) (`tenant_id`), read [users](/code/tables/users.md) |  |
| 1672 |  | read [tenants](/code/tables/tenants.md) |  |
| 1673 |  | **update** [contracts](/code/tables/contracts.md) (`tenant_id`) |  |
| 1686 |  | read [tenants](/code/tables/tenants.md) |  |
| 1689 |  | **update** [contracts](/code/tables/contracts.md) (`cost_centre_id`, `cost_centre`), read [cost_centres](/code/tables/cost_centres.md) |  |
| 1706 |  | **alter** [tickets](/code/tables/tickets.md) |  |
| 1709 |  | **alter** [tickets](/code/tables/tickets.md) |  |
| 1712 |  | read [tenants](/code/tables/tenants.md) |  |
| 1714 |  | **update** [tickets](/code/tables/tickets.md) (`tenant_id`) |  |
| 1730 |  | **alter** [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 1733 |  | **alter** [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 1736 |  | read [tenants](/code/tables/tenants.md) |  |
| 1738 |  | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`tenant_id`) |  |
| 1754 |  | **alter** [sla_calendar_hours](/code/tables/sla_calendar_hours.md) |  |
| 1757 |  | **alter** [sla_calendar_hours](/code/tables/sla_calendar_hours.md) |  |
| 1762 |  | **alter** [sla_calendar_holidays](/code/tables/sla_calendar_holidays.md) |  |
| 1765 |  | **alter** [sla_calendar_holidays](/code/tables/sla_calendar_holidays.md) |  |
| 1770 |  | **alter** [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 1776 |  | **alter** [sla_notification_rules](/code/tables/sla_notification_rules.md) |  |
| 1779 |  | **alter** [sla_notification_rules](/code/tables/sla_notification_rules.md) |  |
| 1784 |  | **alter** [sla_notifications_sent](/code/tables/sla_notifications_sent.md) |  |
| 1787 |  | **alter** [sla_notifications_sent](/code/tables/sla_notifications_sent.md) |  |
| 1792 |  | **alter** [sla_cron_runs](/code/tables/sla_cron_runs.md) |  |
| 1795 |  | **alter** [sla_cron_runs](/code/tables/sla_cron_runs.md) |  |
| 1802 |  | **alter** [ticket_recordings](/code/tables/ticket_recordings.md) |  |
| 1805 |  | **alter** [ticket_recordings](/code/tables/ticket_recordings.md) |  |
| 1808 |  | **alter** [ticket_recordings](/code/tables/ticket_recordings.md) |  |
| 1811 |  | **alter** [ticket_recordings](/code/tables/ticket_recordings.md) |  |
| 1818 |  | **alter** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 1821 |  | **alter** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 1824 |  | **alter** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 1827 |  | **alter** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 1830 |  | **alter** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 1839 |  | **alter** [ticket_merges](/code/tables/ticket_merges.md) |  |
| 1842 |  | **alter** [ticket_merges](/code/tables/ticket_merges.md) |  |
| 1845 |  | **alter** [ticket_merges](/code/tables/ticket_merges.md) |  |
| 1852 |  | **alter** [ticket_splits](/code/tables/ticket_splits.md) |  |
| 1855 |  | **alter** [ticket_splits](/code/tables/ticket_splits.md) |  |
| 1858 |  | **alter** [ticket_splits](/code/tables/ticket_splits.md) |  |
| 1866 |  | **alter** [tickets](/code/tables/tickets.md) |  |
| 1873 |  | read [sla_calendars](/code/tables/sla_calendars.md) |  |
| 1875 |  | **insert** [sla_calendars](/code/tables/sla_calendars.md) (`name`, `timezone`, `is_default`) |  |
| 1878 |  | **insert** [sla_calendar_hours](/code/tables/sla_calendar_hours.md) (`calendar_id`, `weekday`, `start_time`, `end_time`) |  |
| 2011 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 2034 |  | **update** [system_settings](/code/tables/system_settings.md) (`setting_value`) |  |

*(394 more not shown)*

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/setup_state.php](/code/files/includes/setup_state.php.md), [includes/db_errors.php](/code/files/includes/db_errors.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/db_verify_schema.php](/code/files/includes/db_verify_schema.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/db_verify_index_parse.php](/code/files/includes/db_verify_index_parse.php.md), [includes/db_verify_column_parse.php](/code/files/includes/db_verify_column_parse.php.md), [includes/db_verify_indexes.php](/code/files/includes/db_verify_indexes.php.md), [includes/db_verify_modules.php](/code/files/includes/db_verify_modules.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [dbErrorIsUnknownColumn()](/code/functions/dbErrorIsUnknownColumn.md), [dbVerifyColumnSelfCheck()](/code/functions/dbVerifyColumnSelfCheck.md), [dbVerifyIndexListSelfCheck()](/code/functions/dbVerifyIndexListSelfCheck.md), [dbVerifyIndexTypeOf()](/code/functions/dbVerifyIndexTypeOf.md), [dbVerifyModuleForTable()](/code/functions/dbVerifyModuleForTable.md), [dbVerifyModuleMeta()](/code/functions/dbVerifyModuleMeta.md), [encryptValue()](/code/functions/encryptValue.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [installIsUnprovisioned()](/code/functions/installIsUnprovisioned.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [isFreemailDomain()](/code/functions/isFreemailDomain.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [uploadRepairHtaccessTree()](/code/functions/uploadRepairHtaccessTree.md)

