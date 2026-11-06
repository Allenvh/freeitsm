# workflowExpiryWindows()

The windows we announce an expiry at. A contract expiring in 45 days crosses the 90-day window today and the 30-day window in a fortnight — two separate emissions, each carrying `window_days`, so a workflow can say "only tell me at 30 days" with a plain condition rather than needing its own scheduling.

```php
function workflowExpiryWindows(): array
```

Defined in [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md), line 110.

**Returns:** `array`

## Called from

- [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) - in [workflowEmitContractExpiries()](/code/functions/workflowEmitContractExpiries.md), [workflowEmitWarrantyExpiries()](/code/functions/workflowEmitWarrantyExpiries.md)

