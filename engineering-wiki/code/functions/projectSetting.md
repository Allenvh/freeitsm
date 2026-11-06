# projectSetting()

One Projects setting as a string; '' when it is not set.

```php
function projectSetting(PDO $conn, string $key): string
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 180.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the setting key, e.g. 'project_currency' (see `projectSettingDefinitions()`) |

**Returns:** `string`

## Calls

[projectSettings()](/code/functions/projectSettings.md)

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)
- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatThread()](/code/functions/projectChatThread.md)
- [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) - in [projectBenefitNotifyIds()](/code/functions/projectBenefitNotifyIds.md)
- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectInstallCurrency()](/code/functions/projectInstallCurrency.md), [projectLabour()](/code/functions/projectLabour.md), [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectLabourToCome()](/code/functions/projectLabourToCome.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)
- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectCanDecideChange()](/code/functions/projectCanDecideChange.md), [projectBaselineAuto()](/code/functions/projectBaselineAuto.md), [projectControlDetail()](/code/functions/projectControlDetail.md)
- [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) - in [projectProposalNeedsApproval()](/code/functions/projectProposalNeedsApproval.md), [projectProposalNamedApprover()](/code/functions/projectProposalNamedApprover.md), [projectProposalDetail()](/code/functions/projectProposalDetail.md)
- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md), [projectAlertsStalled()](/code/functions/projectAlertsStalled.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectHealthConfig()](/code/functions/projectHealthConfig.md)
- [includes/projects/settings.php](/code/files/includes/projects/settings.php.md) - in [projectScaleLabels()](/code/functions/projectScaleLabels.md), [projectCanCreate()](/code/functions/projectCanCreate.md), [projectCanChange()](/code/functions/projectCanChange.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateList()](/code/functions/projectTemplateList.md), [projectTemplateLoad()](/code/functions/projectTemplateLoad.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`, `ProjectTemplatesService::setBuiltinHidden`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::decideGate`, `ProjectToolsService::saveBenefit`, `ProjectToolsService::decideChangeRequest`, `ProjectToolsService::addProjectRate`, `ProjectToolsService::setCurrency`, `ProjectToolsService::announce`, `ProjectToolsService::announcements`
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::createProject`, `ProjectsService::decideProposal`
- [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) - in [tasksContractorEmailOn()](/code/functions/tasksContractorEmailOn.md)

