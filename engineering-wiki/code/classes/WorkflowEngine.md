# WorkflowEngine

> Class in [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), line 38

> [!TIP]
> **Developer guide on the GitHub wiki:** [Workflows](https://github.com/edmozley/freeitsm/wiki/Workflows)

Workflow Engine ----------------------------------------------------------------------

Cross-module automation. Other modules CALL into this engine when their
events happen (e.g. tickets/save.php calls WorkflowEngine::dispatch(
'ticket.created', ['ticket' => `$row`]) after a successful insert). The
engine finds active workflows matching the event, evaluates conditions
against the payload, executes actions in order, and writes a row to
workflow_executions for every run so the user can see what happened.

The contract is deliberately small in v1:

  - Triggers are flat event-name strings. The catalogue lives in
    `availableTriggers()`; new ones are added here and wired in their
    module's save flow.

  - Conditions are an array of {field, op, value} objects. The engine
    reads `field` from the payload via dot-notation (`ticket.priority`).
    All conditions must match (AND). If empty, the workflow always fires.

  - Actions are an array of {type, args} objects executed in order.
    The catalogue lives in `availableActions()`; the handlers live as
    private methods on this class so each action's contract is in one
    place.

  - Execution is synchronous in v1. Each event triggers a same-request
    pass through the engine. An async queue + retries are planned but
    not built — keep actions fast and idempotent.

Failures don't propagate. The engine catches per-step exceptions, logs
them in the execution row, and continues. The point is that a buggy
workflow can't take down the host module's request.

## Methods

| Method | What it does |
|---|---|
| [availableTriggers()](#availabletriggers) | Events the engine knows about. Display labels are i18n-ready strings but kept plain English here for simplicity in v1 — the editor UI shows them as-is. Adding a trigger = one entry here + a single `dispatch()` call from the corresponding module's save flow. |
| [crudEntities()](#crudentities) | CRUD + settings entities that each get explicit .created/.updated/.deleted triggers. `[label, [condition field paths]]`. Domain entities carry a few useful fields; settings/lookups carry just id + name (their own id isn't a useful filter, but name is, and the .id/.name convention keeps the editor's type inference happy). |
| [emitCrud()](#emitcrud) | Fire a CRUD / settings event: <entityKey>.<action> with a {entityKey: {id, name}} payload matching the entity's condition fields. `$action` is 'created' \| 'updated' \| 'deleted'. `dispatch()` is self-safe, so call sites need no try/catch — a workflow hiccup can't affect the write. |
| [availableFields()](#availablefields) | Per-trigger field hints — the dotted paths into the payload that are valid for `condition.field`. Used to populate the editor's field dropdown so users aren't guessing. |
| [webhookFormats()](#webhookformats) | Every message-wrapping format available on this install: the built-ins plus whatever the admin has added. Cached per request. |
| [renderFormatBody()](#renderformatbody) | Render a format's JSON body template. |
| [enrichWithReferences()](#enrichwithreferences) | Add `{{<object>.number}}` beside `{{<object>.id}}` wherever the record has a human-facing reference. Cached; never clobbers a value the host module already supplied (problem.* triggers ship `problem.problem_number` themselves, and that stays authoritative). |
| [enrichPayloadForTemplates()](#enrichpayloadfortemplates) | Everything a merge code might need that the raw event payload doesn't carry: readable lookup names, and human-facing record references. |
| [lookupNamePath()](#lookupnamepath) | The `_name` merge code that pairs with a lookup id field. |
| [lookupLabel()](#lookuplabel) | Resolve one lookup id to its human label (cached per request — a ticket payload touches ~6 of these and the same status/priority recurs across a fan-out, so the cache earns its keep). |
| [enrichWithLookupNames()](#enrichwithlookupnames) | Add a human-readable `_name` beside every lookup id the payload carries. |
| [dotSet()](#dotset) | Dotted-path setter — the write twin of `dotGet()`. |
| [availableVariables()](#availablevariables) | The merge codes (`{{variables}}`) a workflow on `$trigger` can actually resolve — the catalogue behind the editor's variable picker. |
| [variablePrefixes()](#variableprefixes) | Merge-code prefixes that are open-ended for this trigger — anything beneath them is valid even though it can't be listed up front. |
| [humaniseFieldPath()](#humanisefieldpath) | 'ticket.requester_email' → 'Ticket · Requester email'. Derived rather than hand-mapped so it stays correct as the trigger catalogue grows — a hand-written label map would rot immediately. |
| [humaniseSegment()](#humanisesegment) | One dot-path segment as a label: underscores to spaces, a trailing " id" dropped, first letter capitalised ("id" alone becomes "ID"). |
| [availableOperators()](#availableoperators) | The condition operators the editor offers, as operator key => label; `evaluateOperator()` implements each one. |
| [fieldType()](#fieldtype) | Resolve the type of a field path. Lookup fields always win; explicit entries in FIELD_TYPES take the next precedence; the default is a mild convention — anything ending in `.id` is treated as numeric, everything else as text. New fields added to `availableFields()` get a sensible default without needing an explicit FIELD_TYPES entry, but it's the explicit entry that's authoritative. |
| [operatorsForFieldType()](#operatorsforfieldtype) | Operator slugs valid for a given field type. The full catalogue lives in `availableOperators()`; this is the per-type subset. Drives which operators the editor shows once the user picks a field. Lookup fields are handled by the editor with their own friendly relabelling (is / is not / is empty / is not empty mapping to in / not_in / is_empty / is_not_empty), so we just return the underlying slugs here. |
| [availableValuesForField()](#availablevaluesforfield) | For a normalised id field, return the list of selectable {id, label} pairs from the joined table. Returns null for free-text fields (the editor falls back to a plain text input in that case). Returns null defensively if the lookup table doesn't exist (older installs that haven't migrated yet) so the editor still works. |
| [availableActions()](#availableactions) | Action handlers available to the engine. Each entry's `args` is the editor UI spec, NOT just a name → type map — it drives the per-action form the user sees when they pick an action from the dropdown, so the editor never has to know about specific action types. |
| [availableActionLookup()](#availableactionlookup) | Resolve a named action-arg lookup to its {id, label} pairs. Returns null for unknown keys — the editor falls back to a text input in that case so the workflow editor never breaks on a stale spec. |
| [dispatch()](#dispatch) | Fire all active workflows matching `$event` with the supplied payload. Called from host modules' save flows. Safe to call even if no workflows exist — does nothing in that case. |
| [manualFire()](#manualfire) | Fire a single workflow with a synthetic payload — used by the editor's "Test fire" button so the user can verify a rule without waiting for a real event. |
| [run()](#run) | Loop-protection wrapper around `runInner()`. Refuses re-entrant / runaway runs (see the class-level notes), recording a blocked attempt as an 'aborted' execution row, and otherwise manages the request-scoped counters around the real run. |
| [recordAbortedRun()](#recordabortedrun) | Record a run that loop-protection refused to execute. Writes a single 'aborted' execution row (status / step_log / error_message) so the block is visible in the execution audit, and stamps the parent workflow's last-run state for non-manual runs. Never throws. |
| [runInner()](#runinner) | Runs one workflow against one event: adds {{event}} and the readable lookup names to the payload, records a running execution, checks the conditions, runs the actions (or previews them on a dry run) and stores the outcome and step log. |
| [describeAction()](#describeaction) | The catalogue label for an action type — what a dry run reports it "would have" done. Falls back to the raw type for an action the catalogue no longer knows about. |
| [previewArgs()](#previewargs) | Render an action's args for a dry run: every {{variable}} substituted against the real payload, so what you read in the log is exactly the value the handler would have been handed. |
| [evaluateConditions()](#evaluateconditions) | True when every condition matches the payload (AND), or there are none. Each condition checked is added to the step log, and checking stops at the first that fails. |
| [evaluateOperator()](#evaluateoperator) | Applies one condition operator to the payload value and the configured value; numeric operators need both sides numeric. |
| [value_in_list()](#value_in_list) | True if `$actual` matches any value in `$list`. `$list` can be an array, a comma-separated string, or a single scalar. Comparison uses loose-equal (string cast both sides) so ids stored as numbers in the payload match the strings the form serialises. |
| [loose_equal()](#loose_equal) | Compares two values as strings, so a typed-in "4" equals a numeric id 4; null also equals an empty string. |
| [executeAction()](#executeaction) | Hands one action to its action_* handler by type and returns the handler's result; an unknown type throws. |
| [executeActionList()](#executeactionlist) | Run an ordered list of actions against a payload. |
| [runActionList()](#runactionlist) | Run a list of actions that belongs to something OTHER than a stored workflow — today, a form's own "when submitted / approved / rejected" list (#95). |
| [mergeStepResult()](#mergestepresult) | Make a completed action's result addressable by the actions after it. |
| [renderTemplate()](#rendertemplate) | Replaces each {{path.to.field}} in a string with the payload value at that dot path - empty when missing, JSON when the value is an array. |
| [argString()](#argstring) | Resolve a single arg to a (rendered) string and trim it. Returns '' if the arg is missing — handlers decide whether that's fatal. |
| [argInt()](#argint) | Resolve a single arg to an int, or null if blank / non-numeric. (Handler enforces required-ness if the arg matters.) |
| [argBool()](#argbool) | Resolve a checkbox arg (`'type' => 'bool'`). |
| [action_attach_checklist()](#action_attach_checklist) | attach_checklist: copies each chosen checklist template, with its items, onto the ticket, skipping templates already attached. No templates chosen is a skip, not an error. |
| [action_log_message()](#action_log_message) | log_message: puts the rendered message into the run's step log and changes nothing else. |
| [action_set_ticket_status()](#action_set_ticket_status) | set_ticket_status: writes the ticket's status directly, stamping or clearing closed_datetime. |
| [action_set_ticket_priority()](#action_set_ticket_priority) | set_ticket_priority: sets the ticket's priority after checking the priority id exists. |
| [action_assign_ticket()](#action_assign_ticket) | assign_ticket: sets both the assigned analyst and the owner of the ticket to one analyst, so the two stay in step. |
| [action_add_ticket_note()](#action_add_ticket_note) | add_ticket_note: adds the rendered note to the ticket's audit trail as a "Workflow Note" with no analyst. |
| [action_send_email()](#action_send_email) | send_email: sends an email from the ticket's mailbox or a chosen one, through Microsoft Graph, Gmail or SMTP depending on the mailbox. |
| [plainEmailBody()](#plainemailbody) | The house email styling, without the reply marker. |
| [action_create_task()](#action_create_task) | create_task: creates a task, optionally linked to a ticket, using the default status and priority when none is chosen and placing it at the end of its board column. |
| [action_create_project()](#action_create_project) | A project proposal (3.3.0) - the rules are includes/projects/intake.php's. |
| [action_create_ticket()](#action_create_ticket) | create_ticket: raises a new ticket. |
| [action_send_webhook()](#action_send_webhook) | POST a payload to an external URL — the outbound-webhook action. |
| [action_escalate_to_tracker()](#action_escalate_to_tracker) | Raise an issue in an external tracker from this ticket. |
| [action_send_note_to_tracker()](#action_send_note_to_tracker) | Post a comment onto the issue this ticket is already linked to. |
| [buildWebhookRequest()](#buildwebhookrequest) | Build the outbound-webhook HTTP request (url, headers incl. any HMAC signature, and the rendered/validated JSON body) from the action's args and the trigger payload. Shared by the live action (action_send_webhook, which then enqueues the result) and the editor's "Send test" preview endpoint (which sends it synchronously) — so the test can never drift from a real send. Throws on any validation failure (bad url, empty message, invalid JSON). |
| [enrichWithFullObjects()](#enrichwithfullobjects) | Enrich a trigger payload with the FULL, API-shaped version of its primary object, reusing the REST API serialisers so a webhook can emit exactly the JSON that GET /<resource>/{id} returns — rich, consistent and already typed in the OpenAPI spec. The full object is attached under <key>.full, so a Custom body can embed the whole record with {{ticket.full}} (the template engine json_encodes arrays), and the "Full record" preset sends it wholesale. |
| [fullObjectLoaders()](#fullobjectloaders) | The registry of full-object loaders, keyed by payload key. Each loader takes an id and returns the API-shaped record (or null if gone). Built per-call (not a static, so it can hold closures) — cheap, since it only runs when a webhook actually needs a full object. One entry per resource whose GET /{id} is a clean select+serialise; add a resource here to light up its `.full`. |
| [dotGet()](#dotget) | Dotted-path getter — `dotGet(['ticket' => ['id' => 5]], 'ticket.id')` → 5. |
| [decodeJsonField()](#decodejsonfield) | A JSON column (a workflow's conditions or actions) decoded to an array; [] when empty or not valid JSON. |

### availableTriggers

*public static* - line 92

Events the engine knows about. Display labels are i18n-ready strings but kept plain English here for simplicity in v1 — the editor UI shows them as-is. Adding a trigger = one entry here + a single `dispatch()` call from the corresponding module's save flow.

All seven events are now wired from their host modules:
  ticket.*          — api/tickets/create_ticket.php + assign_ticket.php
  form.submitted    — api/forms/submit_form.php (after commit)
  task.completed    — api/tasks/save.php (on transition into a closed status)
  change.approved   — api/change-management/submit_cab_vote.php (CAB threshold)
                      + save.php (manual status edit to Approved)

```php
function availableTriggers(): array
```

**Called from:** [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md), [api/workflow/save.php](/code/files/api/workflow/save.php.md), [workflow/editor.php](/code/files/workflow/editor.php.md), [workflow/includes/templates.php](/code/files/workflow/includes/templates.php.md)

### crudEntities

*private static* - line 228

CRUD + settings entities that each get explicit .created/.updated/.deleted triggers. `[label, [condition field paths]]`. Domain entities carry a few useful fields; settings/lookups carry just id + name (their own id isn't a useful filter, but name is, and the .id/.name convention keeps the editor's type inference happy).

```php
function crudEntities(): array
```

### emitCrud

*public static* - line 288

Fire a CRUD / settings event: <entityKey>.<action> with a {entityKey: {id, name}} payload matching the entity's condition fields. `$action` is 'created' | 'updated' | 'deleted'. `dispatch()` is self-safe, so call sites need no try/catch — a workflow hiccup can't affect the write.

```php
function emitCrud(string $entityKey, string $action, int $id, ?string $name = null): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$entityKey` | `string` |  | the entity's key in `crudEntities()`, e.g. 'department'; also the payload key |
| `$action` | `string` |  | 'created', 'updated' or 'deleted' |
| `$id` | `int` |  | the record's id |
| `$name` | `?string` | `null` | the record's display name; null when there is none (e.g. after a delete) |

**Called from:** [api/domains/statuses.php](/code/files/api/domains/statuses.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/calendar.php](/code/files/includes/services/calendar.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md), [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md), [includes/services/software.php](/code/files/includes/services/software.php.md)

### availableFields

*public static* - line 300

Per-trigger field hints — the dotted paths into the payload that are valid for `condition.field`. Used to populate the editor's field dropdown so users aren't guessing.

```php
function availableFields(string $trigger): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$trigger` | `string` |  | the trigger event key, e.g. 'ticket.created'; an unknown one gives [] |

**Called from:** [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md), [workflow/editor.php](/code/files/workflow/editor.php.md)

### webhookFormats

*public static* - line 572

Every message-wrapping format available on this install: the built-ins plus whatever the admin has added. Cached per request.

Falls back to the built-ins on ANY database problem — the engine has to
keep sending webhooks even if the formats table is missing or broken.

```php
function webhookFormats(): array
```

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 581 | `WorkflowEngine::webhookFormats` | read [webhook_message_formats](/code/tables/webhook_message_formats.md) |  |

**Called from:** [workflow/editor.php](/code/files/workflow/editor.php.md)

### renderFormatBody

*private static* - line 617

Render a format's JSON body template.

⚠️ THE ESCAPING POINT. We do NOT string-substitute into the raw template
text: a message containing a double quote, a backslash or a newline would
produce invalid JSON (or, worse, let a crafted message inject structure
into the payload). Instead we DECODE the template, walk it, substitute
inside the string VALUES, and re-encode — so json_encode does the
escaping, exactly as the old hardcoded array-then-encode approach did.

```php
function renderFormatBody(string $template, string $message, array $payload): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$template` | `string` |  | the format's JSON body template with {{message}} and other merge codes |
| `$message` | `string` |  | the action's rendered message, put into {{message}} |
| `$payload` | `array` |  | the run's event payload, for the other merge codes |

### enrichWithReferences

*public static* - line 667

Add `{{<object>.number}}` beside `{{<object>.id}}` wherever the record has a human-facing reference. Cached; never clobbers a value the host module already supplied (problem.* triggers ship `problem.problem_number` themselves, and that stays authoritative).

```php
function enrichWithReferences(PDO $conn, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$payload` | `array` |  | the event payload; ticket.id and problem.id gain a .number beside them |

### enrichPayloadForTemplates

*public static* - line 703

Everything a merge code might need that the raw event payload doesn't carry: readable lookup names, and human-facing record references.

ONE entry point, called by the engine at run time AND by the Send-test
preview — so the preview cannot drift from what production actually sends
(the failure that made the old hand-written sample payload a liability).

```php
function enrichPayloadForTemplates(PDO $conn, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$payload` | `array` |  | the event payload as dispatched (plus {{event}}) |

**Called from:** [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)

### lookupNamePath

*private static* - line 720

The `_name` merge code that pairs with a lookup id field.

ticket.priority_id        → ticket.priority_name
  ticket.created_by         → ticket.created_by_name   (no _id to strip)
  approver.id               → approver.name

Returns null if there's nothing sensible to derive.

```php
function lookupNamePath(string $idPath): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$idPath` | `string` |  | a lookup id's dotted path, e.g. 'ticket.priority_id' |

### lookupLabel

*private static* - line 736

Resolve one lookup id to its human label (cached per request — a ticket payload touches ~6 of these and the same status/priority recurs across a fan-out, so the cache earns its keep).

```php
function lookupLabel(PDO $conn, array $spec, int $id): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$spec` | `array` |  | a FIELD_LOOKUP_TABLES entry (table, label_col) |
| `$id` | `int` |  | the lookup row's id |

### enrichWithLookupNames

*public static* - line 771

Add a human-readable `_name` beside every lookup id the payload carries.

WHY: `{{ticket.priority_id}}` renders as `4`. In a Slack message that is
useless — nobody reading the channel knows what priority 4 is. This makes
`{{ticket.priority_name}}` ("Critical") available everywhere a merge code
can be used, resolved from the same FIELD_LOOKUP_TABLES registry that
powers the condition dropdowns, so a new lookup field gets its name code
for free.

NEVER CLOBBERS an existing value: `form.submitted` already ships a real
`form.name` in its payload, and the host module's value wins over anything
we'd derive from `form.id`.

```php
function enrichWithLookupNames(PDO $conn, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$payload` | `array` |  | the event payload; each lookup id in FIELD_LOOKUP_TABLES gains a _name twin |

### dotSet

*private static* - line 796

Dotted-path setter — the write twin of `dotGet()`.

```php
function dotSet(array &$haystack, string $path, $value): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$haystack` | `array` |  | the array to write into, by reference; missing levels are created |
| `$path` | `string` |  | the dotted path, e.g. 'ticket.number' |
| `$value` |  |  | the value to put there |

### availableVariables

*public static* - line 826

The merge codes (`{{variables}}`) a workflow on `$trigger` can actually resolve — the catalogue behind the editor's variable picker.

This is deliberately a DIFFERENT list from `availableFields()`: that one
answers "what can I write a condition on" (scalars only), while this one
answers "what can I paste into a message". The two overlap but aren't the
same — `{{event}}` is a valid merge code and a useless condition field;
`{{ticket.full}}` is a whole JSON object, meaningless in a comparison.

Why it matters: `renderTemplate()` resolves an unknown path to an EMPTY
STRING. So a knowledge.published workflow whose webhook body says
{{ticket.subject}} doesn't fail — it silently posts a blank. Offering
only the codes the trigger can really resolve is what stops that.

Returns [['path' => ..., 'label' => ..., 'note' => ...], ...].

```php
function availableVariables(string $trigger): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$trigger` | `string` |  | the trigger event key, e.g. 'ticket.created' |

**Called from:** [workflow/editor.php](/code/files/workflow/editor.php.md)

### variablePrefixes

*public static* - line 922

Merge-code prefixes that are open-ended for this trigger — anything beneath them is valid even though it can't be listed up front.

The editor uses this so its unknown-variable warning doesn't cry wolf
over a legitimate {{submission.fields.Start date}}.

```php
function variablePrefixes(string $trigger): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$trigger` | `string` |  | the trigger event key, e.g. 'form.submitted' |

**Called from:** [workflow/editor.php](/code/files/workflow/editor.php.md)

### humaniseFieldPath

*private static* - line 957

'ticket.requester_email' → 'Ticket · Requester email'. Derived rather than hand-mapped so it stays correct as the trigger catalogue grows — a hand-written label map would rot immediately.

```php
function humaniseFieldPath(string $path): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | a dotted payload path, e.g. 'ticket.requester_email' |

### humaniseSegment

*private static* - line 968

One dot-path segment as a label: underscores to spaces, a trailing " id" dropped, first letter capitalised ("id" alone becomes "ID").

```php
function humaniseSegment(string $seg): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$seg` | `string` |  | one segment of a dotted path, e.g. 'requester_email' |

### availableOperators

*public static* - line 979

The condition operators the editor offers, as operator key => label; `evaluateOperator()` implements each one.

```php
function availableOperators(): array
```

**Called from:** [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md), [workflow/editor.php](/code/files/workflow/editor.php.md)

### fieldType

*public static* - line 1124

Resolve the type of a field path. Lookup fields always win; explicit entries in FIELD_TYPES take the next precedence; the default is a mild convention — anything ending in `.id` is treated as numeric, everything else as text. New fields added to `availableFields()` get a sensible default without needing an explicit FIELD_TYPES entry, but it's the explicit entry that's authoritative.

```php
function fieldType(string $fieldPath): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fieldPath` | `string` |  | a condition field's dotted path, e.g. 'ticket.priority_id' |

**Called from:** [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md), [workflow/editor.php](/code/files/workflow/editor.php.md)

### operatorsForFieldType

*public static* - line 1143

Operator slugs valid for a given field type. The full catalogue lives in `availableOperators()`; this is the per-type subset. Drives which operators the editor shows once the user picks a field. Lookup fields are handled by the editor with their own friendly relabelling (is / is not / is empty / is not empty mapping to in / not_in / is_empty / is_not_empty), so we just return the underlying slugs here.

```php
function operatorsForFieldType(string $type): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | 'lookup', 'numeric' or 'text' (from `fieldType()`); anything else is treated as text |

**Called from:** [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md)

### availableValuesForField

*public static* - line 1165

For a normalised id field, return the list of selectable {id, label} pairs from the joined table. Returns null for free-text fields (the editor falls back to a plain text input in that case). Returns null defensively if the lookup table doesn't exist (older installs that haven't migrated yet) so the editor still works.

```php
function availableValuesForField(string $fieldPath): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fieldPath` | `string` |  | a condition field's dotted path; only keys of FIELD_LOOKUP_TABLES give values |

**Called from:** [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md), [workflow/editor.php](/code/files/workflow/editor.php.md)

### availableActions

*public static* - line 1213

Action handlers available to the engine. Each entry's `args` is the editor UI spec, NOT just a name → type map — it drives the per-action form the user sees when they pick an action from the dropdown, so the editor never has to know about specific action types.

Per-arg fields:
  - type:          'text' / 'textarea' / 'numeric' / 'bool' / 'lookup'
  - label:         human-readable label shown above the input
  - required:      whether the engine throws if missing (default false)
  - default:       pre-fill value when the action is first added
  - supports_vars: arg can reference payload data via {{path.to.field}}
                   (drives an inline hint in the UI; the engine
                   interpolates these via `renderTemplate()`)
  - lookup:        for type='lookup', names a lookup source
                   (`ticket_status`, `ticket_priority`, `analyst`,
                   `department`, `ticket_type`, `task_status`,
                   `task_priority`) — values listed in
                   ACTION_ARG_LOOKUPS below

```php
function availableActions(): array
```

**Called from:** [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md), [api/workflow/save.php](/code/files/api/workflow/save.php.md), [forms/edit/index.php](/code/files/forms/edit/index.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md), [workflow/editor.php](/code/files/workflow/editor.php.md), [workflow/includes/templates.php](/code/files/workflow/includes/templates.php.md)

### availableActionLookup

*public static* - line 1454

Resolve a named action-arg lookup to its {id, label} pairs. Returns null for unknown keys — the editor falls back to a text input in that case so the workflow editor never breaks on a stale spec.

```php
function availableActionLookup(string $key): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | an action arg's lookup name, a key of ACTION_ARG_LOOKUPS, e.g. 'mailbox' |

**Called from:** [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md), [forms/edit/index.php](/code/files/forms/edit/index.php.md), [workflow/editor.php](/code/files/workflow/editor.php.md), [workflow/includes/templates.php](/code/files/workflow/includes/templates.php.md)

### dispatch

*public static* - line 1476

Fire all active workflows matching `$event` with the supplied payload. Called from host modules' save flows. Safe to call even if no workflows exist — does nothing in that case.

Errors are swallowed (logged into the workflow_executions row but
not re-thrown) so a buggy workflow can never break the host module's
request.

```php
function dispatch(string $event, array $payload): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `string` |  | the trigger event key, e.g. 'ticket.created' |
| `$payload` | `array` |  | the event's data, shaped as `availableFields()` lists for that trigger |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1506 | `WorkflowEngine::dispatch` | read [workflows](/code/tables/workflows.md) |  |

**Called from:** [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md), [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md), [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/domains/watch.php](/code/files/includes/domains/watch.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [includes/intune.php](/code/files/includes/intune.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/services/calendar.php](/code/files/includes/services/calendar.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md), [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md), [includes/services/contracts.php](/code/files/includes/services/contracts.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md), [includes/services/problems.php](/code/files/includes/services/problems.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/ticket_events.php](/code/files/includes/ticket_events.php.md), [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md)

### manualFire

*public static* - line 1531

Fire a single workflow with a synthetic payload — used by the editor's "Test fire" button so the user can verify a rule without waiting for a real event.

Returns the workflow_executions row as an array (for the API to
surface back to the caller).

```php
function manualFire(int $workflowId, array $samplePayload = [], bool $dryRun = false): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$workflowId` | `int` |  | the workflows id to run |
| `$samplePayload` | `array` | `[]` | the payload to run against, standing in for a real event |
| `$dryRun` | `bool` | `false` | true to record what the actions would do without running them |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1534 | `WorkflowEngine::manualFire` | read [workflows](/code/tables/workflows.md) |  |

**Called from:** [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), [api/workflow/fire.php](/code/files/api/workflow/fire.php.md)

### run

*private static* - line 1559

Loop-protection wrapper around `runInner()`. Refuses re-entrant / runaway runs (see the class-level notes), recording a blocked attempt as an 'aborted' execution row, and otherwise manages the request-scoped counters around the real run.

```php
function run(array $wf, string $event, array $payload, bool $isManual, bool $dryRun = false): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$wf` | `array` |  | the workflows row |
| `$event` | `string` |  | the trigger event key |
| `$payload` | `array` |  | the event payload |
| `$isManual` | `bool` |  | true for "Test fire", which leaves the workflow's run statistics alone |
| `$dryRun` | `bool` | `false` | true to preview the actions without running them |

### recordAbortedRun

*private static* - line 1603

Record a run that loop-protection refused to execute. Writes a single 'aborted' execution row (status / step_log / error_message) so the block is visible in the execution audit, and stamps the parent workflow's last-run state for non-manual runs. Never throws.

```php
function recordAbortedRun(
    array $wf,
    string $event,
    array $payload,
    bool $isManual,
    bool $dryRun,
    string $message
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$wf` | `array` |  | the workflows row that was refused |
| `$event` | `string` |  | the trigger event key |
| `$payload` | `array` |  | the event payload, stored with the aborted row |
| `$isManual` | `bool` |  | true for "Test fire", which leaves the workflow's last-run state alone |
| `$dryRun` | `bool` |  | true when the refused run was a dry run |
| `$message` | `string` |  | why loop protection refused it |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1611 | `WorkflowEngine::recordAbortedRun` | **insert** [workflow_executions](/code/tables/workflow_executions.md) (`workflow_id`, `workflow_name`, `trigger_event`, `trigger_payload`, `status`, `is_dry_run`, `started_datetime`, `finished_datetime`, `step_log`, `error_message`) |  |
| 1623 | `WorkflowEngine::recordAbortedRun` | **update** [workflows](/code/tables/workflows.md) (`last_run_datetime`, `last_run_status`) |  |

### runInner

*private static* - line 1658

Runs one workflow against one event: adds {{event}} and the readable lookup names to the payload, records a running execution, checks the conditions, runs the actions (or previews them on a dry run) and stores the outcome and step log.

Conditions that fail make the run "skipped"; an exception makes it
"failed". Last-run stats on the workflow are updated only for real,
non-manual runs.

```php
function runInner(
    array $wf,
    string $event,
    array $payload,
    bool $isManual,
    bool $dryRun = false
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$wf` | `array` |  | the workflows row (id, name, conditions and actions as JSON) |
| `$event` | `string` |  | the trigger event key |
| `$payload` | `array` |  | the event payload |
| `$isManual` | `bool` |  | true for "Test fire", which leaves the workflow's run statistics alone |
| `$dryRun` | `bool` | `false` | true to preview the actions without running them |

**Writes:** workflow_executions - one row per run, with the payload and step log

**Writes:** workflows(last_run_datetime, last_run_status, run_count)

**Returns:** array execution_id, workflow_id, status, is_dry_run, step_log, error_message

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1685 | `WorkflowEngine::runInner` | **insert** [workflow_executions](/code/tables/workflow_executions.md) (`workflow_id`, `workflow_name`, `trigger_event`, `trigger_payload`, `status`, `is_dry_run`, `started_datetime`) |  |
| 1719 | `WorkflowEngine::runInner` | **update** [workflow_executions](/code/tables/workflow_executions.md) (`status`, `finished_datetime`, `step_log`, `error_message`) |  |
| 1729 | `WorkflowEngine::runInner` | **update** [workflows](/code/tables/workflows.md) (`last_run_datetime`, `last_run_status`, `run_count`) |  |

### describeAction

*private static* - line 1755

The catalogue label for an action type — what a dry run reports it "would have" done. Falls back to the raw type for an action the catalogue no longer knows about.

```php
function describeAction(string $type): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | the action type, e.g. 'send_email' |

### previewArgs

*private static* - line 1771

Render an action's args for a dry run: every {{variable}} substituted against the real payload, so what you read in the log is exactly the value the handler would have been handed.

The signing secret is redacted — a dry run is a debugging surface and
must not become a way to read a secret back out of a saved workflow.

```php
function previewArgs(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's saved args |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

### evaluateConditions

*private static* - line 1797

True when every condition matches the payload (AND), or there are none. Each condition checked is added to the step log, and checking stops at the first that fails.

```php
function evaluateConditions(array $conditions, array $payload, array &$stepLog): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conditions` | `array` |  | the workflow's conditions, each [field, op, value] |
| `$payload` | `array` |  | the run's payload the fields are read from |
| `$stepLog` | `array` |  | the run's step log, by reference; one entry is added per condition checked |

### evaluateOperator

*private static* - line 1829

Applies one condition operator to the payload value and the configured value; numeric operators need both sides numeric.

```php
function evaluateOperator(string $op, $actual, $value): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$op` | `string` |  | an operator key from `availableOperators()`; an unknown one fails the condition |
| `$actual` |  |  | the value found in the payload (null when the field is absent) |
| `$value` |  |  | the value the condition was configured with |

### value_in_list

*private static* - line 1859

True if `$actual` matches any value in `$list`. `$list` can be an array, a comma-separated string, or a single scalar. Comparison uses loose-equal (string cast both sides) so ids stored as numbers in the payload match the strings the form serialises.

```php
function value_in_list($actual, $list): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$actual` |  |  | the value found in the payload |
| `$list` |  |  | the allowed values: an array, a comma-separated string or a single value |

### loose_equal

*private static* - line 1880

Compares two values as strings, so a typed-in "4" equals a numeric id 4; null also equals an empty string.

```php
function loose_equal($a, $b): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` |  |  | one value, e.g. the payload's |
| `$b` |  |  | the other, e.g. the configured one |

### executeAction

*private static* - line 1899

Hands one action to its action_* handler by type and returns the handler's result; an unknown type throws.

```php
function executeAction(string $type, array $args, array $payload)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | the action type, e.g. 'send_email' |
| `$args` | `array` |  | the action's saved args, not yet rendered |
| `$payload` | `array` |  | the run's payload, including earlier steps' results |

### executeActionList

*private static* - line 1948

Run an ordered list of actions against a payload.

Extracted from `runInner()` when forms grew their own action lists (#95), so
a form's "when submitted" list and a stored workflow's actions run through
exactly one implementation. Two copies of this loop would drift on the
first change to error handling or chaining, and the difference would only
ever show up as "it behaves differently from the form editor".

`$payload` and `$stepLog` are by reference: the payload accumulates each
step's result for the steps after it, and the caller owns the log.

```php
function executeActionList(
    array $actions,
    array &$payload,
    array &$stepLog,
    bool $dryRun = false
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$actions` | `array` |  | the actions in order, each [type, args] |
| `$payload` | `array` |  | the run's payload, by reference; each step's result is added for the next |
| `$stepLog` | `array` |  | the run's step log, by reference |
| `$dryRun` | `bool` | `false` | true to record what each action would do without running it |

**Returns:** array{0: string, 1: ?string} [status, errorMessage]

### runActionList

*public static* - line 2022

Run a list of actions that belongs to something OTHER than a stored workflow — today, a form's own "when submitted / approved / rejected" list (#95).

Logged to `workflow_executions` like any other run, with `workflow_id`
NULL and the form's name in `workflow_name` (the column is nullable and
carries a name snapshot precisely so a run can outlive, or never have,
a parent row). Without this a form's automation would be the one kind
nobody could audit — and "it didn't do anything and I can't see why" is
the worst failure mode automation has.

Never throws: the caller is a form submission or an approval decision,
and neither may fail because a rule someone configured is wrong.

```php
function runActionList(string $label, string $event, array $actions, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$label` | `string` |  | the name stored as workflow_name in the run history, e.g. the form's name |
| `$event` | `string` |  | the event key it ran for, e.g. 'form.submitted' |
| `$actions` | `array` |  | the actions in order, each [type, args] |
| `$payload` | `array` |  | the event payload |

**Returns:** array{status: string, execution_id: ?int, steps: array}

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2037 | `WorkflowEngine::runActionList` | **insert** [workflow_executions](/code/tables/workflow_executions.md) (`workflow_id`, `workflow_name`, `trigger_event`, `trigger_payload`, `status`, `is_dry_run`, `started_datetime`) |  |
| 2048 | `WorkflowEngine::runActionList` | **update** [workflow_executions](/code/tables/workflow_executions.md) (`status`, `finished_datetime`, `step_log`, `error_message`) |  |

**Called from:** [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

### mergeStepResult

*private static* - line 2086

Make a completed action's result addressable by the actions after it.

Two names for the same thing, because two different questions get asked:
  {{last.ticket_id}}     — "the action just before this one"
  {{steps.1.ticket_id}}  — "the first action, whatever has happened since"

`last` is enough for the common two-action chain; `steps` is what a third
action needs, because by then `last` is the second action's result.

⚠️ `steps` is ONE-BASED. The editor numbers actions from 1 on screen, and a
merge code that disagreed with the number printed next to the action would
be wrong in the one place people copy it from.

Nothing is merged for a failed action (the chain stops there anyway) or a
dry run (nothing ran, so there is no result to speak of — a later action's
preview shows the merge code unresolved, which is honest).

```php
function mergeStepResult(array &$payload, int $index, $result): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$payload` | `array` |  | the run's payload, by reference |
| `$index` | `int` |  | the action's zero-based position; stored one-based under steps |
| `$result` |  |  | what the action returned; anything but a non-empty array is ignored |

### renderTemplate

*private static* - line 2100

Replaces each {{path.to.field}} in a string with the payload value at that dot path - empty when missing, JSON when the value is an array.

```php
function renderTemplate(string $tmpl, array $payload): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tmpl` | `string` |  | the text with {{path.to.field}} merge codes |
| `$payload` | `array` |  | the run's payload the paths are read from |

### argString

*private static* - line 2117

Resolve a single arg to a (rendered) string and trim it. Returns '' if the arg is missing — handlers decide whether that's fatal.

```php
function argString(array $args, string $key, array $payload): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's saved args |
| `$key` | `string` |  | the arg to read |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

### argInt

*private static* - line 2133

Resolve a single arg to an int, or null if blank / non-numeric. (Handler enforces required-ness if the arg matters.)

```php
function argInt(array $args, string $key, array $payload): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's saved args |
| `$key` | `string` |  | the arg to read |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

### argBool

*private static* - line 2154

Resolve a checkbox arg (`'type' => 'bool'`).

The editor stores it as a real JSON boolean, but a workflow saved before
the arg existed simply has no key at all — hence `$default`, which lets a
safety-shaped option like skip_if_linked default to ON for rules written
before it was added. Strings are tolerated because hand-edited JSON and
older saves can carry "0"/"false".

```php
function argBool(array $args, string $key, array $payload, bool $default = false): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's saved args |
| `$key` | `string` |  | the arg to read |
| `$payload` | `array` |  | the run's payload (not used - a checkbox holds no merge codes) |
| `$default` | `bool` | `false` | the answer when the arg is missing, null or '' |

### action_attach_checklist

*private static* - line 2173

attach_checklist: copies each chosen checklist template, with its items, onto the ticket, skipping templates already attached. No templates chosen is a skip, not an error.

```php
function action_attach_checklist(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: ticket_id, template_ids (array or comma-separated) |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

**Writes:** ticket_checklists, ticket_checklist_items - the attached copies

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2194 | `WorkflowEngine::action_attach_checklist` | read [ticket_checklists](/code/tables/ticket_checklists.md) |  |
| 2201 | `WorkflowEngine::action_attach_checklist` | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 2208 | `WorkflowEngine::action_attach_checklist` | **insert** [ticket_checklists](/code/tables/ticket_checklists.md) (`ticket_id`, `template_id`, `title`, `closure_mode`, `created_by_id`, `created_datetime`) |  |
| 2213 | `WorkflowEngine::action_attach_checklist` | read [checklist_template_items](/code/tables/checklist_template_items.md) |  |
| 2217 | `WorkflowEngine::action_attach_checklist` | **insert** [ticket_checklist_items](/code/tables/ticket_checklist_items.md) (`ticket_checklist_id`, `title`, `suggested_role`, `is_mandatory`, `requires_input`, `input_placeholder`, `sort_order`) |  |

### action_log_message

*private static* - line 2246

log_message: puts the rendered message into the run's step log and changes nothing else.

```php
function action_log_message(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: message |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

### action_set_ticket_status

*private static* - line 2264

set_ticket_status: writes the ticket's status directly, stamping or clearing closed_datetime.

Closing applies the same gates as an analyst closing it - mandatory SOP
steps and mandatory fields - and the action fails when they block.

```php
function action_set_ticket_status(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: ticket_id, status_id |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

**Writes:** tickets(status_id, closed_datetime, updated_datetime)

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2271 | `WorkflowEngine::action_set_ticket_status` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 2282 | `WorkflowEngine::action_set_ticket_status` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 2313 | `WorkflowEngine::action_set_ticket_status` | **update** [tickets](/code/tables/tickets.md) |  |

### action_set_ticket_priority

*private static* - line 2331

set_ticket_priority: sets the ticket's priority after checking the priority id exists.

```php
function action_set_ticket_priority(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: ticket_id, priority_id |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2338 | `WorkflowEngine::action_set_ticket_priority` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 2342 | `WorkflowEngine::action_set_ticket_priority` | **update** [tickets](/code/tables/tickets.md) (`priority_id`, `updated_datetime`) |  |

### action_assign_ticket

*private static* - line 2353

assign_ticket: sets both the assigned analyst and the owner of the ticket to one analyst, so the two stay in step.

```php
function action_assign_ticket(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: ticket_id, analyst_id |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2360 | `WorkflowEngine::action_assign_ticket` | read [analysts](/code/tables/analysts.md) |  |
| 2367 | `WorkflowEngine::action_assign_ticket` | **update** [tickets](/code/tables/tickets.md) (`assigned_analyst_id`, `owner_id`, `updated_datetime`) |  |

### action_add_ticket_note

*private static* - line 2380

add_ticket_note: adds the rendered note to the ticket's audit trail as a "Workflow Note" with no analyst.

```php
function action_add_ticket_note(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: ticket_id, note |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

**Writes:** ticket_audit

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2392 | `WorkflowEngine::action_add_ticket_note` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

### action_send_email

*private static* - line 2411

send_email: sends an email from the ticket's mailbox or a chosen one, through Microsoft Graph, Gmail or SMTP depending on the mailbox.

The recipient defaults to the ticket's requester. With a ticket the
subject carries the SDREF tag and the body the reply marker, and the sent
copy is filed on the ticket; without one it is a plain styled email. A
confidential ticket's email to anyone but its requester or an analyst is
skipped. Every send, or failed send, is written to the email log.

```php
function action_send_email(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: ticket_id or mailbox_id, subject, body, to (blank = the requester) |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} and the ticket's confidentiality |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2469 | `WorkflowEngine::action_send_email` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

### plainEmailBody

*private static* - line 2567

The house email styling, without the reply marker.

Mirrors `buildTemplateEmailBody()`'s wrapper so a standalone message looks
like every other email FreeITSM sends; it just omits the trailer that only
means something when there is a ticket to reply onto.

```php
function plainEmailBody(string $bodyContent): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bodyContent` | `string` |  | the email body; plain text is escaped and its line breaks kept, HTML is used as it is |

### action_create_task

*private static* - line 2586

create_task: creates a task, optionally linked to a ticket, using the default status and priority when none is chosen and placing it at the end of its board column.

```php
function action_create_task(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: title, description, status_id, priority_id, assignee_id, ticket_id |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

**Writes:** tasks

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2600 | `WorkflowEngine::action_create_task` | read [task_statuses](/code/tables/task_statuses.md) |  |
| 2601 | `WorkflowEngine::action_create_task` | read [task_statuses](/code/tables/task_statuses.md) |  |
| 2605 | `WorkflowEngine::action_create_task` | read [task_priorities](/code/tables/task_priorities.md) |  |
| 2612 | `WorkflowEngine::action_create_task` | read [tasks](/code/tables/tasks.md) |  |
| 2617 | `WorkflowEngine::action_create_task` | **insert** [tasks](/code/tables/tasks.md) (`title`, `description`, `status_id`, `priority_id`, `assigned_analyst_id`, `ticket_id`, `board_position`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |

### action_create_project

*private static* - line 2648

A project proposal (3.3.0) - the rules are includes/projects/intake.php's.

```php
function action_create_project(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: name, summary, business_case, estimated_cost, estimated_benefit, target_end_date, methodology, priority, owner_analyst_id |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} and passed on to `projectCreateFromAction()` |

### action_create_ticket

*private static* - line 2673

create_ticket: raises a new ticket.

When the event carries a form submission the job goes to the shared
form-to-ticket path (catalogueRaiseTicketFromSubmissionId), with the
configured args as overrides. Otherwise it finds or creates the requester
by email, numbers the ticket with TicketNumbering, applies the
confidential defaults and adds the opening message and a "Ticket Created"
audit row, all in one transaction.

```php
function action_create_ticket(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: subject, body, priority_id, department_id, type_id, assigned_team_id, assigned_analyst_id, from_email, from_name |
| `$payload` | `array` |  | the run's payload; a submission.id sends the job down the form-to-ticket path |

**Writes:** tickets, emails, ticket_audit, users - a requester row for a new email address

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2726 | `WorkflowEngine::action_create_ticket` | read [users](/code/tables/users.md) |  |
| 2730 | `WorkflowEngine::action_create_ticket` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `created_at`) |  |
| 2740 | `WorkflowEngine::action_create_ticket` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 2750 | `WorkflowEngine::action_create_ticket` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_team_id`, `assigned_analyst_id`, `user_id`, `created_datetime`, `updated_datetime`) |  |
| 2772 | `WorkflowEngine::action_create_ticket` | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance`, `is_read`, `ticket_id`) |  |
| 2786 | `WorkflowEngine::action_create_ticket` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

### action_send_webhook

*private static* - line 2822

POST a payload to an external URL — the outbound-webhook action.

Two modes, chosen by the `preset` arg:
  - a chat preset (slack / teams / discord) wraps the rendered `message`
    in that platform's expected JSON shape, so the common case is
    one field to fill;
  - `custom` sends the rendered `body` verbatim (must be valid JSON), so
    any target's exact payload can be produced with {{template}} vars.

If a signing `secret` is set, the raw body is HMAC-SHA256 signed and the
hex digest sent as X-FreeITSM-Signature so the receiver can verify the
call really came from this install. Delivery is synchronous with a short
timeout (a dead URL fails fast rather than hanging the host request); a
non-2xx response or transport error marks the step failed and is visible
in the execution log. An async delivery queue with retries is planned.

```php
function action_send_webhook(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: preset, url and secret (stored encrypted), message, body |
| `$payload` | `array` |  | the run's payload; cut down first for a confidential ticket |

### action_escalate_to_tracker

*private static* - line 2896

Raise an issue in an external tracker from this ticket.

⚠️ Everything that decides whether this is SAFE lives in
`integrationsEscalate()`, not here — most importantly the company guard that
refuses to escalate one client's ticket into another client's tracker.
This handler deliberately does no checking of its own: a second copy of
that logic is a second copy to get wrong, and a workflow's args are
editable by anyone who can author workflows.

Dry run never reaches this method — `runInner()` short-circuits before
`executeAction()`, so a workflow test cannot mint a real issue.

```php
function action_escalate_to_tracker(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: ticket_id, connection_id, project, issue_type, summary, body, skip_if_linked |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} and the default description |

### action_send_note_to_tracker

*private static* - line 2982

Post a comment onto the issue this ticket is already linked to.

Does nothing (rather than failing) when there is no link: a rule like "when
the status changes, tell the dev team" will fire on plenty of tickets that
were never escalated, and that is not an error.

```php
function action_send_note_to_tracker(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the action's args: ticket_id, note |
| `$payload` | `array` |  | the run's payload, for the {{merge codes}} |

### buildWebhookRequest

*public static* - line 3042

Build the outbound-webhook HTTP request (url, headers incl. any HMAC signature, and the rendered/validated JSON body) from the action's args and the trigger payload. Shared by the live action (action_send_webhook, which then enqueues the result) and the editor's "Send test" preview endpoint (which sends it synchronously) — so the test can never drift from a real send. Throws on any validation failure (bad url, empty message, invalid JSON).

The optional HMAC-SHA256 signature is computed here so the secret itself
is never stored — only the resulting X-FreeITSM-Signature header travels
on (retries reuse it: same body → same signature).

```php
function buildWebhookRequest(array $args, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$args` | `array` |  | the send_webhook args with url and secret already decrypted: preset, url, secret, message, body |
| `$payload` | `array` |  | the run's payload (with <key>.full for the Full record format) |

**Returns:** array{preset:string,url:string,body:string,headers:array<int,string>,signed:bool}

**Called from:** [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)

### enrichWithFullObjects

*public static* - line 3110

Enrich a trigger payload with the FULL, API-shaped version of its primary object, reusing the REST API serialisers so a webhook can emit exactly the JSON that GET /<resource>/{id} returns — rich, consistent and already typed in the OpenAPI spec. The full object is attached under <key>.full, so a Custom body can embed the whole record with {{ticket.full}} (the template engine json_encodes arrays), and the "Full record" preset sends it wholesale.

Extend `$registry` to cover more trigger object types — one entry per resource
is the single, typed source of truth for its shape. Best-effort: a load
failure is swallowed so the webhook still sends (just without .full).

```php
function enrichWithFullObjects(PDO $conn, array $payload): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$payload` | `array` |  | the event payload; each object `fullObjectLoaders()` knows gains a .full |

### fullObjectLoaders

*private static* - line 3140

The registry of full-object loaders, keyed by payload key. Each loader takes an id and returns the API-shaped record (or null if gone). Built per-call (not a static, so it can hold closures) — cheap, since it only runs when a webhook actually needs a full object. One entry per resource whose GET /{id} is a clean select+serialise; add a resource here to light up its `.full`.

```php
function fullObjectLoaders(PDO $conn): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

### dotGet

*private static* - line 3178

Dotted-path getter — `dotGet(['ticket' => ['id' => 5]], 'ticket.id')` → 5.

```php
function dotGet(array $haystack, string $path)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$haystack` | `array` |  | the array to read |
| `$path` | `string` |  | the dotted path, e.g. 'ticket.id'; '' or a missing level gives null |

### decodeJsonField

*private static* - line 3198

A JSON column (a workflow's conditions or actions) decoded to an array; [] when empty or not valid JSON.

```php
function decodeJsonField($raw): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` |  |  | the stored JSON text, or null |

