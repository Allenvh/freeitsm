# Traps

Every `TRAP:` comment in the code - the things that will bite the next person, and why. Written by the people who were bitten.

## api/messaging/save_channel.php

- **Line 215:** only write channel_ref for a provider that owns it here. Other providers keep their own value in it (Slack's workspace id), and a save that blanked it for them would quietly break a working channel.

[Open save_channel.php](/code/files/api/messaging/save_channel.php.md)

## api/system/debug-tools/D018_ticket_folder_counts.php

- **Line 108:** only ever for the analyst who is signed in. Running it for somebody else would mean putting their id into the admin's live session, and a fatal error part-way through would leave the admin signed in as them.

[Open D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md)

## api/tickets/get_ticket_counts.php

- **Line 82:** never short-circuit an empty department list to 0 - count "t.department_id IS NULL". Every team member sees no-department tickets (the branch below says so with "OR t.department_id IS NULL"); returning 0 here hid ALL of them, and an install with no departments yet showed 0 everywhere once someone joined a team. Applies to the three other empty-list branches below too. tests/team-counts-no-departments.php
- **Line 366:** decide access with `getAccessibleTenantIds()`, never a copy of its rule in SQL. It unions the analyst's own grants with every team's; a second copy drifts.

[Open get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)

## assets/js/engineering-wiki.js

- **Line 86:** `renderAll()` rebuilds EVERY pane's DOM, so every pane is redrawn (from
- **Line 250:** not "target" - a var here is hoisted over the whole callback and

[Open engineering-wiki.js](/code/files/assets/js/engineering-wiki.js.md)

## assets/js/files-desktop.js

- **Line 1908:** a file from the computer dropped where nothing accepts it (a

[Open files-desktop.js](/code/files/assets/js/files-desktop.js.md)

## assets/js/files-viewer.js

- **Line 134:** elements inside <svg>/<math> report LOWER-case tag names, so
- **Line 312:** "fit" must be MEASURED when it is asked for. It was worked out once
- **Line 560:** SheetJS's reader adds a marker entry "\u0001Sh33tJ5"; no real

[Open files-viewer.js](/code/files/assets/js/files-viewer.js.md)

## assets/js/files-wm.js

- **Line 302:** a button released where we never hear about it (over the Help

[Open files-wm.js](/code/files/assets/js/files-wm.js.md)

## assets/js/inbox.js

- **Line 9457:** escape the WHOLE answer first, then add markup. The answer is model
- **Line 11241:** on a phone the strip picker opens inside the Links sheet, which

[Open inbox.js](/code/files/assets/js/inbox.js.md)

## assets/js/knowledge.js

- **Line 3440:** escape the WHOLE answer first, then add markup. The answer is model

[Open knowledge.js](/code/files/assets/js/knowledge.js.md)

## includes/catalogue_approvals.php

- **Line 301, `catalogueCreateTicketFromSubmission`:** the install's numbering, never a number made here - see `messagingGenerateTicketNumber()` for what went wrong when it was.

[Open catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md)

## includes/csat.php

- **Line 97:** an upgrade must not change who is asked, or how. `csat_in_channel` is OFF after an upgrade (db_verify seeds '0'; a new install's freeitsm.sql seeds '1'). An install that has emailed every survey for months must not start messaging its chat customers the day it upgrades.
- **Line 102:** match a chat reply only to a request sent IN THAT CHAT. channel_id + channel_from record where a request went. Never find "the pending survey" through the emails table alone - that also finds emailed surveys and other chats' requests.
- **Line 107:** no orphan rows. The row exists only to give the buttons an id; if the send fails it is deleted and the email survey is tried instead. With one-survey-per-ticket on, a stray row would mean the customer is never asked.
- **Line 111:** WhatsApp outside its 24-hour window cannot take a free-form message - `csatTicketChannel()` checks `channelWindowOpen()` and falls back to email.
- **Line 206:** a bare digit is usually NOT a rating. "Which floor?" "3" is an answer to the analyst, and a "2" next month starts a new conversation - loosen any of the three conditions and real messages vanish into a survey, with no error anywhere. tests/messaging-teams-mattermost.php checks each one.

[Open csat.php](/code/files/includes/csat.php.md)

## includes/db_verify_schema.php

- **Line 1434:** keep the column DEFAULT 0. Adding it gives every EXISTING link 0, so nothing attached before 3.7.0 goes public on upgrade; new article links are written with 1 by `documentLinkHelpCentreDefault()`.

[Open db_verify_schema.php](/code/files/includes/db_verify_schema.php.md)

## includes/documents.php

- **Line 785:** new links only. Never backfill existing rows to 1 - they were attached before anybody was told that publishes them (see the column's TRAP in includes/db_verify_schema.php).

[Open documents.php](/code/files/includes/documents.php.md)

## includes/engineering_wiki/scanner.php

- **Line 254, `EwScanner::parsePhp`:** the first doc block in a file is ALWAYS the file's description. It used to go to the next function/class instead whenever one came next, so files with a proper header showed no summary. It ALSO describes that declaration when nothing else sits between them (the common "one header, then the class" shape) - but not when another comment follows, because that comment is the declaration's own.
- **Line 694:** comment down to its text - comment markers stripped, from "TRAP:" to the end of its paragraph, on one line.
- **Line 1507:** comment, file by file.

[Open scanner.php](/code/files/includes/engineering_wiki/scanner.php.md)

## includes/hypervisor_http.php

- **Line 37:** keep this https-only. The credential travels with every request.

[Open hypervisor_http.php](/code/files/includes/hypervisor_http.php.md)

## includes/leases.php

- **Line 134:** a returned asset keeps lease_expiry as the record of what happened (see the header), so anything that alerts on lease_expiry must skip these, or a machine handed back in March is "due back" for ever. Call only when `leaseReady()`.

[Open leases.php](/code/files/includes/leases.php.md)

## includes/messaging/ingest.php

- **Line 302, `findOpenChannelTicket`:** a new channel type whose sender id is not unique per channel must be added here. Mattermost (PR #166) is one: a user id is the same in every configuration on one server, so two companies' support channels would share a person's thread - another company's ticket - without this.
- **Line 394:** only ever pass an address the PLATFORM vouches for - Slack's profile, Microsoft 365's directory, Mattermost's verified flag. The email is the whole of the identity here: an address someone merely typed would file their chats under whoever owns it. users.email is unique, so a match is one person, never a guess between two.
- **Line 1013:** never make a ticket number here, or anywhere else, by hand. This used to roll its own XXX-NNN-NNNNN, so on an install set to TICKET-{######} every chat ticket still came out as JNL-471-47705 (GH #71 moved three generators onto TicketNumbering and missed this one, plus catalogue approvals, merge and split). Pass the ticket's company: per-company numbering and {COMPANY} need it.

[Open ingest.php](/code/files/includes/messaging/ingest.php.md)

## includes/messaging/MattermostProvider.php

- **Line 63, `MattermostProvider::verifyWebhook`:** compare secrets with `hash_equals()`, never === (timing).
- **Line 116, `MattermostProvider::parseInbound`:** drop the bot's own posts. Our replies are posted in the same channel, so the webhook hands them straight back; without this every analyst reply would arrive as a new message from "the customer".
- **Line 263:** Mattermost refuses a root_id that is itself a reply, and the outgoing webhook never says which a post is - so ask, rather than threading under whatever post came in last.
- **Line 353:** the email is returned ONLY when Mattermost says it is verified. A server can allow sign-up without proving the address, and an unverified email is just text anyone typed: someone registering as ceo@yourcompany.com would have their chats filed under the real CEO. An empty email (the server hides addresses from this bot) is normal - the person simply gets a named contact instead.

[Open MattermostProvider.php](/code/files/includes/messaging/MattermostProvider.php.md)

## includes/messaging/SlackProvider.php

- **Line 302, `SlackProvider::downloadMedia`:** trap: HTML back means the token wasn't accepted.

[Open SlackProvider.php](/code/files/includes/messaging/SlackProvider.php.md)

## includes/messaging/TelegramProvider.php

- **Line 524, `TelegramProvider::setWebhook`:** this list is everything `parseInbound()` reads. Leave one out and Telegram never sends it - no error anywhere. A rating button press is a callback_query; without it the button just shimmers on the customer's phone. Telegram keeps the list a bot was registered with, so a bot connected before a type was added needs Connect pressed again - `testConnection()` checks for that (TELEGRAM_UPDATE_TYPES).

[Open TelegramProvider.php](/code/files/includes/messaging/TelegramProvider.php.md)

## includes/projects/ai.php

- **Line 198, `projectAiFacts`:** `projectAlertRows()` rows carry no '_budget' (only the assistant's rows do), so reading it from `$p` meant the budget never reached the model - worked out here instead.

[Open ai.php](/code/files/includes/projects/ai.php.md)

## includes/projects/benefits.php

- **Line 188, `projectAlertsBenefits`:** a review falling due is nobody's action. This scan also runs inside somebody's request (afterChange), and the bell never tells you about your own action - so it is dispatched as the system, or the project manager who happened to close the project would never hear that a review is due.

[Open benefits.php](/code/files/includes/projects/benefits.php.md)

## includes/projects/budget.php

- **Line 397, `projectEarnedValue`:** the share is measured against TODAY's whole scope, never the scope as it stood on that date. Measuring against "tasks that existed by then" made earned value FALL whenever work was added (6 of 10 done = 60%, add 10 tasks = 30%), and earned value never falls - the work done is still done. Today's figures are the same either way; only the history changes, and now it can only rise.

[Open budget.php](/code/files/includes/projects/budget.php.md)

## includes/projects/targets.php

- **Line 291, `projectTargetAssets`:** an asset with no status makes "status = 6" NULL, not false, and NOT(NULL) is NULL too - the asset would vanish from "still to do". The count's CASE WHEN already treats NULL as not done; this must agree.

[Open targets.php](/code/files/includes/projects/targets.php.md)

## includes/proxmox_sync.php

- **Line 148, `proxmoxTestConnection`:** proxmoxCall, not proxmoxTryGet - the real reason must reach the admin. With a token there is no separate login call, so the node list is the first request; swallowing its error turned "could not reach the server" into "check the user's permissions" (found at merge, by Testing a server that does not exist).
- **Line 245, `proxmoxSyncConnection`:** "gone" means missing from a LIST THAT CAME BACK - one node's qemu list or its lxc list - never merely "on a node that answered". The PR scoped removal by node, so a node that was online but whose qemu list failed (a timeout, a permission on that node) still counted as read, and every VM on it was deleted. The guard did not catch it either: it compared all VMs seen across the cluster with the known ones, so one small node failing in a big cluster stayed under the 50% line. The rows are upserted above first, so a VM that moved to another node is already recorded there before this looks.
- **Line 469:** the password field holds the API token SECRET (or the user's password). It is stored only through `encryptValue()`, never returned to the browser (proxmoxConnectionOut() says has_password and nothing more), and a blank or all-asterisks value on edit keeps the stored one.

[Open proxmox_sync.php](/code/files/includes/proxmox_sync.php.md)

## includes/services/project_templates.php

- **Line 49, `ProjectTemplatesService::createFromTemplate`:** read the date as UTC midnight. strtotime("2026-10-07") is LOCAL midnight, which `gmdate()` below turns into the day before whenever the server is ahead of UTC (British Summer Time) - every stage and task a day early.

[Open project_templates.php](/code/files/includes/services/project_templates.php.md)

## includes/ticket_merge.php

- **Line 449, `mergeCreateTargetTicket`:** the install's numbering, never a number made here - see `messagingGenerateTicketNumber()` for what went wrong when it was.

[Open ticket_merge.php](/code/files/includes/ticket_merge.php.md)

## includes/ticket_reply.php

- **Line 100, `reopenTicketForCustomerReply`:** leaving it set made a reopened ticket still count as closed everywhere that reads closed_datetime - reports, and the chat lookup, which would open a NEW ticket for the customer's next message rather than continue the one just reopened.

[Open ticket_reply.php](/code/files/includes/ticket_reply.php.md)

## includes/ticket_split.php

- **Line 263, `splitCreateTicket`:** the install's numbering, never a number made here - see `messagingGenerateTicketNumber()` for what went wrong when it was.

[Open ticket_split.php](/code/files/includes/ticket_split.php.md)

## includes/vcloud_sync.php

- **Line 236, `vcdSyncConnection`:** count what ARRIVED, never page x the size we asked for. The PR stopped when page * 128 >= total, but Director caps a page at its own maximum (restapi.queryservice.maxPageSize, which an admin can lower). On such a system page 1 came back short, the loop stopped, the list counted as complete - and every VM after the first page was deleted.
- **Line 288, `vcdSyncConnection`:** every page, and remove only after all of them came back. The PR read one page of 128 and then deleted every gateway not on it, so an install with more than 128 lost the rest on every sync.
- **Line 444:** the password is stored only through `encryptValue()`, never returned to the browser (vcdConnectionOut() says has_password and nothing more), and a blank or all-asterisks value on edit keeps the stored one.

[Open vcloud_sync.php](/code/files/includes/vcloud_sync.php.md)

## workflow/includes/engine.php

- **Line 2743, `WorkflowEngine::action_create_ticket`:** the install's numbering, never a number made here - see `messagingGenerateTicketNumber()` for what went wrong when it was. No company is stored on this ticket, which means the default one.

[Open engine.php](/code/files/workflow/includes/engine.php.md)

