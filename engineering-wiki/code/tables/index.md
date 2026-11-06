# Database tables

From [includes/db_verify_schema.php](/code/files/includes/db_verify_schema.php.md) - the definition Database Verification builds every install from.

| Table | Columns | Read by | Written by | About |
|---|--:|--:|--:|---|
| [analyst_dashboard_widgets](/code/tables/analyst_dashboard_widgets.md) | 6 | 2 | 4 |  |
| [analyst_modules](/code/tables/analyst_modules.md) | 3 | 4 | 5 |  |
| [analyst_recent_trail](/code/tables/analyst_recent_trail.md) | 5 | 1 | 1 | The recent trail (#124): every record an analyst has opened, in order, so the waffle drawer can draw an outline of how they got where they are. |
| [analyst_signatures](/code/tables/analyst_signatures.md) | 8 | 2 | 3 |  |
| [analyst_software_dashboard_widgets](/code/tables/analyst_software_dashboard_widgets.md) | 5 | 2 | 4 |  |
| [analyst_sso_identities](/code/tables/analyst_sso_identities.md) | 7 | 2 | 2 |  |
| [analyst_teams](/code/tables/analyst_teams.md) | 5 | 24 | 4 |  |
| [analyst_tenant_access](/code/tables/analyst_tenant_access.md) | 4 | 4 | 3 | Which analysts may access which tenants (only consulted when an analyst is NOT flagged can_access_all_tenants). |
| [analyst_ticket_dashboard_widgets](/code/tables/analyst_ticket_dashboard_widgets.md) | 7 | 2 | 4 |  |
| [analysts](/code/tables/analysts.md) | 28 | 213 | 16 |  |
| [api_key_rate_limits](/code/tables/api_key_rate_limits.md) | 4 | 1 | 1 |  |
| [api_keys](/code/tables/api_keys.md) | 14 | 3 | 4 | REST API v1 keys (System > API) — distinct from the legacy `apikeys` table above (api/external ingest): stored hashed, granular permission map (JSON), optional company scope, acts as an analyst. |
| [api_rate_limits](/code/tables/api_rate_limits.md) | 4 | 1 | 1 |  |
| [apikeys](/code/tables/apikeys.md) | 7 | 6 | 3 |  |
| [asset_checkout_log](/code/tables/asset_checkout_log.md) | 9 | 3 | 1 | Check-in / check-out custody trail. |
| [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) | 10 | 5 | 3 |  |
| [asset_devices](/code/tables/asset_devices.md) | 8 | 2 | 1 |  |
| [asset_disk_hide_rules](/code/tables/asset_disk_hide_rules.md) | 7 | 1 | 1 | Drives you have told FreeITSM you do not want to look at. |
| [asset_disks](/code/tables/asset_disks.md) | 9 | 3 | 2 |  |
| [asset_field_options](/code/tables/asset_field_options.md) | 5 | 4 | 2 |  |
| [asset_field_set_assets](/code/tables/asset_field_set_assets.md) | 5 | 2 | 1 |  |
| [asset_field_set_fields](/code/tables/asset_field_set_fields.md) | 6 | 2 | 1 |  |
| [asset_field_sets](/code/tables/asset_field_sets.md) | 7 | 2 | 1 |  |
| [asset_field_values](/code/tables/asset_field_values.md) | 9 | 4 | 0 |  |
| [asset_fields](/code/tables/asset_fields.md) | 12 | 6 | 1 | Custom asset fields (docs/design/flexible-asset-fields.md) The catalogue, the sets that bundle fields, the two ways a set attaches (to a type, or to one asset), and the answers. |
| [asset_handover_templates](/code/tables/asset_handover_templates.md) | 7 | 1 | 1 | Optional grouping + routing for the morning round (discussion #64). |
| [asset_history](/code/tables/asset_history.md) | 7 | 4 | 4 |  |
| [asset_import_mappings](/code/tables/asset_import_mappings.md) | 6 | 0 | 0 |  |
| [asset_import_profiles](/code/tables/asset_import_profiles.md) | 16 | 0 | 0 | Asset import (docs/design/flexible-asset-fields.md §6) |
| [asset_import_run_entries](/code/tables/asset_import_run_entries.md) | 11 | 3 | 1 |  |
| [asset_import_runs](/code/tables/asset_import_runs.md) | 18 | 3 | 1 |  |
| [asset_locations](/code/tables/asset_locations.md) | 7 | 19 | 2 | Arbitrary-depth physical location tree (adjacency list). |
| [asset_network_adapters](/code/tables/asset_network_adapters.md) | 8 | 1 | 1 |  |
| [asset_physical_disks](/code/tables/asset_physical_disks.md) | 7 | 3 | 1 | The physical drives themselves, as opposed to the lettered volumes above. |
| [asset_status_types](/code/tables/asset_status_types.md) | 8 | 17 | 2 |  |
| [asset_tag_counters](/code/tables/asset_tag_counters.md) | 3 | 1 | 1 | Generated asset tag counters (PR #164) - ticket_number_counters' shape, and the same ⚠️: counter_key is the PK, declared in db_verify.php's `$primaryKeys` map, not here. |
| [asset_type_field_sets](/code/tables/asset_type_field_sets.md) | 4 | 2 | 1 |  |
| [asset_types](/code/tables/asset_types.md) | 9 | 21 | 2 |  |
| [assets](/code/tables/assets.md) | 35 | 54 | 10 |  |
| [attachment_text](/code/tables/attachment_text.md) | 6 | 4 | 3 | Text pulled out of an attachment (discussion #53). |
| [auth_providers](/code/tables/auth_providers.md) | 66 | 43 | 7 |  |
| [calendar_categories](/code/tables/calendar_categories.md) | 8 | 8 | 3 |  |
| [calendar_connections](/code/tables/calendar_connections.md) | 12 | 2 | 2 | ── Scheduled work -> the analyst's own calendar (GH #75) ──────────────── Admin-level connection, per-analyst enrolment, and the map of what we put where. |
| [calendar_enrolments](/code/tables/calendar_enrolments.md) | 16 | 4 | 5 |  |
| [calendar_events](/code/tables/calendar_events.md) | 14 | 7 | 6 |  |
| [calendar_sync_events](/code/tables/calendar_sync_events.md) | 10 | 4 | 4 | One row per event we have written into somebody's calendar. |
| [carddav_write_log](/code/tables/carddav_write_log.md) | 11 | 2 | 2 | Every attempt to write a contact back to an address book — succeeded, refused, or failed. |
| [change_attachments](/code/tables/change_attachments.md) | 8 | 5 | 3 |  |
| [change_audit](/code/tables/change_audit.md) | 9 | 2 | 2 |  |
| [change_cab_members](/code/tables/change_cab_members.md) | 10 | 4 | 1 |  |
| [change_categories](/code/tables/change_categories.md) | 6 | 3 | 0 |  |
| [change_checklist_items](/code/tables/change_checklist_items.md) | 8 | 0 | 0 |  |
| [change_comments](/code/tables/change_comments.md) | 7 | 4 | 1 |  |
| [change_field_layout](/code/tables/change_field_layout.md) | 5 | 2 | 2 |  |
| [change_field_sections](/code/tables/change_field_sections.md) | 4 | 4 | 2 | Layout tables that drive the Change form's section structure. |
| [change_impacts](/code/tables/change_impacts.md) | 7 | 11 | 3 |  |
| [change_notifications](/code/tables/change_notifications.md) | 7 | 0 | 0 |  |
| [change_priorities](/code/tables/change_priorities.md) | 7 | 12 | 3 |  |
| [change_relations](/code/tables/change_relations.md) | 7 | 5 | 1 |  |
| [change_statuses](/code/tables/change_statuses.md) | 8 | 20 | 3 |  |
| [change_templates](/code/tables/change_templates.md) | 18 | 0 | 0 |  |
| [change_tickets](/code/tables/change_tickets.md) | 5 | 4 | 1 | Incidents (tickets) linked to a change — twin of problem_tickets. |
| [change_types](/code/tables/change_types.md) | 7 | 11 | 3 |  |
| [changes](/code/tables/changes.md) | 39 | 30 | 2 |  |
| [checklist_categories](/code/tables/checklist_categories.md) | 4 | 3 | 1 |  |
| [checklist_roles](/code/tables/checklist_roles.md) | 4 | 4 | 1 |  |
| [checklist_template_items](/code/tables/checklist_template_items.md) | 12 | 6 | 2 |  |
| [checklist_templates](/code/tables/checklist_templates.md) | 14 | 6 | 2 |  |
| [cmdb_class_properties](/code/tables/cmdb_class_properties.md) | 11 | 14 | 2 |  |
| [cmdb_class_property_options](/code/tables/cmdb_class_property_options.md) | 6 | 4 | 1 |  |
| [cmdb_classes](/code/tables/cmdb_classes.md) | 10 | 21 | 2 |  |
| [cmdb_icons](/code/tables/cmdb_icons.md) | 5 | 9 | 1 | CMDB ---------------------------------------------------------- |
| [cmdb_object_properties](/code/tables/cmdb_object_properties.md) | 9 | 8 | 1 |  |
| [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) | 6 | 12 | 1 |  |
| [cmdb_objects](/code/tables/cmdb_objects.md) | 11 | 25 | 2 |  |
| [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) | 9 | 12 | 3 |  |
| [contacts](/code/tables/contacts.md) | 12 | 15 | 4 |  |
| [contract_assets](/code/tables/contract_assets.md) | 6 | 4 | 1 | Tickets ↔ assets (discussion #57). |
| [contract_statuses](/code/tables/contract_statuses.md) | 7 | 10 | 2 |  |
| [contract_term_tabs](/code/tables/contract_term_tabs.md) | 7 | 3 | 2 |  |
| [contract_term_values](/code/tables/contract_term_values.md) | 7 | 3 | 3 |  |
| [contracts](/code/tables/contracts.md) | 29 | 28 | 5 |  |
| [cost_centres](/code/tables/cost_centres.md) | 10 | 11 | 1 | Cost centres (GH #160, stage 1). |
| [department_teams](/code/tables/department_teams.md) | 5 | 6 | 3 |  |
| [departments](/code/tables/departments.md) | 8 | 25 | 2 |  |
| [directory_sync_entries](/code/tables/directory_sync_entries.md) | 8 | 1 | 1 | What a run did to each PERSON. |
| [directory_sync_runs](/code/tables/directory_sync_runs.md) | 15 | 3 | 2 | One row per directory sync RUN — the attempt, not just the success. |
| [document_access_log](/code/tables/document_access_log.md) | 6 | 0 | 1 | Who fetched which document, and when. |
| [document_links](/code/tables/document_links.md) | 7 | 13 | 4 | What each document is attached to. |
| [document_text](/code/tables/document_text.md) | 6 | 2 | 1 | Extracted text for an attached document (discussion #76). |
| [documents](/code/tables/documents.md) | 15 | 15 | 3 | A document is either a file we hold or a link to somewhere else (an external DMS). |
| [domain_alerts_sent](/code/tables/domain_alerts_sent.md) | 5 | 1 | 1 |  |
| [domain_audit](/code/tables/domain_audit.md) | 8 | 3 | 3 |  |
| [domain_certificates](/code/tables/domain_certificates.md) | 10 | 3 | 2 |  |
| [domain_cmdb_objects](/code/tables/domain_cmdb_objects.md) | 5 | 0 | 0 | Domains joined to the rest of FreeITSM (3.0.0): four links and the record of the status incidents a domain raised. |
| [domain_knowledge_articles](/code/tables/domain_knowledge_articles.md) | 5 | 0 | 0 |  |
| [domain_lookalikes](/code/tables/domain_lookalikes.md) | 9 | 3 | 2 |  |
| [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md) | 12 | 4 | 1 |  |
| [domain_status_incidents](/code/tables/domain_status_incidents.md) | 7 | 1 | 1 |  |
| [domain_status_services](/code/tables/domain_status_services.md) | 5 | 2 | 0 |  |
| [domain_statuses](/code/tables/domain_statuses.md) | 8 | 13 | 2 | Domains module (GitHub #154) — see the matching block in freeitsm.sql for what each table is for. |
| [domains](/code/tables/domains.md) | 53 | 24 | 1 |  |
| [email_attachments](/code/tables/email_attachments.md) | 10 | 17 | 11 |  |
| [email_send_log](/code/tables/email_send_log.md) | 11 | 1 | 1 |  |
| [emails](/code/tables/emails.md) | 28 | 48 | 20 |  |
| [feature_bingo_dismissed](/code/tables/feature_bingo_dismissed.md) | 3 | 1 | 1 | The self-service portal's bell (discussion #62) - `notifications` for portal users. |
| [files_audit](/code/tables/files_audit.md) | 12 | 1 | 1 | Every action, reads and refusals included. |
| [files_folders](/code/tables/files_folders.md) | 11 | 4 | 2 | Files: secure file and folder sharing (files/) PESSIMISTIC: nothing is visible until files_permissions grants it. |
| [files_items](/code/tables/files_items.md) | 12 | 7 | 3 |  |
| [files_permissions](/code/tables/files_permissions.md) | 7 | 3 | 3 | Grants only, no Deny. |
| [files_shortcuts](/code/tables/files_shortcuts.md) | 8 | 3 | 1 | A shortcut lives in a folder (folder_id) OR on one person's desktop (analyst_id). |
| [files_uploads](/code/tables/files_uploads.md) | 10 | 1 | 1 |  |
| [files_versions](/code/tables/files_versions.md) | 9 | 7 | 2 | A copied file SHARES its source's storage_path - a purge must check nothing else points at a .bin before deleting it. |
| [form_audiences](/code/tables/form_audiences.md) | 4 | 2 | 1 | A named exercise that several forms' submissions belong to - "Staff        Survey 2026", "ISO 27001 evidence round". |
| [form_collections](/code/tables/form_collections.md) | 9 | 4 | 1 |  |
| [form_drafts](/code/tables/form_drafts.md) | 7 | 1 | 1 | A form somebody started and did not finish. |
| [form_fields](/code/tables/form_fields.md) | 10 | 10 | 2 |  |
| [form_submission_data](/code/tables/form_submission_data.md) | 5 | 4 | 2 |  |
| [form_submissions](/code/tables/form_submissions.md) | 13 | 9 | 3 |  |
| [forms](/code/tables/forms.md) | 17 | 20 | 2 |  |
| [freemail_domains](/code/tables/freemail_domains.md) | 3 | 3 | 2 | Admin-added public/free-email domains (gmail.com etc. |
| [integration_comment_map](/code/tables/integration_comment_map.md) | 8 | 2 | 1 | Echo suppression + idempotency for comment sync. |
| [integration_connections](/code/tables/integration_connections.md) | 18 | 4 | 5 | External issue trackers — see freeitsm.sql for the full commentary. |
| [integration_field_maps](/code/tables/integration_field_maps.md) | 6 | 1 | 1 | What our values mean in the tracker's vocabulary (V3 mapping). |
| [integration_links](/code/tables/integration_links.md) | 14 | 4 | 2 | The ticket ↔ external issue spine. |
| [intune_app_sync_job_assets](/code/tables/intune_app_sync_job_assets.md) | 7 | 2 | 1 |  |
| [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) | 8 | 3 | 2 |  |
| [intune_devices](/code/tables/intune_devices.md) | 31 | 7 | 1 |  |
| [intune_sync_jobs](/code/tables/intune_sync_jobs.md) | 7 | 3 | 2 |  |
| [ip_login_bans](/code/tables/ip_login_bans.md) | 6 | 1 | 0 |  |
| [knowledge_acl](/code/tables/knowledge_acl.md) | 7 | 3 | 2 | ── The access list ───────────────────────────────────────────────────── 🔑 THERE IS NO allow/deny COLUMN, AND ITS ABSENCE IS THE GUARANTEE. |
| [knowledge_article_tags](/code/tables/knowledge_article_tags.md) | 3 | 8 | 3 |  |
| [knowledge_article_versions](/code/tables/knowledge_article_versions.md) | 7 | 1 | 3 |  |
| [knowledge_articles](/code/tables/knowledge_articles.md) | 22 | 41 | 7 |  |
| [knowledge_audit](/code/tables/knowledge_audit.md) | 9 | 1 | 1 | ── Audit ─────────────────────────────────────────────────────────────── Modelled on document_access_log, which already does this job for attached documents. |
| [knowledge_folders](/code/tables/knowledge_folders.md) | 10 | 5 | 1 | ── Knowledge folders ─────────────────────────────────────────────────── A document lives in EXACTLY ONE folder. |
| [knowledge_gap_cluster_tickets](/code/tables/knowledge_gap_cluster_tickets.md) | 3 | 3 | 1 |  |
| [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) | 16 | 5 | 3 |  |
| [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) | 8 | 2 | 1 | Knowledge gaps — the Knowledge assistant. |
| [knowledge_shortcuts](/code/tables/knowledge_shortcuts.md) | 5 | 1 | 1 | ── Shortcuts ─────────────────────────────────────────────────────────── A pointer with NO permissions of its own — it resolves to the target and the target's rules decide. |
| [knowledge_tags](/code/tables/knowledge_tags.md) | 4 | 7 | 3 |  |
| [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md) | 6 | 8 | 1 |  |
| [knowledge_user_groups](/code/tables/knowledge_user_groups.md) | 7 | 11 | 1 | ── User groups ───────────────────────────────────────────────────────── NOT lms_learning_groups. |
| [lease_assets](/code/tables/lease_assets.md) | 11 | 4 | 1 | An asset on a schedule; its own start/end when they differ from the schedule's. |
| [lease_outcomes](/code/tables/lease_outcomes.md) | 7 | 5 | 3 | Leases (3.5.0) - Assets -> Leases; see includes/leases.php What became of a leased asset (Assets -> Settings -> Leasing). |
| [lease_schedules](/code/tables/lease_schedules.md) | 13 | 3 | 1 | A group of kit on one agreement with its own dates and cost. |
| [leases](/code/tables/leases.md) | 12 | 3 | 1 | The agreement. |
| [lms_answers](/code/tables/lms_answers.md) | 7 | 3 | 1 |  |
| [lms_cmi_data](/code/tables/lms_cmi_data.md) | 6 | 2 | 3 |  |
| [lms_course_assignments](/code/tables/lms_course_assignments.md) | 8 | 4 | 2 |  |
| [lms_courses](/code/tables/lms_courses.md) | 14 | 14 | 2 |  |
| [lms_ct_questions](/code/tables/lms_ct_questions.md) | 13 | 2 | 2 | LMS competency tests (3.0.0) The question bank. |
| [lms_ct_sittings](/code/tables/lms_ct_sittings.md) | 18 | 4 | 3 | One candidate's attempt. |
| [lms_ct_test_questions](/code/tables/lms_ct_test_questions.md) | 4 | 2 | 2 |  |
| [lms_ct_test_skills](/code/tables/lms_ct_test_skills.md) | 7 | 1 | 1 |  |
| [lms_ct_tests](/code/tables/lms_ct_tests.md) | 9 | 2 | 1 |  |
| [lms_learning_group_members](/code/tables/lms_learning_group_members.md) | 5 | 3 | 2 |  |
| [lms_learning_groups](/code/tables/lms_learning_groups.md) | 8 | 5 | 2 |  |
| [lms_lessons](/code/tables/lms_lessons.md) | 8 | 5 | 1 | Native course content. |
| [lms_progress](/code/tables/lms_progress.md) | 19 | 8 | 4 |  |
| [lms_questions](/code/tables/lms_questions.md) | 9 | 4 | 1 |  |
| [lms_reminders_sent](/code/tables/lms_reminders_sent.md) | 7 | 1 | 1 | The fire-once record for training reminders. |
| [mailbox_activity_log](/code/tables/mailbox_activity_log.md) | 10 | 2 | 1 |  |
| [mailbox_email_whitelist](/code/tables/mailbox_email_whitelist.md) | 5 | 2 | 1 |  |
| [manager_grants](/code/tables/manager_grants.md) | 8 | 3 | 1 | Management lines (discussion #62, step 3): who a portal user may see the tickets of, as a MANAGER. |
| [messaging_channels](/code/tables/messaging_channels.md) | 14 | 9 | 7 | Messaging channel "inbox" — the channel twin of target_mailboxes. |
| [messaging_identity_links](/code/tables/messaging_identity_links.md) | 8 | 2 | 1 | Which requester a chat belongs to, on ONE channel (Telegram, PR #159). |
| [messaging_templates](/code/tables/messaging_templates.md) | 9 | 2 | 2 | Pre-approved provider message templates (replying after the WhatsApp 24h window). |
| [morningChecks_Checks](/code/tables/morningChecks_Checks.md) | 10 | 0 | 0 |  |
| [morningChecks_Groups](/code/tables/morningChecks_Groups.md) | 9 | 0 | 0 |  |
| [morningChecks_ResultLinks](/code/tables/morningChecks_ResultLinks.md) | 7 | 0 | 0 | Tickets/tasks raised from a check. |
| [morningChecks_Results](/code/tables/morningChecks_Results.md) | 11 | 0 | 0 |  |
| [morningChecks_Statuses](/code/tables/morningChecks_Statuses.md) | 8 | 0 | 0 | Configurable status options for morning checks. |
| [network_diagram_connectors](/code/tables/network_diagram_connectors.md) | 8 | 4 | 4 |  |
| [network_diagram_nodes](/code/tables/network_diagram_nodes.md) | 8 | 6 | 4 |  |
| [network_diagrams](/code/tables/network_diagrams.md) | 17 | 6 | 3 | Network Mapper — visual diagrams over the CMDB graph (see freeitsm.sql header). |
| [notifications](/code/tables/notifications.md) | 13 | 0 | 0 | In-app notifications (discussion #55). |
| [password_reset_tokens](/code/tables/password_reset_tokens.md) | 6 | 1 | 2 |  |
| [payment_schedules](/code/tables/payment_schedules.md) | 7 | 5 | 2 |  |
| [photo_album](/code/tables/photo_album.md) | 11 | 1 | 1 | System -> Photo Album: webcam photos as ASCII art. |
| [portal_notifications](/code/tables/portal_notifications.md) | 13 | 1 | 0 |  |
| [portal_user_preferences](/code/tables/portal_user_preferences.md) | 5 | 1 | 1 | The same idea for SELF-SERVICE PORTAL users, who have no analyst row and so cannot use the table above. |
| [problem_audit](/code/tables/problem_audit.md) | 8 | 2 | 1 |  |
| [problem_notes](/code/tables/problem_notes.md) | 5 | 2 | 1 | Free-text journal notes on a problem (who / when / the note). |
| [problem_priorities](/code/tables/problem_priorities.md) | 7 | 8 | 3 |  |
| [problem_statuses](/code/tables/problem_statuses.md) | 8 | 13 | 3 |  |
| [problem_tickets](/code/tables/problem_tickets.md) | 5 | 11 | 1 | The incident link: which tickets a problem explains. |
| [problems](/code/tables/problems.md) | 15 | 17 | 1 | Problem Management A Problem is the root cause behind one or more incidents (tickets). |
| [process_annotations](/code/tables/process_annotations.md) | 9 | 2 | 1 |  |
| [process_connectors](/code/tables/process_connectors.md) | 6 | 1 | 2 |  |
| [process_groups](/code/tables/process_groups.md) | 9 | 2 | 1 |  |
| [process_lanes](/code/tables/process_lanes.md) | 7 | 2 | 1 |  |
| [process_step_types](/code/tables/process_step_types.md) | 8 | 5 | 4 |  |
| [process_steps](/code/tables/process_steps.md) | 15 | 1 | 2 |  |
| [processes](/code/tables/processes.md) | 7 | 3 | 2 |  |
| [project_ai_messages](/code/tables/project_ai_messages.md) | 13 | 1 | 1 |  |
| [project_ai_threads](/code/tables/project_ai_threads.md) | 8 | 1 | 1 | The AI project assistant (3.3.0) - includes/projects/assistant_chat.php. |
| [project_asset_target_snapshots](/code/tables/project_asset_target_snapshots.md) | 6 | 1 | 2 |  |
| [project_asset_targets](/code/tables/project_asset_targets.md) | 16 | 3 | 2 | Asset targets (3.2.0): live progress measured from Assets. |
| [project_assets](/code/tables/project_assets.md) | 6 | 1 | 0 | Projects joined to the rest of FreeITSM (3.2.0) - one join table per kind. |
| [project_audit](/code/tables/project_audit.md) | 9 | 4 | 1 |  |
| [project_baselines](/code/tables/project_baselines.md) | 18 | 3 | 2 | Change control (3.3.0) - includes/projects/control.php. |
| [project_benefit_measures](/code/tables/project_benefit_measures.md) | 8 | 1 | 2 |  |
| [project_benefits](/code/tables/project_benefits.md) | 19 | 4 | 2 | Benefits realisation (3.3.0) - includes/projects/benefits.php. |
| [project_budget_lines](/code/tables/project_budget_lines.md) | 17 | 4 | 1 | Budget (3.2.0) - includes/projects/budget.php. |
| [project_change_requests](/code/tables/project_change_requests.md) | 19 | 4 | 1 |  |
| [project_changes](/code/tables/project_changes.md) | 6 | 2 | 0 |  |
| [project_cmdb_objects](/code/tables/project_cmdb_objects.md) | 6 | 0 | 0 |  |
| [project_contracts](/code/tables/project_contracts.md) | 6 | 2 | 0 |  |
| [project_gate_items](/code/tables/project_gate_items.md) | 15 | 5 | 3 | Gate checklists (3.3.0) - includes/projects/gatecheck.php. |
| [project_items](/code/tables/project_items.md) | 13 | 5 | 2 |  |
| [project_knowledge_articles](/code/tables/project_knowledge_articles.md) | 6 | 0 | 0 |  |
| [project_labour_rates](/code/tables/project_labour_rates.md) | 8 | 2 | 3 |  |
| [project_members](/code/tables/project_members.md) | 17 | 8 | 1 |  |
| [project_milestones](/code/tables/project_milestones.md) | 13 | 11 | 3 | Milestones (3.3.0): the dates a project promises - includes/projects/milestones.php. |
| [project_problems](/code/tables/project_problems.md) | 6 | 0 | 0 | Projects 3.3.0: problems a project fixes. |
| [project_raci](/code/tables/project_raci.md) | 6 | 2 | 1 |  |
| [project_raid](/code/tables/project_raid.md) | 25 | 8 | 2 |  |
| [project_raid_tasks](/code/tables/project_raid_tasks.md) | 5 | 1 | 3 | Follow-up actions on a RAID entry (3.3.0) - project tasks, joined. |
| [project_reports](/code/tables/project_reports.md) | 19 | 2 | 1 | AI project manager (3.2.0): briefings and reports - see includes/projects/ai.php. |
| [project_roles](/code/tables/project_roles.md) | 7 | 6 | 2 | Projects phase 2 (3.2.0): roles, members, scope, RACI, RAID, tolerances. |
| [project_stages](/code/tables/project_stages.md) | 17 | 18 | 3 | Phases, stages and sprints in ONE table - the same thing through different methods. |
| [project_task_flow](/code/tables/project_task_flow.md) | 5 | 1 | 1 | Task flow (3.3.0) - includes/projects/flow.php. |
| [project_templates](/code/tables/project_templates.md) | 9 | 2 | 1 | Project templates saved from a project (3.2.0); built-ins live in code. |
| [project_tickets](/code/tables/project_tickets.md) | 6 | 1 | 0 |  |
| [project_tolerances](/code/tables/project_tolerances.md) | 6 | 2 | 2 |  |
| [projects](/code/tables/projects.md) | 38 | 30 | 7 | Projects (3.2.0) - see docs/design/projects.md A container over ordinary tasks (tasks.project_id), scoped like tickets. |
| [proxmox_connections](/code/tables/proxmox_connections.md) | 12 | 4 | 2 | Proxmox VE servers (several per install). |
| [proxmox_nodes](/code/tables/proxmox_nodes.md) | 8 | 1 | 2 |  |
| [proxmox_vms](/code/tables/proxmox_vms.md) | 16 | 3 | 2 |  |
| [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md) | 4 | 3 | 2 |  |
| [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md) | 4 | 5 | 2 |  |
| [rbac_roles](/code/tables/rbac_roles.md) | 9 | 5 | 3 | RBAC Layer 2 — per-module settings permissions (see docs/design/rbac.md). |
| [rbac_team_roles](/code/tables/rbac_team_roles.md) | 5 | 3 | 1 |  |
| [report_pack_shares](/code/tables/report_pack_shares.md) | 7 | 1 | 1 | Who else may open a pack. |
| [report_packs](/code/tables/report_packs.md) | 8 | 5 | 3 | Report Packs: a multi-page report designed in Reporting and exported as a PDF. |
| [rfp_categories](/code/tables/rfp_categories.md) | 7 | 15 | 1 |  |
| [rfp_conflicts](/code/tables/rfp_conflicts.md) | 10 | 4 | 2 |  |
| [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) | 10 | 19 | 7 |  |
| [rfp_consolidated_sources](/code/tables/rfp_consolidated_sources.md) | 4 | 6 | 4 |  |
| [rfp_departments](/code/tables/rfp_departments.md) | 6 | 10 | 2 |  |
| [rfp_document_sections](/code/tables/rfp_document_sections.md) | 10 | 6 | 3 |  |
| [rfp_documents](/code/tables/rfp_documents.md) | 10 | 16 | 4 |  |
| [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) | 10 | 12 | 3 |  |
| [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) | 6 | 11 | 5 |  |
| [rfp_output_sections](/code/tables/rfp_output_sections.md) | 10 | 10 | 4 |  |
| [rfp_processing_log](/code/tables/rfp_processing_log.md) | 10 | 1 | 1 |  |
| [rfp_scores](/code/tables/rfp_scores.md) | 8 | 2 | 3 |  |
| [rfp_section_history](/code/tables/rfp_section_history.md) | 6 | 2 | 4 |  |
| [rfps](/code/tables/rfps.md) | 11 | 14 | 30 | RFP Builder (feature of the Contracts module) |
| [rota_locations](/code/tables/rota_locations.md) | 7 | 9 | 3 |  |
| [search_documents](/code/tables/search_documents.md) | 11 | 3 | 2 | Search corpus — one row per searchable unit (ticket subject, email body, note, and later attachment text). |
| [servers](/code/tables/servers.md) | 13 | 2 | 1 |  |
| [service_impact_levels](/code/tables/service_impact_levels.md) | 9 | 17 | 2 |  |
| [service_incident_statuses](/code/tables/service_incident_statuses.md) | 8 | 15 | 2 |  |
| [sla_calendar_holidays](/code/tables/sla_calendar_holidays.md) | 4 | 3 | 1 |  |
| [sla_calendar_hours](/code/tables/sla_calendar_hours.md) | 5 | 4 | 2 |  |
| [sla_calendars](/code/tables/sla_calendars.md) | 7 | 8 | 3 |  |
| [sla_cron_runs](/code/tables/sla_cron_runs.md) | 11 | 2 | 1 |  |
| [sla_notification_rules](/code/tables/sla_notification_rules.md) | 11 | 3 | 2 |  |
| [sla_notifications_sent](/code/tables/sla_notifications_sent.md) | 6 | 2 | 1 |  |
| [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md) | 10 | 5 | 3 |  |
| [software_inventory_apps](/code/tables/software_inventory_apps.md) | 9 | 19 | 5 |  |
| [software_inventory_detail](/code/tables/software_inventory_detail.md) | 13 | 14 | 3 |  |
| [software_inventory_log](/code/tables/software_inventory_log.md) | 4 | 0 | 1 | Ingest log for software-inventory agent submissions — the submit endpoint always wrote here but the table was never defined (silent try/catch), so logging never worked. |
| [software_licences](/code/tables/software_licences.md) | 18 | 8 | 1 |  |
| [sso_group_teams](/code/tables/sso_group_teams.md) | 5 | 1 | 1 | SSO groups -> teams, and the groups seen at sign-in (3.5.0). |
| [sso_groups_seen](/code/tables/sso_groups_seen.md) | 6 | 1 | 1 |  |
| [status_incident_services](/code/tables/status_incident_services.md) | 5 | 10 | 2 |  |
| [status_incident_update_services](/code/tables/status_incident_update_services.md) | 4 | 3 | 1 |  |
| [status_incident_updates](/code/tables/status_incident_updates.md) | 7 | 6 | 2 | Incident update log (discussion #59, phase 2). |
| [status_incidents](/code/tables/status_incidents.md) | 9 | 12 | 3 |  |
| [status_planned](/code/tables/status_planned.md) | 12 | 1 | 2 | Planned maintenance (3.2.0) - not an incident until it starts; see includes/service_status_planned.php and database/freeitsm.sql. |
| [status_planned_services](/code/tables/status_planned_services.md) | 5 | 1 | 1 |  |
| [status_services](/code/tables/status_services.md) | 7 | 16 | 1 | Service Status module |
| [supplier_statuses](/code/tables/supplier_statuses.md) | 7 | 8 | 2 |  |
| [supplier_types](/code/tables/supplier_types.md) | 7 | 5 | 2 | Contracts module |
| [suppliers](/code/tables/suppliers.md) | 20 | 44 | 7 |  |
| [system_logs](/code/tables/system_logs.md) | 5 | 1 | 4 |  |
| [system_settings](/code/tables/system_settings.md) | 3 | 132 | 52 |  |
| [table_views](/code/tables/table_views.md) | 11 | 1 | 1 | Saved table views (#96). |
| [target_mailboxes](/code/tables/target_mailboxes.md) | 39 | 26 | 13 |  |
| [task_audit](/code/tables/task_audit.md) | 9 | 0 | 3 | Tasks had NO history at all until calendar sync could change one from outside FreeITSM (#75). |
| [task_checklist_items](/code/tables/task_checklist_items.md) | 14 | 1 | 1 |  |
| [task_checklists](/code/tables/task_checklists.md) | 9 | 1 | 1 | Checklists on TASKS (discussion #138, round three) - the same shape as the two ticket tables above, keyed on a task instead. |
| [task_collaborators](/code/tables/task_collaborators.md) | 8 | 4 | 1 | The other people on a task (GH #89) — shown as "Involved". |
| [task_comments](/code/tables/task_comments.md) | 6 | 2 | 1 |  |
| [task_dependencies](/code/tables/task_dependencies.md) | 7 | 3 | 2 | Task dependencies (3.3.0) - includes/projects/dependencies.php. |
| [task_priorities](/code/tables/task_priorities.md) | 7 | 13 | 3 |  |
| [task_recurrences](/code/tables/task_recurrences.md) | 25 | 2 | 2 |  |
| [task_statuses](/code/tables/task_statuses.md) | 8 | 33 | 4 |  |
| [task_tag_map](/code/tables/task_tag_map.md) | 2 | 4 | 2 |  |
| [task_tags](/code/tables/task_tags.md) | 5 | 7 | 3 |  |
| [task_time_entries](/code/tables/task_time_entries.md) | 9 | 5 | 1 | Time actually SPENT on a task, as many sessions as it took (GH #112). |
| [tasks](/code/tables/tasks.md) | 30 | 44 | 12 |  |
| [team_modules](/code/tables/team_modules.md) | 4 | 2 | 1 | Per-team module grants (issue #30) — the team twin of analyst_modules, mirroring team_tenant_access. |
| [team_tenant_access](/code/tables/team_tenant_access.md) | 4 | 3 | 1 | Which companies a TEAM grants its members (only consulted when the team is NOT flagged can_access_all_tenants). |
| [teams](/code/tables/teams.md) | 12 | 41 | 4 |  |
| [tenant_channel_senders](/code/tables/tenant_channel_senders.md) | 4 | 1 | 0 | Specific sender phone numbers mapped to a company (shared-intake channel routing). |
| [tenant_config_hidden](/code/tables/tenant_config_hidden.md) | 5 | 9 | 4 | Multi-tenancy: the per-company "hide" layer for global config (the add+hide override model — design §7). |
| [tenant_domains](/code/tables/tenant_domains.md) | 4 | 12 | 3 | Domains owned by a tenant (used by shared-intake email routing). |
| [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) | 4 | 8 | 3 | Specific sender addresses mapped to a company (shared-intake routing). |
| [tenant_settings](/code/tables/tenant_settings.md) | 5 | 2 | 1 | Per-company answers to settings that otherwise live install-wide in system_settings (discussion #72). |
| [tenants](/code/tables/tenants.md) | 8 | 49 | 4 | Multi-tenancy foundation — a single install can host multiple client companies (tenants). |
| [ticket_ai_summaries](/code/tables/ticket_ai_summaries.md) | 15 | 3 | 2 | The AI-written ticket summary, kept as a history — see freeitsm.sql for why a refresh writes a new version instead of overwriting the old one. |
| [ticket_assets](/code/tables/ticket_assets.md) | 6 | 5 | 3 |  |
| [ticket_audit](/code/tables/ticket_audit.md) | 8 | 5 | 20 |  |
| [ticket_categories](/code/tables/ticket_categories.md) | 11 | 6 | 2 | Ticket classification (#1540). |
| [ticket_checklist_items](/code/tables/ticket_checklist_items.md) | 14 | 2 | 4 |  |
| [ticket_checklists](/code/tables/ticket_checklists.md) | 8 | 4 | 3 |  |
| [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) | 5 | 6 | 4 |  |
| [ticket_csat_responses](/code/tables/ticket_csat_responses.md) | 11 | 4 | 2 |  |
| [ticket_dashboard_widgets](/code/tables/ticket_dashboard_widgets.md) | 15 | 5 | 3 |  |
| [ticket_domains](/code/tables/ticket_domains.md) | 5 | 0 | 0 |  |
| [ticket_email_template_rules](/code/tables/ticket_email_template_rules.md) | 5 | 1 | 2 |  |
| [ticket_email_templates](/code/tables/ticket_email_templates.md) | 9 | 4 | 2 |  |
| [ticket_links](/code/tables/ticket_links.md) | 6 | 2 | 3 | Ticket-to-ticket links (self-referential, typed): related / duplicate / parent. |
| [ticket_merges](/code/tables/ticket_merges.md) | 15 | 3 | 1 |  |
| [ticket_notes](/code/tables/ticket_notes.md) | 7 | 16 | 10 |  |
| [ticket_number_counters](/code/tables/ticket_number_counters.md) | 3 | 0 | 1 | Ticket numbering (GH #71) ⚠️ ticket_number_counters has NO auto-increment id: counter_key is the primary key, which is what lets the read-and-increment be one statement. |
| [ticket_number_history](/code/tables/ticket_number_history.md) | 5 | 1 | 1 | ⚠️ The PK is declared in db_verify.php's `$primaryKeys` map, NOT here — this array is columns only, and a 'PRIMARY KEY' entry would be built as a column literally called that. |
| [ticket_origins](/code/tables/ticket_origins.md) | 8 | 13 | 3 |  |
| [ticket_prefixes](/code/tables/ticket_prefixes.md) | 6 | 0 | 0 |  |
| [ticket_presence](/code/tables/ticket_presence.md) | 5 | 1 | 1 | Collision detection (#934): who is looking at which ticket right now. |
| [ticket_priorities](/code/tables/ticket_priorities.md) | 10 | 38 | 4 |  |
| [ticket_recordings](/code/tables/ticket_recordings.md) | 12 | 6 | 2 |  |
| [ticket_reply_templates](/code/tables/ticket_reply_templates.md) | 9 | 1 | 2 |  |
| [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) | 8 | 6 | 3 | How a ticket ENDED, not what it was about. |
| [ticket_rota_entries](/code/tables/ticket_rota_entries.md) | 8 | 7 | 7 |  |
| [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) | 7 | 7 | 2 |  |
| [ticket_splits](/code/tables/ticket_splits.md) | 12 | 1 | 1 |  |
| [ticket_statuses](/code/tables/ticket_statuses.md) | 9 | 60 | 3 |  |
| [ticket_time_entries](/code/tables/ticket_time_entries.md) | 9 | 6 | 4 |  |
| [ticket_types](/code/tables/ticket_types.md) | 8 | 13 | 3 |  |
| [ticket_views](/code/tables/ticket_views.md) | 8 | 1 | 1 | Who has looked at a ticket (discussion #62, step 2). |
| [tickets](/code/tables/tickets.md) | 34 | 135 | 30 |  |
| [trusted_devices](/code/tables/trusted_devices.md) | 7 | 0 | 2 |  |
| [user_password_reset_tokens](/code/tables/user_password_reset_tokens.md) | 6 | 1 | 2 | Password reset for SELF-SERVICE PORTAL users (GH #134). |
| [user_preferences](/code/tables/user_preferences.md) | 5 | 27 | 7 |  |
| [user_sso_identities](/code/tables/user_sso_identities.md) | 9 | 9 | 5 |  |
| [user_verification_tokens](/code/tables/user_verification_tokens.md) | 7 | 1 | 2 |  |
| [users](/code/tables/users.md) | 29 | 101 | 21 |  |
| [users_assets](/code/tables/users_assets.md) | 9 | 12 | 1 | Who is holding an asset. |
| [vcloud_connections](/code/tables/vcloud_connections.md) | 14 | 4 | 2 | VMware Cloud Director servers (several per install). |
| [vcloud_edge_gateways](/code/tables/vcloud_edge_gateways.md) | 9 | 1 | 2 |  |
| [vcloud_vms](/code/tables/vcloud_vms.md) | 14 | 3 | 2 |  |
| [warroom_attachments](/code/tables/warroom_attachments.md) | 6 | 2 | 1 | ⚠️ THERE IS NO content_type COLUMN, AND THAT IS THE POINT. |
| [warroom_channel_members](/code/tables/warroom_channel_members.md) | 4 | 1 | 1 | Membership, for private custom channels and for DMs. |
| [warroom_channels](/code/tables/warroom_channels.md) | 10 | 3 | 2 | War room — the fallback chat analysts use when Teams/Slack is unavailable. |
| [warroom_mentions](/code/tables/warroom_mentions.md) | 4 | 1 | 1 | Who was named in a message. |
| [warroom_messages](/code/tables/warroom_messages.md) | 10 | 4 | 3 | analyst_id is NULLABLE on purpose (FK is ON DELETE SET NULL, see db_verify.php): deleting an analyst must not erase the conversation that happened during an incident. |
| [warroom_presence](/code/tables/warroom_presence.md) | 4 | 1 | 1 | Who is currently in the war room. |
| [warroom_reads](/code/tables/warroom_reads.md) | 5 | 1 | 1 | How far each analyst has read in each channel, so the list can show what is new. |
| [watchtower_item_members](/code/tables/watchtower_item_members.md) | 6 | 1 | 1 | Which statuses/priorities feed one Watchtower item. |
| [watchtower_items](/code/tables/watchtower_items.md) | 5 | 1 | 1 | Watchtower settings. |
| [webchat_conversations](/code/tables/webchat_conversations.md) | 9 | 1 | 2 | One website chat conversation. |
| [webchat_messages](/code/tables/webchat_messages.md) | 6 | 1 | 1 | Pre-ticket chat transcript (AI 'deflect' mode) — see freeitsm.sql. |
| [webchat_widgets](/code/tables/webchat_widgets.md) | 16 | 4 | 1 | Embed config for one website chat widget. |
| [webhook_deliveries](/code/tables/webhook_deliveries.md) | 19 | 6 | 3 |  |
| [webhook_message_formats](/code/tables/webhook_message_formats.md) | 11 | 4 | 3 |  |
| [wiki_classes](/code/tables/wiki_classes.md) | 7 | 1 | 0 |  |
| [wiki_db_references](/code/tables/wiki_db_references.md) | 5 | 5 | 0 |  |
| [wiki_dependencies](/code/tables/wiki_dependencies.md) | 6 | 2 | 0 |  |
| [wiki_files](/code/tables/wiki_files.md) | 11 | 8 | 0 |  |
| [wiki_function_calls](/code/tables/wiki_function_calls.md) | 4 | 1 | 0 |  |
| [wiki_functions](/code/tables/wiki_functions.md) | 10 | 4 | 0 |  |
| [wiki_scan_runs](/code/tables/wiki_scan_runs.md) | 9 | 7 | 0 |  |
| [wiki_session_vars](/code/tables/wiki_session_vars.md) | 5 | 1 | 0 |  |
| [workflow_executions](/code/tables/workflow_executions.md) | 11 | 5 | 3 |  |
| [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) | 5 | 0 | 6 |  |
| [workflows](/code/tables/workflows.md) | 14 | 12 | 6 | Workflows module — automation engine with cross-module triggers. |
