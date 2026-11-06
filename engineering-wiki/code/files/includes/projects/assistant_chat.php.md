# includes/projects/assistant_chat.php

> PHP - 663 lines

Projects - the AI project assistant (3.3.0): "Ask AI" on a project.

A conversation with an AI project manager about ONE project, in a panel that
slides in from the right (assets/js/projects-assistant.js, endpoint
api/projects/assistant_chat.php). It reads the project live and can propose
changes; it never makes one.

─────────────────────────────────────────────────────────────────────────────
WHAT IT KNOWS
  - the project, fresh every turn: `projectAiFacts()` (includes/projects/ai.php),
    as the person asking - so only what they could see themselves;
  - where the project is in its set-up: `projectChatMaturity()` - a checklist of
    goal, dates, stages, tasks, people, scope, risks, milestones - which decides
    whether it COACHES (a project with a name and little else: what is it about,
    what does done look like, roughly when, who is involved - then a proposed
    set-up) or KEEPS YOU ON TOP (overdue work, high risks, missed milestones,
    waiting approvals);
  - the conversation: the last PROJECT_CHAT_RECENT messages word for word, and
    everything older folded into the thread's `summary` (projectChatSummarise())
    - the memory that means a resumed conversation does not start cold;
  - more detail on demand through READ tools (list_tasks, list_stages,
    list_raid, list_milestones, list_analysts, list_suppliers), which also give it the ids a
    proposal needs.

WHAT IT CAN DO - PROPOSE, NEVER CHANGE
  The propose_* tools record a change in the turn's `proposals` (project
  details, stages, tasks, task dates, milestones, RAID entries, scope items,
  dependencies, budget lines, benefits, members, tools). The person sees each as
  a line on a card with Apply and Dismiss; `projectChatApply()` runs the ones they
  tick through the ordinary services AS THEM - so every permission rule, every
  validation and the project's History apply exactly as if they had used the
  screens. Somebody who may not change the project is told so, and the model is
  told not to propose.

MEMORY - whose conversation
  project_assistant_memory (Projects -> Settings -> AI): 'person' (default) - one
  private conversation per person per project; 'project' - one conversation the
  whole project shares (analyst_id NULL), so the team picks up where any of them
  left off. Start again clears it (a shared one only by somebody who may change
  the project).

OPENING THE PANEL
  `projectChatOpen()`: an empty conversation gets a greeting suited to the
  project's set-up (kind 'open'); one last used more than PROJECT_CHAT_RESUME_HOURS
  ago gets a "since we last spoke" catch-up (kind 'resume'); otherwise nothing -
  the history is the welcome. Each is one call to the provider.

