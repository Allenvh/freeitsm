# knowledge/help/_registry.php

> PHP - 42 lines

Knowledge help — the topic registry.

The same arrangement as forms/help/_registry.php (read that for the why): the
main guide, knowledge/help.php, keeps the basics and shows a card per topic;
each topic that needs real explaining gets a page of its own. The registry
owns each topic's title, standfirst and sections, which drive both the
page's sidebar and the guide's cards.

ADDING A TOPIC: add the entry here, then create <slug>.php which sets
`$helpTopic` and requires _top.php / _bottom.php. Nothing else to wire up.

⚠️ ENGLISH-ONLY AND INLINE, like forms/help/ and system/help/: a topic is
prose rather than chrome. The module's chrome IS translated.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [knowledgeHelpTopics()](/code/functions/knowledgeHelpTopics.md) | 23 | Returns the Knowledge help topics - title, standfirst, card blurb and section list for each. |

## How it hangs together

**Included by:** [knowledge/help.php](/code/files/knowledge/help.php.md), [knowledge/help/_top.php](/code/files/knowledge/help/_top.php.md)

