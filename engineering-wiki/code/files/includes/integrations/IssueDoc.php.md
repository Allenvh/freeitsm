# includes/integrations/IssueDoc.php

> PHP - 403 lines

IssueDoc — one description/comment, written once, rendered per provider.

The problem this solves: every issue tracker wants a different body format.
Jira Cloud's v3 API wants ADF (Atlassian Document Format — a nested JSON
document). Jira Data Center's v2 API wants wiki markup. GitHub and GitLab want
Markdown. Azure DevOps wants HTML. If core built a string, every one of those
would need its own string-building code and they would drift.

So core never builds a string. It builds an IssueDoc — an ordered list of
blocks — and the connector asks for the shape it needs:

    `$doc` = (new IssueDoc)
        ->heading('Raised in FreeITSM')
        ->para('Ticket ', IssueDoc::link(`$url`, 'SD-1042'), ' · Jane Doe (Acme)')
        ->rule()
        ->para(`$ticket`['description']);

    `$doc`->toAdf();        // Jira Cloud   (array, JSON-encode it)
    `$doc`->toWikiMarkup(); // Jira DC
    `$doc`->toMarkdown();   // GitHub / GitLab
    `$doc`->toHtml();       // Azure DevOps

The renderers live HERE rather than in each connector on purpose: a new
provider almost always reuses one of these four, so adding GitHub is
"renderDoc() returns `$doc`->toMarkdown()" and not a new renderer. That is what
makes the connector-per-provider cost small.

Deliberately small — heading, paragraph, link, bullet list, code block, rule.
This is not a rich-text format. It is enough to make a readable issue and no
more; anything bigger becomes a second sanitiser to keep safe.

⚠️ Text is always treated as PLAIN TEXT and escaped for the target format.
Never pass HTML in and expect it to survive — pass the text, use the blocks.

## Classes

- [IssueDoc](/code/classes/IssueDoc.md) (class, line 38) - IssueDoc — one description/comment, written once, rendered per provider.

## How it hangs together

**Included by:** [includes/integrations/AzureDevOpsProvider.php](/code/files/includes/integrations/AzureDevOpsProvider.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [includes/integrations/JiraProvider.php](/code/files/includes/integrations/JiraProvider.php.md)

