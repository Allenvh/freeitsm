# includes/services/project_tools.php

> PHP - 2081 lines

ProjectToolsService - the project-management tools on a project (3.2.0 phase 2): its people (members and their roles), its scope (deliverables and requirements, prioritised with MoSCoW) and the RACI matrix that joins the two.

Kept apart from ProjectsService so neither grows into a thousand-line file;
every method still starts from ProjectsService::loadForActor() (company scope
as not-found) and ProjectsService::assertCanChange() (Projects -> Settings ->
General), so the rules are the same ones.

🔑 RACI rule: at most ONE "A" (accountable) per deliverable. Setting A on a
cell demotes any other A in that row to R rather than refusing - the person
clicking has just said who is accountable, and the previous one is still doing
the work. A row with no A or no R is allowed (a draft) and the screen flags it.

## Classes

- [ProjectToolsService](/code/classes/ProjectToolsService.md) (class, line 20)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `ProjectToolsService::addMember` | read [analysts](/code/tables/analysts.md) |  |
| 45 | `ProjectToolsService::addMember` | read [teams](/code/tables/teams.md) |  |
| 49 | `ProjectToolsService::addMember` | read [users](/code/tables/users.md) |  |
| 61 | `ProjectToolsService::addMember` | read [project_members](/code/tables/project_members.md) |  |
| 65 | `ProjectToolsService::addMember` | read [project_members](/code/tables/project_members.md) |  |
| 66 | `ProjectToolsService::addMember` | **insert** [project_members](/code/tables/project_members.md) (`project_id`, `$col`, `role_id`, `notes`, `position`, `created_by_analyst_id`, `created_datetime`) |  |
| 84 | `ProjectToolsService::addContractorMember` | read [project_members](/code/tables/project_members.md) |  |
| 92 | `ProjectToolsService::addContractorMember` | read [project_members](/code/tables/project_members.md) |  |
| 96 | `ProjectToolsService::addContractorMember` | read [project_members](/code/tables/project_members.md) |  |
| 97 | `ProjectToolsService::addContractorMember` | **insert** [project_members](/code/tables/project_members.md) (`project_id`, `supplier_id`, `contact_id`, `role_id`, `notes`, `position`, `created_by_analyst_id`, `created_datetime`) |  |
| 110 | `ProjectToolsService::contractorMemberSql` | read [project_members](/code/tables/project_members.md) |  |
| 110 | `ProjectToolsService::contractorMemberSql` | read [suppliers](/code/tables/suppliers.md) |  |
| 117 | `ProjectToolsService::contractorMemberSql` | read [suppliers](/code/tables/suppliers.md), read [contacts](/code/tables/contacts.md) |  |
| 154 | `ProjectToolsService::updateMember` | **update** [project_members](/code/tables/project_members.md) |  |
| 168 | `ProjectToolsService::stakeReady` | read [project_members](/code/tables/project_members.md) |  |
| 193 | `ProjectToolsService::removeMember` | **delete** [project_raci](/code/tables/project_raci.md) |  |
| 194 | `ProjectToolsService::removeMember` | **delete** [project_members](/code/tables/project_members.md) |  |
| 227 | `ProjectToolsService::saveItem` | **update** [project_items](/code/tables/project_items.md) (`title`, `description`, `acceptance_criteria`, `moscow`, `stage_id`, `status`, `updated_datetime`) |  |
| 234 | `ProjectToolsService::saveItem` | read [project_items](/code/tables/project_items.md) |  |
| 235 | `ProjectToolsService::saveItem` | **insert** [project_items](/code/tables/project_items.md) (`project_id`, `title`, `description`, `acceptance_criteria`, `moscow`, `stage_id`, `status`, `position`, `created_datetime`, `updated_datetime`) |  |
| 253 | `ProjectToolsService::deleteItem` | **delete** [project_raci](/code/tables/project_raci.md) |  |
| 254 | `ProjectToolsService::deleteItem` | **update** [project_items](/code/tables/project_items.md) (`parent_id`) |  |
| 255 | `ProjectToolsService::deleteItem` | **delete** [project_items](/code/tables/project_items.md) |  |
| 271 | `ProjectToolsService::moveItem` | **update** [project_items](/code/tables/project_items.md) (`moscow`, `updated_datetime`) |  |
| 272 | `ProjectToolsService::moveItem` | **update** [project_items](/code/tables/project_items.md) (`position`) |  |
| 300 | `ProjectToolsService::setRaci` | **delete** [project_raci](/code/tables/project_raci.md) |  |
| 304 | `ProjectToolsService::setRaci` | **update** [project_raci](/code/tables/project_raci.md) (`letter`) |  |
| 306 | `ProjectToolsService::setRaci` | **insert** [project_raci](/code/tables/project_raci.md) (`project_id`, `item_id`, `member_id`, `letter`) |  |
| 310 | `ProjectToolsService::setRaci` | read [project_raci](/code/tables/project_raci.md) |  |
| 349 | `ProjectToolsService::saveRaid` | read [project_raid](/code/tables/project_raid.md) |  |
| 367 | `ProjectToolsService::saveRaid` | read [analysts](/code/tables/analysts.md) |  |
| 396 | `ProjectToolsService::saveRaid` | **update** [project_raid](/code/tables/project_raid.md) (`type`, `title`, `description`, `probability`, `impact`, `response`, `response_plan`, `owner_analyst_id`, `status`, `due_date`, `ticket_id`, `updated_datetime`) |  |
| 407 | `ProjectToolsService::saveRaid` | **insert** [project_raid](/code/tables/project_raid.md) (`type`, `title`, `description`, `probability`, `impact`, `response`, `response_plan`, `owner_analyst_id`, `status`, `due_date`, `ticket_id`, `project_id`) |  |
| 412 | `ProjectToolsService::saveRaid` | **update** [project_raid](/code/tables/project_raid.md) (`decided_by`, `decided_date`, `rationale`) |  |
| 428 | `ProjectToolsService::deleteRaid` | read [project_raid](/code/tables/project_raid.md) |  |
| 433 | `ProjectToolsService::deleteRaid` | **delete** [project_raid_tasks](/code/tables/project_raid_tasks.md) |  |
| 434 | `ProjectToolsService::deleteRaid` | **delete** [project_raid](/code/tables/project_raid.md) |  |
| 444 | `ProjectToolsService::raidLogReady` | read [project_raid](/code/tables/project_raid.md) |  |
| 469 | `ProjectToolsService::escalateRaid` | **update** [project_raid](/code/tables/project_raid.md) (`escalated_datetime`, `escalated_by_id`, `escalation_note`, `updated_datetime`) |  |
| 488 | `ProjectToolsService::deescalateRaid` | **update** [project_raid](/code/tables/project_raid.md) (`escalated_datetime`, `escalated_by_id`, `escalation_note`, `updated_datetime`) |  |
| 515 | `ProjectToolsService::addRaidAction` | **insert** [project_raid_tasks](/code/tables/project_raid_tasks.md) (`raid_id`, `task_id`, `created_datetime`) |  |
| 530 | `ProjectToolsService::removeRaidAction` | **delete** [project_raid_tasks](/code/tables/project_raid_tasks.md) |  |
| 538 | `ProjectToolsService::raidActionsReady` | read [project_raid_tasks](/code/tables/project_raid_tasks.md) |  |
| 586 | `ProjectToolsService::lessonToKnowledge` | **update** [project_raid](/code/tables/project_raid.md) (`knowledge_article_id`, `updated_datetime`) |  |
| 616 | `ProjectToolsService::issueToTicket` | read [analysts](/code/tables/analysts.md) |  |
| 641 | `ProjectToolsService::issueToTicket` | **update** [project_raid](/code/tables/project_raid.md) (`ticket_id`, `updated_datetime`) |  |
| 643 | `ProjectToolsService::issueToTicket` | read [tickets](/code/tables/tickets.md) |  |
| 658 | `ProjectToolsService::raidRow` | read [project_raid](/code/tables/project_raid.md) |  |
| 706 | `ProjectToolsService::saveTolerances` | **delete** [project_tolerances](/code/tables/project_tolerances.md) |  |
| 712 | `ProjectToolsService::saveTolerances` | read [project_tolerances](/code/tables/project_tolerances.md) |  |
| 715 | `ProjectToolsService::saveTolerances` | **update** [project_tolerances](/code/tables/project_tolerances.md) (`value`) |  |
| 717 | `ProjectToolsService::saveTolerances` | **insert** [project_tolerances](/code/tables/project_tolerances.md) (`project_id`, `stage_id`, `dimension`, `value`) |  |
| 730 | `ProjectToolsService::tolerances` | read [project_tolerances](/code/tables/project_tolerances.md) |  |
| 752 | `ProjectToolsService::decideGate` | read [project_stages](/code/tables/project_stages.md) |  |
| 775 | `ProjectToolsService::decideGate` | **update** [project_stages](/code/tables/project_stages.md) (`gate_decision`, `gate_notes`, `gate_decided_by`, `gate_decided_datetime`, `updated_datetime`) |  |
| 782 | `ProjectToolsService::decideGate` | **update** [project_stages](/code/tables/project_stages.md) (`status`) |  |
| 784 | `ProjectToolsService::decideGate` | read [project_stages](/code/tables/project_stages.md) |  |
| 787 | `ProjectToolsService::decideGate` | **update** [project_stages](/code/tables/project_stages.md) (`status`, `updated_datetime`) |  |
| 822 | `ProjectToolsService::raid` | read [project_raid](/code/tables/project_raid.md), read [analysts](/code/tables/analysts.md) |  |
| 825 | `ProjectToolsService::raid` | read [analysts](/code/tables/analysts.md) |  |
| 825 | `ProjectToolsService::raid` | read [tickets](/code/tables/tickets.md) |  |
| 838 | `ProjectToolsService::raid` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 844 | `ProjectToolsService::raid` | read [project_raid_tasks](/code/tables/project_raid_tasks.md), read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md) |  |
| 895 | `ProjectToolsService::saveTarget` | **update** [project_asset_targets](/code/tables/project_asset_targets.md) (`name`, `scope`, `scope_type_id`, `scope_field`, `scope_value`, `done_field`, `done_op`, `done_value`, `target_date`, `updated_datetime`) |  |
| 899 | `ProjectToolsService::saveTarget` | **delete** [project_asset_target_snapshots](/code/tables/project_asset_target_snapshots.md) |  |
| 901 | `ProjectToolsService::saveTarget` | read [project_asset_targets](/code/tables/project_asset_targets.md) |  |
| 903 | `ProjectToolsService::saveTarget` | **insert** [project_asset_targets](/code/tables/project_asset_targets.md) (`project_id`, `name`, `scope`, `scope_type_id`, `scope_field`, `scope_value`, `done_field`, `done_op`, `done_value`, `target_date`, `position`, `created_by_analyst_id`) |  |
| 923 | `ProjectToolsService::deleteTarget` | **delete** [project_asset_targets](/code/tables/project_asset_targets.md) |  |
| 944 | `ProjectToolsService::target` | read [project_asset_targets](/code/tables/project_asset_targets.md) |  |
| 1009 | `ProjectToolsService::saveMilestone` | **update** [project_milestones](/code/tables/project_milestones.md) (`name`, `due_date`, `stage_id`, `notes`, `done_date`, `done_by_analyst_id`, `updated_datetime`) |  |
| 1015 | `ProjectToolsService::saveMilestone` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 1017 | `ProjectToolsService::saveMilestone` | **insert** [project_milestones](/code/tables/project_milestones.md) (`project_id`, `stage_id`, `name`, `due_date`, `done_date`, `done_by_analyst_id`, `notes`, `position`, `created_by_analyst_id`, `created_datetime`, `updated_datetime`) |  |
| 1042 | `ProjectToolsService::deleteMilestone` | **delete** [project_milestones](/code/tables/project_milestones.md) |  |
| 1057 | `ProjectToolsService::milestone` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 1078 | `ProjectToolsService::setTaskDates` | read [tasks](/code/tables/tasks.md) |  |
| 1102 | `ProjectToolsService::setTaskEstimate` | read [tasks](/code/tables/tasks.md) |  |
| 1124 | `ProjectToolsService::members` | read [project_members](/code/tables/project_members.md), read [project_roles](/code/tables/project_roles.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md), read [users](/code/tables/users.md) |  |
| 1145 | `ProjectToolsService::items` | read [project_items](/code/tables/project_items.md), read [project_stages](/code/tables/project_stages.md) |  |
| 1159 | `ProjectToolsService::raci` | read [project_raci](/code/tables/project_raci.md) |  |
| 1183 | `ProjectToolsService::addTaskDependency` | read [tasks](/code/tables/tasks.md) |  |
| 1192 | `ProjectToolsService::addTaskDependency` | **insert** [task_dependencies](/code/tables/task_dependencies.md) (`task_id`, `depends_on_id`, `lag_days`, `created_by_id`, `created_datetime`) |  |
| 1195 | `ProjectToolsService::addTaskDependency` | read [task_dependencies](/code/tables/task_dependencies.md) |  |
| 1213 | `ProjectToolsService::removeTaskDependency` | **delete** [task_dependencies](/code/tables/task_dependencies.md) |  |
| 1243 | `ProjectToolsService::saveGateItem` | read [analysts](/code/tables/analysts.md) |  |
| 1252 | `ProjectToolsService::saveGateItem` | **update** [project_gate_items](/code/tables/project_gate_items.md) (`title`, `analyst_id`, `change_id`, `notes`) |  |
| 1256 | `ProjectToolsService::saveGateItem` | read [project_gate_items](/code/tables/project_gate_items.md) |  |
| 1257 | `ProjectToolsService::saveGateItem` | **insert** [project_gate_items](/code/tables/project_gate_items.md) (`project_id`, `stage_id`, `kind`, `title`, `analyst_id`, `change_id`, `notes`, `position`, `created_by_id`, `created_datetime`) |  |
| 1277 | `ProjectToolsService::deleteGateItem` | **delete** [project_gate_items](/code/tables/project_gate_items.md) |  |
| 1303 | `ProjectToolsService::tickGateItem` | **update** [project_gate_items](/code/tables/project_gate_items.md) (`done_by_id`, `done_datetime`) |  |
| 1312 | `ProjectToolsService::tickGateItem` | **update** [project_gate_items](/code/tables/project_gate_items.md) (`document_id`, `done_by_id`, `done_datetime`) |  |
| 1318 | `ProjectToolsService::tickGateItem` | **update** [project_gate_items](/code/tables/project_gate_items.md) (`done_by_id`, `done_datetime`) |  |
| 1338 | `ProjectToolsService::setGateKind` | **update** [project_stages](/code/tables/project_stages.md) (`gate_kind`, `updated_datetime`) |  |
| 1341 | `ProjectToolsService::setGateKind` | read [project_gate_items](/code/tables/project_gate_items.md) |  |
| 1365 | `ProjectToolsService::gateItem` | read [project_gate_items](/code/tables/project_gate_items.md) |  |
| 1405 | `ProjectToolsService::saveBenefit` | read [analysts](/code/tables/analysts.md) |  |
| 1414 | `ProjectToolsService::saveBenefit` | **update** [project_benefits](/code/tables/project_benefits.md) (`title`, `measure`, `unit`, `direction`, `baseline_value`, `target_value`, `target_date`, `owner_analyst_id`, `review_date`, `review_months`, `status`, `notes`) |  |
| 1421 | `ProjectToolsService::saveBenefit` | read [project_benefits](/code/tables/project_benefits.md) |  |
| 1422 | `ProjectToolsService::saveBenefit` | **insert** [project_benefits](/code/tables/project_benefits.md) (`title`, `measure`, `unit`, `direction`, `baseline_value`, `target_value`, `target_date`, `owner_analyst_id`, `review_date`, `review_months`, `status`, `notes`) |  |
| 1442 | `ProjectToolsService::deleteBenefit` | **delete** [project_benefit_measures](/code/tables/project_benefit_measures.md) |  |
| 1443 | `ProjectToolsService::deleteBenefit` | **delete** [project_benefits](/code/tables/project_benefits.md) |  |
| 1465 | `ProjectToolsService::addBenefitMeasure` | **insert** [project_benefit_measures](/code/tables/project_benefit_measures.md) (`benefit_id`, `value`, `measured_date`, `note`, `recorded_by_id`, `created_datetime`) |  |
| 1471 | `ProjectToolsService::addBenefitMeasure` | **update** [project_benefits](/code/tables/project_benefits.md) (`review_date`, `updated_datetime`) |  |
| 1488 | `ProjectToolsService::deleteBenefitMeasure` | **delete** [project_benefit_measures](/code/tables/project_benefit_measures.md) |  |
| 1502 | `ProjectToolsService::benefit` | read [project_benefits](/code/tables/project_benefits.md) |  |
| 1530 | `ProjectToolsService::takeBaseline` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 1571 | `ProjectToolsService::saveChangeRequest` | **update** [project_change_requests](/code/tables/project_change_requests.md) (`title`, `description`, `reason`, `impact_days`, `impact_cost`, `impact_scope`, `updated_datetime`) |  |
| 1577 | `ProjectToolsService::saveChangeRequest` | read [project_change_requests](/code/tables/project_change_requests.md) |  |
| 1578 | `ProjectToolsService::saveChangeRequest` | **insert** [project_change_requests](/code/tables/project_change_requests.md) (`project_id`, `number`, `title`, `description`, `reason`, `impact_days`, `impact_cost`, `impact_scope`, `status`, `raised_by_id`, `raised_datetime`, `updated_datetime`) |  |
| 1616 | `ProjectToolsService::decideChangeRequest` | **update** [project_change_requests](/code/tables/project_change_requests.md) (`status`, `decided_by_id`, `decided_datetime`, `decision_notes`, `updated_datetime`) |  |
| 1626 | `ProjectToolsService::decideChangeRequest` | **update** [projects](/code/tables/projects.md) (`target_end_date`) |  |
| 1635 | `ProjectToolsService::decideChangeRequest` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1636 | `ProjectToolsService::decideChangeRequest` | **insert** [project_budget_lines](/code/tables/project_budget_lines.md) (`project_id`, `title`, `category`, `planned_amount`, `notes`, `position`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 1645 | `ProjectToolsService::decideChangeRequest` | **update** [project_change_requests](/code/tables/project_change_requests.md) (`baseline_id`, `applied`) |  |
| 1672 | `ProjectToolsService::withdrawChangeRequest` | **update** [project_change_requests](/code/tables/project_change_requests.md) (`status`, `updated_datetime`) |  |
| 1685 | `ProjectToolsService::changeRequest` | read [project_change_requests](/code/tables/project_change_requests.md) |  |
| 1714 | `ProjectToolsService::member` | read [project_members](/code/tables/project_members.md) |  |
| 1728 | `ProjectToolsService::item` | read [project_items](/code/tables/project_items.md) |  |
| 1756 | `ProjectToolsService::stageOf` | read [project_stages](/code/tables/project_stages.md) |  |
| 1770 | `ProjectToolsService::roleId` | read [project_roles](/code/tables/project_roles.md) |  |
| 1784 | `ProjectToolsService::roleName` | read [project_roles](/code/tables/project_roles.md) |  |
| 1797 | `ProjectToolsService::memberName` | read [project_members](/code/tables/project_members.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md), read [users](/code/tables/users.md) |  |
| 1880 | `ProjectToolsService::saveBudgetLine` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1886 | `ProjectToolsService::saveBudgetLine` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1889 | `ProjectToolsService::saveBudgetLine` | **update** [project_budget_lines](/code/tables/project_budget_lines.md) (`title`, `category`, `planned_amount`, `actual_amount`, `contract_id`, `cost_centre_id`, `notes`, `updated_datetime`) |  |
| 1894 | `ProjectToolsService::saveBudgetLine` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1895 | `ProjectToolsService::saveBudgetLine` | **insert** [project_budget_lines](/code/tables/project_budget_lines.md) (`project_id`, `title`, `category`, `planned_amount`, `actual_amount`, `contract_id`, `cost_centre_id`, `notes`, `position`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 1918 | `ProjectToolsService::budgetLineWhen` | **update** [project_budget_lines](/code/tables/project_budget_lines.md) (`planned_date`, `spent_date`, `forecast_amount`) |  |
| 1932 | `ProjectToolsService::deleteBudgetLine` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1936 | `ProjectToolsService::deleteBudgetLine` | **delete** [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1960 | `ProjectToolsService::addProjectRate` | **insert** [project_labour_rates](/code/tables/project_labour_rates.md) (`scope`, `ref_id`, `hourly_rate`, `effective_from`, `created_by_id`, `created_datetime`) |  |
| 1976 | `ProjectToolsService::deleteProjectRate` | **delete** [project_labour_rates](/code/tables/project_labour_rates.md) |  |
| 1998 | `ProjectToolsService::setCurrency` | **update** [projects](/code/tables/projects.md) (`currency`, `updated_datetime`) |  |
| 2024 | `ProjectToolsService::announce` | read [projects](/code/tables/projects.md) |  |
| 2076 | `ProjectToolsService::announcements` | read [status_services](/code/tables/status_services.md) |  |
| 2077 | `ProjectToolsService::announcements` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## How it hangs together

**Includes:** [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md), [includes/entity_links.php](/code/files/includes/entity_links.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md), [includes/projects/control.php](/code/files/includes/projects/control.php.md), [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md), [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md), [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md)

**Included by:** [api/projects/export.php](/code/files/api/projects/export.php.md), [api/projects/get.php](/code/files/api/projects/get.php.md), [api/projects/lookups.php](/code/files/api/projects/lookups.php.md), [api/projects/tools.php](/code/files/api/projects/tools.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [entityLink()](/code/functions/entityLink.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [projectBaselineAuto()](/code/functions/projectBaselineAuto.md), [projectBenefitNextReview()](/code/functions/projectBenefitNextReview.md), [projectBenefitsReady()](/code/functions/projectBenefitsReady.md), [projectBudgetContracts()](/code/functions/projectBudgetContracts.md), [projectBudgetCostCentres()](/code/functions/projectBudgetCostCentres.md), [projectBudgetReady()](/code/functions/projectBudgetReady.md), [projectCanDecideChange()](/code/functions/projectCanDecideChange.md), [projectCode()](/code/functions/projectCode.md), [projectControlReady()](/code/functions/projectControlReady.md), [projectCurrencyOf()](/code/functions/projectCurrencyOf.md), [projectDependencies()](/code/functions/projectDependencies.md), [projectDependenciesReady()](/code/functions/projectDependenciesReady.md), [projectDependencyMakesCycle()](/code/functions/projectDependencyMakesCycle.md), [projectGateChanges()](/code/functions/projectGateChanges.md), [projectGateDocuments()](/code/functions/projectGateDocuments.md), [projectGateItemsReady()](/code/functions/projectGateItemsReady.md), [projectGateOpenItems()](/code/functions/projectGateOpenItems.md), [projectGoLiveStarter()](/code/functions/projectGoLiveStarter.md), [projectLabourRatesReset()](/code/functions/projectLabourRatesReset.md), [projectLinkAdd()](/code/functions/projectLinkAdd.md), [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectMoney()](/code/functions/projectMoney.md), [projectScaleLabels()](/code/functions/projectScaleLabels.md), [projectSetting()](/code/functions/projectSetting.md), [projectStampCurrency()](/code/functions/projectStampCurrency.md), [projectTakeBaseline()](/code/functions/projectTakeBaseline.md), [projectTargetNormalise()](/code/functions/projectTargetNormalise.md), [projectTargetOptions()](/code/functions/projectTargetOptions.md), [projectTargetsReady()](/code/functions/projectTargetsReady.md), [projectValidCurrency()](/code/functions/projectValidCurrency.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md), [statusPlannedCancel()](/code/functions/statusPlannedCancel.md), [statusPlannedDue()](/code/functions/statusPlannedDue.md), [statusPlannedList()](/code/functions/statusPlannedList.md), [statusPlannedLoad()](/code/functions/statusPlannedLoad.md), [statusPlannedReady()](/code/functions/statusPlannedReady.md), [statusPlannedSave()](/code/functions/statusPlannedSave.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md), [tasksContractorValidate()](/code/functions/tasksContractorValidate.md)

