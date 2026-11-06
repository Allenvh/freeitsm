# system/integrations/help_slack.php

> PHP - 265 lines

The Slack half of system/integrations/help.php.

Kept in its own file because Slack's setup shares nothing with a tracker's —
there is no base URL, no API token and no project. Inlining a second set of
sections would have made help.php a page of two unrelated halves separated by
an if.

⚠️ Written for somebody who has never used Slack. That is not a hypothetical:
the first person to set this up had never opened it, and the original steps
began at "go to api.slack.com/apps", which assumes an account, a workspace and
knowing what an app is. Step 2 exists because of that.

Included by help.php inside .help-content; the enclosing markup is that file's.

## How it hangs together

**Included by:** [system/integrations/help.php](/code/files/system/integrations/help.php.md)

