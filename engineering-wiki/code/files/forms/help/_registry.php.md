# forms/help/_registry.php

> PHP - 95 lines

Forms help — the topic registry.

🔑 WHY FORMS HAS TOPIC PAGES AT ALL. `forms/help.php` is one page with eight
sections and it was already the longest help guide in the product. Six more
areas landed in 2.3.0 — layout, blocks, tables, drafts and who may request a
form — and adding them as six more sections would have made a page nobody
reads to the bottom of. Ed's words: "maybe it should have its own help pages
so the help screen doesn't turn into war and peace."

So the main guide keeps the basics and links here; each topic that needs real
explaining gets a page of its own.

The registry owns each topic's title, standfirst and section list. The
sections drive BOTH the page's own sidebar and the cards on forms/help.php,
so a topic cannot list a section it does not have, and the guide cannot link
to a topic that does not exist.

ADDING A TOPIC: add the entry here, then create <slug>.php which sets
`$helpTopic` and requires _top.php / _bottom.php. Nothing else to wire up.

⚠️ ENGLISH-ONLY AND INLINE, exactly as system/help/ is. A help topic is prose
rather than chrome, and putting whole paragraphs through the translation
layer would add several hundred keys per locale for text that nobody has
asked to have translated. The module's chrome IS translated, as it always was.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [formsHelpTopics()](/code/functions/formsHelpTopics.md) | 34 | Returns the Forms help topics - title, standfirst, card blurb and section list for each. |

## How it hangs together

**Included by:** [forms/help.php](/code/files/forms/help.php.md), [forms/help/_top.php](/code/files/forms/help/_top.php.md)

