# api/projects/assistant_chat.php

> API endpoint - 69 lines

Projects - the AI project assistant (3.3.0). Every rule is in includes/projects/assistant_chat.php; this only routes.

GET  ?project_id=N        the conversation (this person's, or the project's shared
                          one), whether AI is set up, whether they may change the
                          project, and where the project is in its set-up
POST {action, project_id, ...}
     open                 opening the panel: a greeting, a "since we last spoke", or nothing
     send   {text}        a message; the answer may carry proposals
     apply  {message_id, items:[i,...]}    apply the ticked proposals, as the caller
     dismiss {message_id, items:[i,...]}
     clear                start again

## The endpoint

| | |
|---|---|
| URL | `api/projects/assistant_chat.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php); Signed-in analyst (session check) (via includes/i18n.php) |
| Reads | `GET project_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectChatAiFail()](/code/functions/projectChatAiFail.md) | 26 | A provider problem in words the panel can show (as reports.php). |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [projectAiReady()](/code/functions/projectAiReady.md), [projectApiBody()](/code/functions/projectApiBody.md), [projectApiFail()](/code/functions/projectApiFail.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectCanChange()](/code/functions/projectCanChange.md), [projectChatApply()](/code/functions/projectChatApply.md), [projectChatClear()](/code/functions/projectChatClear.md), [projectChatMaturity()](/code/functions/projectChatMaturity.md), [projectChatMessages()](/code/functions/projectChatMessages.md), [projectChatOpen()](/code/functions/projectChatOpen.md), [projectChatReady()](/code/functions/projectChatReady.md), [projectChatThread()](/code/functions/projectChatThread.md), [projectChatTurn()](/code/functions/projectChatTurn.md), [projectSetting()](/code/functions/projectSetting.md)

