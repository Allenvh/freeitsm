# knowledge_user_group_members

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `group_id` | `INT NOT NULL` |  |
| `member_type` | `VARCHAR(10) NOT NULL` | A member is an analyst OR a portal user, never both: 'analyst' \| 'user'. |
| `member_id` | `INT NOT NULL` |  |
| `expires_at` | `DATETIME NULL` | ⚠️ The whole reason this table exists. "For the week" is the requirement as stated, and an access list that quietly stays open after the engineers go home is the failure worth designing out on day one. NULL = no expiry. |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md):402 | `handleAddMember` | insert | `group_id`, `member_type`, `member_id`, `expires_at`, `created_datetime` |  |
| [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md):427 | `handleRemoveMember` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/forms/audience.php](/code/files/api/forms/audience.php.md) | L62 |  |
| [api/lms/assignment_targets.php](/code/files/api/lms/assignment_targets.php.md) | L95 |  |
| [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) | L275 |  |
| [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md) | handleList L99, handleGet L147, handleGet L159, handleGet L173 |  |
| [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) | knowledgeViewerPrincipals L746 |  |
| [includes/lms_access.php](/code/files/includes/lms_access.php.md) | lmsAssignmentReachSql L253, lmsAssignedLearnersSql L524 |  |
| [includes/managers.php](/code/files/includes/managers.php.md) | managerGrantMembers L198 |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::portalAudienceSql L2219 |  |

