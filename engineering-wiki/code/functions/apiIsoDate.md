# apiIsoDate()

DB "YYYY-MM-DD HH:MM:SS" (stored UTC) -> ISO 8601 "YYYY-MM-DDTHH:MM:SSZ", or null.

```php
function apiIsoDate(?string $dbDatetime): ?string
```

Defined in [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), line 81.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dbDatetime` | `?string` |  | a UTC datetime column value; null or '' gives null |

**Returns:** `?string`

## Called from

- [api/v1/index.php](/code/files/api/v1/index.php.md) - in [apiHandlePing()](/code/functions/apiHandlePing.md)
- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiAuthenticate()](/code/functions/apiAuthenticate.md)
- [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md) - in [apiSerializeAnalyst()](/code/functions/apiSerializeAnalyst.md)
- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiSerializeAsset()](/code/functions/apiSerializeAsset.md), [apiAssetsGet()](/code/functions/apiAssetsGet.md), [apiAssetAssignmentsList()](/code/functions/apiAssetAssignmentsList.md), [apiAssetHistoryList()](/code/functions/apiAssetHistoryList.md), [apiAssetCustodyList()](/code/functions/apiAssetCustodyList.md), [apiAssetSoftwareList()](/code/functions/apiAssetSoftwareList.md)
- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiSerializeChange()](/code/functions/apiSerializeChange.md), [apiChangesGet()](/code/functions/apiChangesGet.md), [apiChangeCommentsList()](/code/functions/apiChangeCommentsList.md), [apiChangeAuditList()](/code/functions/apiChangeAuditList.md), [apiChangeCabGet()](/code/functions/apiChangeCabGet.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbSerializeObject()](/code/functions/apiCmdbSerializeObject.md), [apiCmdbObjectsGet()](/code/functions/apiCmdbObjectsGet.md), [apiCmdbObjectTicketsList()](/code/functions/apiCmdbObjectTicketsList.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiSerializeContract()](/code/functions/apiSerializeContract.md), [apiSerializeSupplier()](/code/functions/apiSerializeSupplier.md), [apiContractTermsGet()](/code/functions/apiContractTermsGet.md)
- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiSerializeCostCentre()](/code/functions/apiSerializeCostCentre.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiSerializeDomain()](/code/functions/apiSerializeDomain.md), [apiDomainsFindings()](/code/functions/apiDomainsFindings.md), [apiDomainsHistory()](/code/functions/apiDomainsHistory.md)
- [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) - in [apiSerializeForm()](/code/functions/apiSerializeForm.md), [apiSerializeFormSubmission()](/code/functions/apiSerializeFormSubmission.md)
- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiSerializeArticle()](/code/functions/apiSerializeArticle.md), [apiKnowledgeVersionsList()](/code/functions/apiKnowledgeVersionsList.md), [apiKnowledgeVersionsGet()](/code/functions/apiKnowledgeVersionsGet.md)
- [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md) - in [apiSerializeMorningCheck()](/code/functions/apiSerializeMorningCheck.md), [apiSerializeMorningResult()](/code/functions/apiSerializeMorningResult.md)
- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiSerializeNmDiagram()](/code/functions/apiSerializeNmDiagram.md), [apiNmObjectProperties()](/code/functions/apiNmObjectProperties.md)
- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiSerializeProblem()](/code/functions/apiSerializeProblem.md), [apiProblemsGet()](/code/functions/apiProblemsGet.md), [apiProblemNotesList()](/code/functions/apiProblemNotesList.md), [apiProblemAuditList()](/code/functions/apiProblemAuditList.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiSerializeProject()](/code/functions/apiSerializeProject.md), [apiSerializeProjectStage()](/code/functions/apiSerializeProjectStage.md), [apiSerializeProjectItem()](/code/functions/apiSerializeProjectItem.md), [apiSerializeProjectRaid()](/code/functions/apiSerializeProjectRaid.md), [apiProjectHistory()](/code/functions/apiProjectHistory.md)
- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiSerializeIncident()](/code/functions/apiSerializeIncident.md)
- [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) - in [apiSoftwareAppsList()](/code/functions/apiSoftwareAppsList.md), [apiSoftwareAppsGet()](/code/functions/apiSoftwareAppsGet.md), [apiSoftwareAppMachinesList()](/code/functions/apiSoftwareAppMachinesList.md), [apiSerializeLicence()](/code/functions/apiSerializeLicence.md)
- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiSerializeTask()](/code/functions/apiSerializeTask.md), [apiTasksGet()](/code/functions/apiTasksGet.md), [apiTaskCommentsList()](/code/functions/apiTaskCommentsList.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiSerializeTicket()](/code/functions/apiSerializeTicket.md), [apiTicketNotesList()](/code/functions/apiTicketNotesList.md), [apiTicketThreadList()](/code/functions/apiTicketThreadList.md), [apiTicketAuditList()](/code/functions/apiTicketAuditList.md), [apiTicketTimeEntriesList()](/code/functions/apiTicketTimeEntriesList.md), [apiTicketTimeEntriesCreate()](/code/functions/apiTicketTimeEntriesCreate.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiSerializeUser()](/code/functions/apiSerializeUser.md)
- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiSerializeWorkflow()](/code/functions/apiSerializeWorkflow.md), [apiSerializeWorkflowExecution()](/code/functions/apiSerializeWorkflowExecution.md)

