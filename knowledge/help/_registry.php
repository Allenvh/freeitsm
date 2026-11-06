<?php
/**
 * Knowledge help — the topic registry.
 *
 * The same arrangement as forms/help/_registry.php (read that for the why): the
 * main guide, knowledge/help.php, keeps the basics and shows a card per topic;
 * each topic that needs real explaining gets a page of its own. The registry
 * owns each topic's title, standfirst and sections, which drive both the
 * page's sidebar and the guide's cards.
 *
 * ADDING A TOPIC: add the entry here, then create <slug>.php which sets
 * $helpTopic and requires _top.php / _bottom.php. Nothing else to wire up.
 *
 * ⚠️ ENGLISH-ONLY AND INLINE, like forms/help/ and system/help/: a topic is
 * prose rather than chrome. The module's chrome IS translated.
 */

/**
 * Returns the Knowledge help topics - title, standfirst, card blurb and section list for each.
 *
 * @return array<string,array<string,mixed>> Help topics, keyed by slug.
 */
function knowledgeHelpTopics(): array
{
    return [
        'documents-help-centre' => [
            'title' => 'Documents and the Help Centre',
            'sub'   => 'Which documents customers can download from the self-service portal, and how to keep one back.',
            'blurb' => 'Who can download an article\'s documents, and how to hide one.',
            'sections' => [
                'rule'     => 'The rule, in one sentence',
                'where'    => 'Only knowledge articles',
                'hide'     => 'Keeping one document back',
                'pins'     => 'A document on more than one record',
                'before'   => 'Documents attached before 3.7.0',
                'audience' => 'Changing who can read an article',
                'safe'     => 'What stops the wrong people downloading',
            ],
        ],
    ];
}
