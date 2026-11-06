# apiFailFromService()

Emit the API error response for a ServiceError raised by a shared service (see includes/service_context.php). Maps kind -> HTTP status and passes the code + message through verbatim, so a service-backed handler produces the same error body it did when the logic lived inline. Resolves ServiceError / serviceErrorHttpStatus at call time (the calling resource has required them).

```php
function apiFailFromService(ServiceError $e): void
```

Defined in [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$e` | `ServiceError` |  | the error a shared service threw - its kind picks the status, errorCode and message pass through |

**Returns:** `void`

## Calls

[apiError()](/code/functions/apiError.md), [serviceErrorHttpStatus()](/code/functions/serviceErrorHttpStatus.md)

## Called from

- [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md) - in [apiAnalystsCreate()](/code/functions/apiAnalystsCreate.md), [apiAnalystsGet()](/code/functions/apiAnalystsGet.md), [apiAnalystsUpdate()](/code/functions/apiAnalystsUpdate.md), [apiAnalystsAccessGet()](/code/functions/apiAnalystsAccessGet.md), [apiAnalystsAccessUpdate()](/code/functions/apiAnalystsAccessUpdate.md)
- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetsCreate()](/code/functions/apiAssetsCreate.md), [apiAssetsUpdate()](/code/functions/apiAssetsUpdate.md), [apiAssetAssignmentsCreate()](/code/functions/apiAssetAssignmentsCreate.md), [apiAssetAssignmentsDelete()](/code/functions/apiAssetAssignmentsDelete.md)
- [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md) - in [apiCalendarEventsCreate()](/code/functions/apiCalendarEventsCreate.md), [apiCalendarEventsUpdate()](/code/functions/apiCalendarEventsUpdate.md), [apiCalendarEventsDelete()](/code/functions/apiCalendarEventsDelete.md)
- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesCreate()](/code/functions/apiChangesCreate.md), [apiChangesUpdate()](/code/functions/apiChangesUpdate.md), [apiChangesDelete()](/code/functions/apiChangesDelete.md), [apiChangeCommentsCreate()](/code/functions/apiChangeCommentsCreate.md), [apiChangeCommentsDelete()](/code/functions/apiChangeCommentsDelete.md), [apiChangeCabSave()](/code/functions/apiChangeCabSave.md), [apiChangeCabVote()](/code/functions/apiChangeCabVote.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectsCreate()](/code/functions/apiCmdbObjectsCreate.md), [apiCmdbObjectsUpdate()](/code/functions/apiCmdbObjectsUpdate.md), [apiCmdbObjectsDelete()](/code/functions/apiCmdbObjectsDelete.md), [apiCmdbRelationshipsCreate()](/code/functions/apiCmdbRelationshipsCreate.md), [apiCmdbRelationshipsDelete()](/code/functions/apiCmdbRelationshipsDelete.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractsCreate()](/code/functions/apiContractsCreate.md), [apiContractsUpdate()](/code/functions/apiContractsUpdate.md), [apiContractsDelete()](/code/functions/apiContractsDelete.md), [apiContractTermsSave()](/code/functions/apiContractTermsSave.md), [apiSuppliersCreate()](/code/functions/apiSuppliersCreate.md), [apiSuppliersUpdate()](/code/functions/apiSuppliersUpdate.md), [apiSuppliersDelete()](/code/functions/apiSuppliersDelete.md)
- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiCostCentresCreate()](/code/functions/apiCostCentresCreate.md), [apiCostCentresUpdate()](/code/functions/apiCostCentresUpdate.md), [apiCostCentresDelete()](/code/functions/apiCostCentresDelete.md), [apiCostCentresSync()](/code/functions/apiCostCentresSync.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsCreate()](/code/functions/apiDomainsCreate.md), [apiDomainsUpdate()](/code/functions/apiDomainsUpdate.md), [apiDomainsDelete()](/code/functions/apiDomainsDelete.md), [apiDomainsLookup()](/code/functions/apiDomainsLookup.md), [apiDomainsCheck()](/code/functions/apiDomainsCheck.md)
- [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) - in [apiFormsCreate()](/code/functions/apiFormsCreate.md), [apiFormsUpdate()](/code/functions/apiFormsUpdate.md), [apiFormsDelete()](/code/functions/apiFormsDelete.md), [apiFormVersionsCreate()](/code/functions/apiFormVersionsCreate.md), [apiFormSubmissionsCreate()](/code/functions/apiFormSubmissionsCreate.md), [apiFormSubmissionsDelete()](/code/functions/apiFormSubmissionsDelete.md)
- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiKnowledgeArticlesCreate()](/code/functions/apiKnowledgeArticlesCreate.md), [apiKnowledgeArticlesUpdate()](/code/functions/apiKnowledgeArticlesUpdate.md), [apiKnowledgeArticlesDelete()](/code/functions/apiKnowledgeArticlesDelete.md), [apiKnowledgeArticlesRestore()](/code/functions/apiKnowledgeArticlesRestore.md), [apiKnowledgeArticlesPurge()](/code/functions/apiKnowledgeArticlesPurge.md)
- [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md) - in [apiMorningChecksCreate()](/code/functions/apiMorningChecksCreate.md), [apiMorningChecksUpdate()](/code/functions/apiMorningChecksUpdate.md), [apiMorningChecksDelete()](/code/functions/apiMorningChecksDelete.md), [apiMorningCheckResultsRecord()](/code/functions/apiMorningCheckResultsRecord.md)
- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmDiagramsCreate()](/code/functions/apiNmDiagramsCreate.md), [apiNmDiagramsUpdate()](/code/functions/apiNmDiagramsUpdate.md), [apiNmDiagramsDelete()](/code/functions/apiNmDiagramsDelete.md), [apiNmVersionsCreate()](/code/functions/apiNmVersionsCreate.md)
- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiProblemsCreate()](/code/functions/apiProblemsCreate.md), [apiProblemsUpdate()](/code/functions/apiProblemsUpdate.md), [apiProblemsDelete()](/code/functions/apiProblemsDelete.md), [apiProblemNotesCreate()](/code/functions/apiProblemNotesCreate.md), [apiProblemTicketsLink()](/code/functions/apiProblemTicketsLink.md), [apiProblemTicketsUnlink()](/code/functions/apiProblemTicketsUnlink.md), [apiProblemChangesLink()](/code/functions/apiProblemChangesLink.md), [apiProblemChangesUnlink()](/code/functions/apiProblemChangesUnlink.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectTry()](/code/functions/apiProjectTry.md)
- [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) - in [apiSoftwareLicencesCreate()](/code/functions/apiSoftwareLicencesCreate.md), [apiSoftwareLicencesUpdate()](/code/functions/apiSoftwareLicencesUpdate.md), [apiSoftwareLicencesDelete()](/code/functions/apiSoftwareLicencesDelete.md)
- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiTasksCreate()](/code/functions/apiTasksCreate.md), [apiTasksUpdate()](/code/functions/apiTasksUpdate.md), [apiTasksMove()](/code/functions/apiTasksMove.md), [apiTasksDelete()](/code/functions/apiTasksDelete.md), [apiTaskCommentsCreate()](/code/functions/apiTaskCommentsCreate.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsCreate()](/code/functions/apiTicketsCreate.md), [apiTicketsUpdate()](/code/functions/apiTicketsUpdate.md), [apiTicketsDelete()](/code/functions/apiTicketsDelete.md), [apiTicketsRestore()](/code/functions/apiTicketsRestore.md), [apiTicketNotesCreate()](/code/functions/apiTicketNotesCreate.md), [apiTicketTimeEntriesCreate()](/code/functions/apiTicketTimeEntriesCreate.md), [apiTicketTimeEntriesDelete()](/code/functions/apiTicketTimeEntriesDelete.md)

