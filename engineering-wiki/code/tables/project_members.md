# project_members

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `team_id` | `INT NULL` |  |
| `user_id` | `INT NULL` |  |
| `role_id` | `INT NULL` |  |
| `supplier_id` | `INT NULL` | A contractor on the team (3.3.0): a supplier, and optionally one of its contacts. |
| `contact_id` | `INT NULL` |  |
| `notes` | `VARCHAR(255) NULL` |  |
| `power` | `TINYINT NULL` | Stakeholder map (3.3.0). |
| `interest` | `TINYINT NULL` |  |
| `stance` | `VARCHAR(10) NULL` |  |
| `keep_informed` | `VARCHAR(255) NULL` |  |
| `position` | `INT NOT NULL DEFAULT 0` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):66 | `ProjectToolsService::addMember` | insert | `project_id`, `$col`, `role_id`, `notes`, `position`, `created_by_analyst_id`, `created_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):97 | `ProjectToolsService::addContractorMember` | insert | `project_id`, `supplier_id`, `contact_id`, `role_id`, `notes`, `position`, `created_by_analyst_id`, `created_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):154 | `ProjectToolsService::updateMember` | update |  |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):194 | `ProjectToolsService::removeMember` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/projects/settings.php](/code/files/api/projects/settings.php.md) | projectRolesForSettings L31 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleProjects L492, peopleProjects L513 |  |
| [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) | projectAiFacts L286 |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatMaturity L128 |  |
| [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) | projectCapacity L155 |  |
| [includes/projects/settings.php](/code/files/includes/projects/settings.php.md) | projectIsTeam L451 |  |
| [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md) | projectVisibleSql L80 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::addMember L61, ProjectToolsService::addMember L65, ProjectToolsService::addContractorMember L84, ProjectToolsService::addContractorMember L92, ProjectToolsService::addContractorMember L96, ProjectToolsService::contractorMemberSql L110 ... |  |

