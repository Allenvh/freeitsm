# includes/public_url.php

> PHP - 172 lines

Where is this installation reachable from, seen from outside?

WHY THIS EXISTS AT ALL
----------------------
BASE_URL answers "where does the app live under the web root" and nothing more.
It is a path — `/freeitsm-app/` — which is the right and sufficient answer for
every link on a page FreeITSM itself renders, because the browser already knows
the host.

It is the wrong answer for a link FreeITSM *sends somewhere else*: an email, a
Jira issue, a Slack message. There the reader's browser resolves the address
against whatever host they happen to be on, and a bare path 404s.

THE CASE THIS IS REALLY FOR: THERE IS NO REQUEST
------------------------------------------------
The obvious fix — read the host off the current request — works for the analyst
who clicked something, and fails for everything unattended. Mail collected by
cron, an SLA breach notification, a workflow escalation: no request exists, so
`$_SERVER`['HTTP_HOST'] is simply absent. **Unattended is the normal case for
outbound mail, not the exception**, which is why an explicitly configured
address has to come first and cannot merely be a fallback.

WHY IT MOVED HERE
-----------------
This logic was written for issue trackers and lived in integrations.php, where
`integrationsAbsoluteUrl()` still is — it now calls this. The [ticket_url] merge
code (discussion #80) needs precisely the same answer, and a second copy of
"how do I address this install" is exactly the kind of duplication that drifts:
one of them would get the reverse-proxy handling and the other would not.
`includes/csat.php` and `includes/self_service_email.php` each still build their
own; they are older, they only ever run in request context, and folding them in
is a separate job with its own testing.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [publicBaseUrlSetting()](/code/functions/publicBaseUrlSetting.md) | 50 | The address an administrator has explicitly configured, or '' if none. |
| [publicUrlWithAppPath()](/code/functions/publicUrlWithAppPath.md) | 92 | Make sure the configured address carries the app's sub-path exactly once. |
| [publicBaseUrlForget()](/code/functions/publicBaseUrlForget.md) | 111 | Forget the cached root - after the setting is saved, or between test cases. |
| [publicBaseUrl()](/code/functions/publicBaseUrl.md) | 126 | The root every outbound link should be built on, without a trailing slash. |
| [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md) | 168 | An absolute URL for an app-relative path, e.g. 'self-service/tickets.php?id=9'. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 | `publicBaseUrlSetting` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/session_security.php](/code/files/includes/session_security.php.md)

**Included by:** [api/domains/settings.php](/code/files/api/domains/settings.php.md), [api/lms/tests.php](/code/files/api/lms/tests.php.md), [api/tickets/get_public_base_url.php](/code/files/api/tickets/get_public_base_url.php.md), [asset-management/labels.php](/code/files/asset-management/labels.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), [includes/csrf.php](/code/files/includes/csrf.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [includes/managers.php](/code/files/includes/managers.php.md), [includes/notifications_router.php](/code/files/includes/notifications_router.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md)

**Calls:** [requestIsHttps()](/code/functions/requestIsHttps.md)

