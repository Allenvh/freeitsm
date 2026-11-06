# workflow/includes/templates.php

> PHP - 851 lines

Workflow starter templates — the "recipe library".

A template is a workflow definition with the install-specific bits left
abstract, so the same recipe can be cloned into any FreeITSM install.

Two abstraction markers may appear anywhere an action arg or a condition
value would normally hold a literal:

  ['`$lookup`' => 'ticket_priority', 'names' => ['P1', 'Critical']]
      Resolved at clone time against this install's own lookup table, by
      label, case-insensitively, trying each candidate name in order.
      Installs rename their statuses and priorities freely, so a recipe
      can never hardcode `status_id: 5` — it says "whatever this install
      calls Closed" and we look it up.

  ['`$configure`' => 'Your Slack incoming-webhook URL']
      A value only the user can supply (a webhook URL, an email address).

Either marker that can't be turned into a real value is cloned as an EMPTY
arg and reported back in `unresolved`, which the editor turns into a
"these fields still need you" banner. A recipe therefore always clones —
it just tells you honestly what it couldn't fill in.

Cloned workflows are always INACTIVE. Nothing a recipe creates can fire
until the user has read it and switched it on.

Node positions (x/y) are deliberately omitted: the canvas editor
auto-lays-out any workflow whose nodes lack coordinates, so recipes stay
readable here and still land tidy on the canvas.

## Classes

- [WorkflowTemplates](/code/classes/WorkflowTemplates.md) (class, line 36)

## How it hangs together

**Includes:** [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Included by:** [api/workflow/create_from_template.php](/code/files/api/workflow/create_from_template.php.md), [api/workflow/templates.php](/code/files/api/workflow/templates.php.md)

