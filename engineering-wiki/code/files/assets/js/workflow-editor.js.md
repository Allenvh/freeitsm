# assets/js/workflow-editor.js

> JavaScript - 1912 lines

Workflow Editor — visual canvas builder.

Stage 2 of the Workflows module: replaces the form-based editor with a
Process Mapper-style dot-grid canvas where each node represents a single
piece of the rule. Layout is the UI, but the engine's data shape is
unchanged — at save time we sort condition + action nodes by y position
and write them in order.

Node kinds:
  - trigger    (singleton, pinned at top by default)
  - condition  (zero or more, drawn as diamonds)
  - action     (one or more, drawn as rounded rectangles)

Connectors are auto-routed at render time based on the y-sorted order
trigger -> condition[0..n-1] -> action[0..m-1]. They aren't stored.

The trigger event itself lives on the workflow row (trigger_event column);
the trigger node just visualises it. Each condition / action node stores
its x, y on its JSON object — the engine ignores the extra keys.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `WFE(()` | 22 | Workflow Editor — visual canvas builder. |
| `snap(v)` | 26 | Rounds a canvas coordinate to the nearest grid line (GRID pixels). |
| `init()` | 50 | Init |
| `setRunButtonsEnabled(on)` | 85 | Test fire and Dry run both need a saved workflow (the engine loads it from the database by id), so they enable and disable together. |
| `showTemplateGaps(id)` | 98 | A workflow just cloned from a starter template may carry args the template couldn't fill in for this install — a priority we don't have, a webhook URL only the user knows. The list is handed over in sessionStorage by the gallery; show it once, then clear it. |
| `escHtml(s)` | 111 | Escapes text for safe use in HTML. |
| `loadWorkflow(id)` | 126 | Loads a saved workflow from get.php and rebuilds the canvas - trigger, conditions and actions at their stored positions (or stacked when they have none) - plus the detail panel and the run history. |
| `addTriggerNode()` | 187 | Node model |
| `addConditionNode(x, y, field, op, value)` | 197 | Adds a condition node at a grid-snapped position and returns it (not rendered until renderAll). |
| `addActionNode(x, y, type, args)` | 209 | Adds an action node at a grid-snapped position and returns it (not rendered until renderAll). |
| `addCondition()` | 222 | Public — toolbar buttons. |
| `addAction()` | 232 | Toolbar: adds an action below the stack, of the first action type, seeded with that type's default args, and selects it. |
| `lowestNodeY(...kinds)` | 251 | Helper — find the lowest y of any node in the listed kinds (used to place a new node below the existing stack). |
| `deleteSelected()` | 263 | Removes the selected condition or action node (the trigger cannot be deleted) and marks the workflow unsaved. |
| `renderAll()` | 278 | Rendering — nodes + connectors |
| `createNodeEl(n)` | 291 | Builds the positioned DOM element for one node and wires its mousedown for drag and select. |
| `renderNodeContent(n)` | 308 | Returns the inner HTML of a node - the trigger's event, a condition's field, operator and value (lookup ids shown as labels), or an action's label with a one-line summary of its args. |
| `escHtml(s)` | 310 | Escapes text for safe use in HTML. |
| `labelFor(id)` | 332 | Turns a lookup id into its label for the condition preview, or returns the id when there is no lookup. |
| `lookupLabel(lookupKey, idVal)` | 362 | Turns an action arg's id into its label from WF_ACTION_LOOKUPS, or returns the id. |
| `renderConnectors()` | 399 | Connectors run trigger -> conditions (in y order) -> actions (in y order). No branching in v1 — purely linear visual of execution flow. |
| `ensureArrowheadDef()` | 413 | Adds the SVG arrowhead marker used by the connectors, once. |
| `drawConnector(a, b)` | 421 | Draws a straight arrow between two nodes, leaving from the edge of each box that faces the other. |
| `updateCanvasSize()` | 451 | Resize the canvas's intrinsic dimensions so all nodes fit and the SVG overlay stretches over the whole content area. |
| `updateSelectionVisuals()` | 464 | Toggles the 'selected' outline so only the selected node has it. |
| `bindCanvasEvents()` | 474 | Interaction — drag, select, keys |
| `onCanvasMouseDown(e)` | 487 | A click on bare canvas deselects and shows the workflow's own detail panel. |
| `onNodeMouseDown(e, n)` | 495 | Starts dragging a node; the detail panel opens on mouseup only if the node was not moved. |
| `onDocMouseMove(e)` | 519 | Moves the dragged node to the grid-snapped cursor position and redraws the connectors; the trigger node stays pinned. |
| `onDocMouseUp(e)` | 544 | Ends a drag - a moved node marks the workflow unsaved, a click without movement opens that node's detail panel. |
| `selectNode(id, deferDetail)` | 554 | Selection + detail panel |
| `selectWorkflow()` | 567 | Clears the node selection and shows the workflow's name, description and active switch in the detail panel. |
| `showBody(title, id)` | 574 | Sets the detail panel title and shows only the named body section. |
| `showDetailForTrigger()` | 582 | Shows the trigger detail with the current trigger event selected. |
| `showDetailForCondition(n)` | 590 | Shows the condition detail - the fields this trigger offers (keeping a stored field from another trigger as "custom"), the operators and the value control. |
| `rebuildOperatorDropdown(n)` | 627 | The operator dropdown's contents depend on whether the field is a normalised lookup (priority_id, status_id, etc.) or free-text. |
| `renderConditionValueInput(n)` | 690 | The value control adapts to (a) whether the chosen field has a lookup (i.e. a normalised id like `ticket.priority_id`) and (b) the operator. |
| `onConditionMultiToggle()` | 742 | When a multi-select checkbox toggles, gather every checked value and store as an array on the condition node. Re-render the canvas node so its preview reflects the new list. |
| `showDetailForAction(n)` | 752 | Shows the action detail - its type, description and the per-arg form. |
| `renderActionArgsForm(n, def)` | 768 | Render the per-action form into #wfActArgsHost based on the action's args spec in WF_ACTION_DEFS. Replaces the old single JSON textarea — users now get a labelled control per arg (text input / textarea / number / checkbox / lookup dropdown). Free-text args that support variables show a small `{{ticket.id}}` hint underneath. |
| `renderList(query = '')` | 844 | Lists the checklist templates matching the search (scored by scoreChecklistTemplate when loaded), best match then ticked then A-Z; ticking updates the node's template_ids. |
| `refreshWarn()` | 956 | Updates the unknown merge-code warning under this field. |
| `applyFormatHints(host, n)` | 989 | Two things the message-format registry gives us, shown where they're needed rather than in a help page you'd have to know to read: |
| `check()` | 1014 | Warns under the webhook URL when it does not match the chosen format's url_pattern, naming the platform it does look like when one matches. |
| `appendWebhookTest(host, n)` | 1050 | Append the "Send test" control + result panel for the webhook action. Sends n.args (the live config) to webhook_test.php, which renders the templates against a sample payload and delivers synchronously. |
| `runWebhookTest(n, btn, out)` | 1068 | Sends the action's current webhook config to webhook_test.php with sample data and shows what the endpoint returned; nothing is saved. |
| `wfEsc(s)` | 1098 | Local HTML escaper (escHtml elsewhere is function-scoped, not shared). |
| `currentVars()` | 1114 | Returns the merge codes ({path, label}) the current trigger can resolve. |
| `currentVarPrefixes()` | 1119 | Open-ended prefixes, e.g. submission.fields.<the form author's label>. |
| `varLabel(path)` | 1124 | Returns the human label for a merge-code path, or null when the trigger does not know it. |
| `isKnownVar(path)` | 1130 | True when the current trigger can resolve a merge-code path, exactly or under one of its open-ended prefixes. |
| `unknownVarsIn(text)` | 1136 | Every {{code}} in the text that this trigger cannot resolve. |
| `renderVarWarning(el, text)` | 1147 | Shows (or hides) the warning listing the {{codes}} in the text that the current trigger cannot resolve. |
| `openVarModal(argName, spec, ctrl)` | 1163 | Opens the large merge-code editor for one action arg, with the codes this trigger offers and a live preview. |
| `closeVarModal()` | 1181 | Closes the merge-code editor without applying it. |
| `applyVarModal()` | 1189 | Copies the merge-code editor's text back into the arg's control (newlines collapsed for a single-line arg) and closes it. |
| `renderVarList(query)` | 1202 | Lists the current trigger's merge codes matching the search, each button inserting its code at the cursor. |
| `insertVar(path)` | 1225 | Insert {{path}} at the cursor (replacing any selection). |
| `refreshVarModal()` | 1243 | Live feedback inside the modal: the unknown-code warning, plus a preview that swaps each code for its human label. We deliberately do NOT invent sample data — showing a fake ticket subject would be a lie about what the message will say. Showing the shape is honest and still useful. |
| `webhookTestError(msg, heading)` | 1269 | Returns the red error box HTML for a failed webhook test. |
| `renderDiagnosis(dg)` | 1280 | The plain-English explanation of a transport failure, plus a link to the fix where one exists. Shared shape with the delivery log in System → Webhooks (both get it from `webhookDiagnoseError()` server-side). |
| `renderWebhookTestResult(d)` | 1295 | Returns the HTML for a webhook test result - delivered or not, HTTP status and timing, any diagnosis, and the request and response bodies. |
| `applyArgVisibility(host, n)` | 1328 | Show/hide fields whose `show_when` condition (another arg's current value) isn't met — e.g. the webhook Message field only for the chat presets, the raw JSON body only for Custom. |
| `updateActionArgFromControl(argName, type, ctrl)` | 1345 | Writes one arg control's value into the selected action node (an empty text value removes the arg), then refreshes the node, conditional fields and webhook hints. |
| `bindWorkflowDetailEvents()` | 1366 | Wires the workflow name, description and active switch so edits update the workflow and mark it unsaved. |
| `updateTriggerFromDetail()` | 1381 | Detail-panel handlers exposed via WFE.* for inline onchange / oninput. |
| `updateConditionFromDetail()` | 1391 | Applies the condition detail panel to the selected node - a new field rebuilds the operators, and a new field or operator reshapes the value to suit. |
| `updateActionTypeFromDetail()` | 1426 | Changes the selected action's type, replacing its args with the new type's defaults and redrawing the form. |
| `markDirty()` | 1448 | Dirty flag + status pip |
| `setStatus(state)` | 1453 | Shows the save status pip - idle, unsaved, saving or saved. |
| `save()` | 1470 | Save / Test fire |
| `buildTestFirePayload()` | 1538 | Build a synthetic payload from the workflow's own conditions so Test fire actually exercises the action path. For each condition with an equality-ish op we set `payload[field] = value` (or the first item of a list), so the condition trivially passes. Other ops (is_empty / is_not_empty / contains / gt / lt) get a sensible sentinel. The user wants Test fire to mean "do my actions run when conditions pass" — an empty payload would skip every workflow that has any condition. |
| `setPath(path, value)` | 1542 | Sets a dotted path (ticket.priority_id) inside the test payload, creating the objects on the way. |
| `dryRun()` | 1590 | Dry run — evaluate the conditions for real, but describe the actions instead of executing them. Nothing is written, emailed or queued, so this is safe to point at a live workflow. |
| `testFire(isDryRun)` | 1597 | Runs the saved workflow through fire.php with a test payload - for real, or as a dry run that only describes the actions - then reloads the run history. |
| `renderExecutions(execs)` | 1627 | Lists recent runs with their status pill, start time, any error, and a dry run's would-have-done steps. |
| `escHtml(s)` | 1635 | Escapes text for safe use in HTML. |
| `fmtDate(s)` | 1641 | Formats a run's UTC start time in the analyst's zone, or returns it unchanged if formatting fails. |
| `dryStepsHtml(e)` | 1650 | A dry run's whole value is in its step log — "here is what it WOULD have done" — so unlike a real run we render the steps inline rather than just a status pill. |
| `escAttr(s)` | 1688 | Helpers |
| `openAiModal()` | 1708 | Opens the AI co-author modal reset to an empty prompt; the "iterate" hint shows only when the canvas already has conditions or actions. |
| `closeAiModal()` | 1725 | Closes the AI co-author modal. |
| `aiGenerate()` | 1731 | Sends the prompt and the current workflow (without positions) to ai_compose.php and shows the proposal. |
| `collectWorkflowForApi(dropPositions)` | 1766 | Build a payload that mirrors what save.php expects, with optional `dropPositions` to omit the x/y the canvas tracks — the AI doesn't need to see those. |
| `renderAiResult(d)` | 1792 | Shows the AI proposal - explanation, a compact trigger/condition/action preview and any validation warnings - with Apply and Discard. |
| `escHtml(s)` | 1794 | Escapes text for safe use in HTML. |
| `aiDiscard()` | 1838 | Throws away the AI proposal and returns the modal to the prompt. |
| `aiApply()` | 1851 | Replaces the canvas with the AI proposal, keeping the workflow's id and active flag, stacks the nodes and marks it unsaved. |

## Loaded by

- [workflow/editor.php](/code/files/workflow/editor.php.md)

