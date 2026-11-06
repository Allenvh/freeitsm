# api/projects/get.php

> API endpoint - 74 lines

GET ?id= - one project for its page: the project, its time boxes, its tasks and its history. Out of scope reads as not found.

## The endpoint

| | |
|---|---|
| URL | `api/projects/get.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |
| Reads | `GET id` |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md), [includes/projects/flow.php](/code/files/includes/projects/flow.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md), [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), [includes/projects/control.php](/code/files/includes/projects/control.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectBenefits()](/code/functions/projectBenefits.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md), [projectBudgetReady()](/code/functions/projectBudgetReady.md), [projectCanChange()](/code/functions/projectCanChange.md), [projectCanDelete()](/code/functions/projectCanDelete.md), [projectControlDetail()](/code/functions/projectControlDetail.md), [projectDependencies()](/code/functions/projectDependencies.md), [projectDependencyAnalysis()](/code/functions/projectDependencyAnalysis.md), [projectDetail()](/code/functions/projectDetail.md), [projectFlow()](/code/functions/projectFlow.md), [projectFlowSnapshot()](/code/functions/projectFlowSnapshot.md), [projectGateChanges()](/code/functions/projectGateChanges.md), [projectGateDocuments()](/code/functions/projectGateDocuments.md), [projectGateItems()](/code/functions/projectGateItems.md), [projectMilestones()](/code/functions/projectMilestones.md), [projectProposalDetail()](/code/functions/projectProposalDetail.md), [projectSetting()](/code/functions/projectSetting.md), [projectTargetsDetail()](/code/functions/projectTargetsDetail.md), [projectUnapprovedChanges()](/code/functions/projectUnapprovedChanges.md)

