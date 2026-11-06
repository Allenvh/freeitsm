# integrationsTrackerProviders()

Providers that are genuinely issue trackers — the ones an escalation, a "raise an issue" button or a workflow action can target.

Use this rather than `integrationsAvailableProviders()` anywhere the answer
"can we push a ticket into it?" matters, or Slack will be offered as somewhere
to raise a work item and fail at the moment somebody tries.

```php
function integrationsTrackerProviders(): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 174.

**Returns:** `array`

## Calls

[integrationsAvailableProviders()](/code/functions/integrationsAvailableProviders.md)

*Nothing calls this function by name.*