TRAP: the project's own text (names, risk titles, notes) reaches the model as
DATA inside <project_data>; the system prompt says so. Tool results are data too.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectChatReady()](/code/functions/projectChatReady.md) | 66 | Have Database Verification's tables arrived? |
| [projectChatThread()](/code/functions/projectChatThread.md) | 81 | The conversation this person has on this project (per the memory setting); created on first use when `$create`. |
| [projectChatMessages()](/code/functions/projectChatMessages.md) | 100 | Every message of a thread, oldest first, shaped for the panel. |
| [projectChatMaturity()](/code/functions/projectChatMaturity.md) | 120 | How far the project is set up - a checklist the prompt is built around, and the panel's suggestion chips. stage: 'blank' (a name and little else), 'early', 'running'. |
| [projectChatSystem()](/code/functions/projectChatSystem.md) | 163 | The standing instructions, the set-up checklist, the memory and the project, for one turn. |
| [projectChatTools()](/code/functions/projectChatTools.md) | 198 | The tools the model may call. Read tools run at once; propose_* tools only record a proposal. |
| [projectChatRead()](/code/functions/projectChatRead.md) | 246 | Run a READ tool, as text. Never throws. |
| [projectChatDescribe()](/code/functions/projectChatDescribe.md) | 310 | One line a person can read for a proposal (shown on the card). |
| [projectChatTurn()](/code/functions/projectChatTurn.md) | 348 | One turn. `$mode`: 'chat' (the person wrote `$text`), 'open' (a greeting for an empty conversation) or 'resume' (a catch-up after a while). Stores the turn and returns the new messages. Throws RuntimeException for provider problems ('not_configured' when AI is not set up). |
| [projectChatOpen()](/code/functions/projectChatOpen.md) | 441 | Open the panel: a greeting for an empty conversation, a catch-up after a while, or nothing. |
| [projectChatSummarise()](/code/functions/projectChatSummarise.md) | 462 | The memory: once more than PROJECT_CHAT_SUMMARISE_AT messages are not yet in the summary, everything but the latest PROJECT_CHAT_RECENT is folded into it - what was decided, what the user told it about the project, what is still open. Quiet: a failure leaves the conversation exactly as it was. |
| [projectChatApply()](/code/functions/projectChatApply.md) | 503 | Apply proposals of one assistant message - the indexes the person ticked - through the ordinary services, AS THEM. Stages before tasks, tasks before dependencies, so a proposal can name a stage or task proposed alongside it. Returns each item's result. |
| [projectChatApplyOne()](/code/functions/projectChatApplyOne.md) | 541 | One proposal, through the service a person would have used. |
| [projectChatClear()](/code/functions/projectChatClear.md) | 654 | Start again: the conversation and its memory go. A shared one needs somebody who may change the project. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 | `projectChatReady` | read [project_ai_messages](/code/tables/project_ai_messages.md) |  |
| 70 | `projectChatReady` | read [project_ai_threads](/code/tables/project_ai_threads.md) |  |
| 85 | `projectChatThread` | read [project_ai_threads](/code/tables/project_ai_threads.md) |  |
| 86 | `projectChatThread` | read [project_ai_threads](/code/tables/project_ai_threads.md) |  |
| 90 | `projectChatThread` | **insert** [project_ai_threads](/code/tables/project_ai_threads.md) (`project_id`, `analyst_id`, `created_datetime`, `updated_datetime`) |  |
| 102 | `projectChatMessages` | read [project_ai_messages](/code/tables/project_ai_messages.md), read [analysts](/code/tables/analysts.md) |  |
| 124 | `projectChatMaturity` | read [tasks](/code/tables/tasks.md) |  |
| 125 | `projectChatMaturity` | read [tasks](/code/tables/tasks.md) |  |
| 126 | `projectChatMaturity` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 127 | `projectChatMaturity` | read [project_stages](/code/tables/project_stages.md) |  |
| 128 | `projectChatMaturity` | read [project_members](/code/tables/project_members.md) |  |
| 129 | `projectChatMaturity` | read [tasks](/code/tables/tasks.md) |  |
| 130 | `projectChatMaturity` | read [project_raid](/code/tables/project_raid.md) |  |
| 131 | `projectChatMaturity` | read [project_raid](/code/tables/project_raid.md) |  |
| 132 | `projectChatMaturity` | read [project_items](/code/tables/project_items.md) |  |
| 133 | `projectChatMaturity` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 134 | `projectChatMaturity` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 135 | `projectChatMaturity` | read [project_change_requests](/code/tables/project_change_requests.md) |  |
| 165 | `projectChatSystem` | read [analysts](/code/tables/analysts.md) |  |
| 254 | `projectChatRead` | read [suppliers](/code/tables/suppliers.md) |  |
| 255 | `projectChatRead` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [project_stages](/code/tables/project_stages.md), read [analysts](/code/tables/analysts.md) |  |
| 265 | `projectChatRead` | read [project_stages](/code/tables/project_stages.md) |  |
| 271 | `projectChatRead` | read [project_raid](/code/tables/project_raid.md), read [analysts](/code/tables/analysts.md) |  |
| 278 | `projectChatRead` | read [project_milestones](/code/tables/project_milestones.md), read [project_stages](/code/tables/project_stages.md) |  |
| 293 | `projectChatRead` | read [analysts](/code/tables/analysts.md) |  |
| 363 | `projectChatTurn` | read [project_ai_messages](/code/tables/project_ai_messages.md) |  |
| 392 | `projectChatTurn` | read [suppliers](/code/tables/suppliers.md) |  |
| 395 | `projectChatTurn` | read [contacts](/code/tables/contacts.md) |  |
| 400 | `projectChatTurn` | read [tasks](/code/tables/tasks.md) |  |
| 408 | `projectChatTurn` | read [tasks](/code/tables/tasks.md) |  |
| 408 | `projectChatTurn` | read [analysts](/code/tables/analysts.md) |  |
| 426 | `projectChatTurn` | **insert** [project_ai_messages](/code/tables/project_ai_messages.md) (`thread_id`, `role`, `kind`, `content`, `proposals`, `looked_at`, `analyst_id`, `model`, `tokens_in`, `tokens_out`, `created_datetime`) |  |
| 431 | `projectChatTurn` | **update** [project_ai_threads](/code/tables/project_ai_threads.md) (`updated_datetime`) |  |
| 445 | `projectChatOpen` | read [project_ai_messages](/code/tables/project_ai_messages.md) |  |
| 465 | `projectChatSummarise` | read [project_ai_threads](/code/tables/project_ai_threads.md) |  |
| 469 | `projectChatSummarise` | read [project_ai_messages](/code/tables/project_ai_messages.md) |  |
| 487 | `projectChatSummarise` | **update** [project_ai_threads](/code/tables/project_ai_threads.md) (`summary`, `summary_upto_id`) |  |
| 508 | `projectChatApply` | read [project_ai_messages](/code/tables/project_ai_messages.md) |  |
| 530 | `projectChatApply` | **update** [project_ai_messages](/code/tables/project_ai_messages.md) (`proposals`) |  |
| 546 | `projectChatApplyOne` | read [project_stages](/code/tables/project_stages.md) |  |
| 550 | `projectChatApplyOne` | read [project_stages](/code/tables/project_stages.md) |  |
| 557 | `projectChatApplyOne` | read [tasks](/code/tables/tasks.md) |  |
| 561 | `projectChatApplyOne` | read [tasks](/code/tables/tasks.md) |  |
| 637 | `projectChatApplyOne` | read [project_roles](/code/tables/project_roles.md) |  |
| 661 | `projectChatClear` | **delete** [project_ai_threads](/code/tables/project_ai_threads.md) |  |

## How it hangs together

**Includes:** [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Included by:** [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)

**Calls:** [aiProviderChat()](/code/functions/aiProviderChat.md), [aiProviderChatTools()](/code/functions/aiProviderChatTools.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [projectAiFacts()](/code/functions/projectAiFacts.md), [projectAiLanguage()](/code/functions/projectAiLanguage.md), [projectCanChange()](/code/functions/projectCanChange.md), [projectEnabledTools()](/code/functions/projectEnabledTools.md), [projectSetting()](/code/functions/projectSetting.md), [projectToolDefinitions()](/code/functions/projectToolDefinitions.md), [t()](/code/functions/t.md), [tasksContractorChoices()](/code/functions/tasksContractorChoices.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md), [tasksContractorValidate()](/code/functions/tasksContractorValidate.md), [tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md)

