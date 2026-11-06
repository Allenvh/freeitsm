# apiPagination()

Pagination from ?page= and ?per_page= (defaults 1 / 25, per_page capped at 100).

```php
function apiPagination(): array
```

Defined in [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), line 106.

**Returns:** `array`

## Called from

- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetsList()](/code/functions/apiAssetsList.md)
- [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md) - in [apiCalendarEventsList()](/code/functions/apiCalendarEventsList.md)
- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesList()](/code/functions/apiChangesList.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectsList()](/code/functions/apiCmdbObjectsList.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractsList()](/code/functions/apiContractsList.md)
- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiCostCentresList()](/code/functions/apiCostCentresList.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsList()](/code/functions/apiDomainsList.md), [apiDomainsHistory()](/code/functions/apiDomainsHistory.md)
- [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) - in [apiFormSubmissionsList()](/code/functions/apiFormSubmissionsList.md)
- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiKnowledgeArticlesList()](/code/functions/apiKnowledgeArticlesList.md)
- [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md) - in [apiMorningCheckResultsList()](/code/functions/apiMorningCheckResultsList.md)
- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmDiagramsList()](/code/functions/apiNmDiagramsList.md)
- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiProblemsList()](/code/functions/apiProblemsList.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsList()](/code/functions/apiProjectsList.md), [apiProjectHistory()](/code/functions/apiProjectHistory.md)
- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiStatusIncidentsList()](/code/functions/apiStatusIncidentsList.md)
- [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) - in [apiSoftwareAppsList()](/code/functions/apiSoftwareAppsList.md), [apiSoftwareLicencesList()](/code/functions/apiSoftwareLicencesList.md)
- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiTasksList()](/code/functions/apiTasksList.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsList()](/code/functions/apiTicketsList.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUsersList()](/code/functions/apiUsersList.md)
- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiWorkflowExecutionsRespondList()](/code/functions/apiWorkflowExecutionsRespondList.md)

