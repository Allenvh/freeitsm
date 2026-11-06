<?php
/**
 * The action row - one strip directly under a module's header: what this page
 * is on the left, what you can do here on the right (discussion #185).
 *
 * Why it exists: creating a record used to be in a different place in nearly
 * every module - a green plus in the Tickets list header, an outlined Add under
 * the Assets search box, "Add application" in Software's toolbar, and a dozen
 * more. Somebody moving between modules had to hunt for it. The row puts it in
 * one place, the far right under the header, labelled "New".
 *
 * Rules it carries (wiki: UI-Style-Guide):
 *  - The create button says **New** - one word. The tooltip names the thing
 *    ("New ticket"), so a screen reader and a hover still say exactly what.
 *  - Just the word - no "+" and no plus icon (Ed, #185).
 *  - It sits OUTSIDE every pane and panel, so a phone never hides it inside a
 *    Filters or Tags sheet.
 *  - A page with nothing to create gets no New button; never a dead one.
 *
 * Usage, straight after the module header include:
 *
 *   require_once __DIR__ . '/../includes/action_row.php';
 *   renderActionRow(t('software.inventory.heading'), [
 *       actionRowNew('openAppModal(null)', t('software.inventory.add_app')),
 *   ], ['count_id' => 'softwareCount', 'extra' => $searchHtml]);
 *
 * The row is a direct child of <body>; assets/css/action-row.css makes the
 * page's .main-container take the height that is left, so a page needs no
 * calc() of its own.
 */

/**
 * The New button for an action row.
 *
 * @param string $onclick  JavaScript to run, e.g. "openNewTicketModal()"; or a URL when $isHref is true
 * @param string $what     what it creates, for the tooltip and screen readers, e.g. "New ticket"
 * @param bool $isHref     true when $onclick is a link to follow rather than script to run
 * @param string $id       an optional element id
 */
function actionRowNew(string $onclick, string $what, bool $isHref = false, string $id = ''): array
{
    return ['label' => t('common.action_row.new'), 'title' => $what,
            'primary' => true, ($isHref ? 'href' : 'onclick') => $onclick, 'id' => $id];
}

/**
 * Print the action row.
 *
 * @param string $title    what this page is, e.g. "Inbox" or "Software inventory"
 * @param array $actions   buttons, rightmost last: each ['label', 'onclick' or 'href', optional
 *                         'title', 'primary' (bool), 'id', 'disabled' (bool - a button the page
 *                         enables later, e.g. CMDB's New once a class is picked), 'hidden' (bool -
 *                         one the page shows later, e.g. LMS's Assign on its Assignments tab)]; actionRowNew()
 *                         builds the usual one
 * @param array $opts      'count_id' => id of a span after the title that the page fills with a count;
 *                         'title_id' => id for the title itself, for a page that rewrites it;
 *                         'extra'    => trusted HTML (a search box, say) placed before the buttons;
 *                         'accent'   => a theme.css module prefix ('cm', 'tsk') for a page that does not
 *                                       set --accent on <body> itself - Changes, whose Calendar view
 *                                       shares calendar.css with the Calendar module
 */
function renderActionRow(string $title, array $actions = [], array $opts = []): void
{
    static $cssDone = false;
    if (!$cssDone) {
        $cssDone = true;
        echo '<link rel="stylesheet" href="' . htmlspecialchars(BASE_URL) . 'assets/css/action-row.css?v=6">' . "\n";
    }
    $h = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES);

    $style = '';
    if (!empty($opts['accent']) && preg_match('/^[a-z]+$/', $opts['accent'])) {
        $p = $opts['accent'];
        $style = " style=\"--accent: var(--{$p}-accent); --accent-hover: var(--{$p}-accent-hover); --on-accent: var(--{$p}-on-accent)\"";
    }
    echo '<div class="action-row"' . $style . '>';
    echo '<h2 class="action-row-title"' . (!empty($opts['title_id']) ? ' id="' . $h($opts['title_id']) . '"' : '') . '>' . $h($title);
    if (!empty($opts['count_id'])) echo ' <span class="action-row-count" id="' . $h($opts['count_id']) . '"></span>';
    echo '</h2>';
    echo '<div class="action-row-actions">';
    if (!empty($opts['extra'])) echo $opts['extra'];
    foreach ($actions as $a) {
        $cls = 'action-row-btn' . (!empty($a['primary']) ? ' action-row-btn-primary' : '');
        $attrs = ' class="' . $cls . '"'
               . (!empty($a['id']) ? ' id="' . $h($a['id']) . '"' : '')
               . (!empty($a['title']) ? ' title="' . $h($a['title']) . '" aria-label="' . $h($a['title']) . '"' : '')
               . (!empty($a['disabled']) ? ' disabled' : '')
               . (!empty($a['hidden']) ? ' hidden' : '');
        $inner = $h($a['label']);
        if (isset($a['href'])) {
            echo '<a href="' . $h($a['href']) . '"' . $attrs . '>' . $inner . '</a>';
        } else {
            echo '<button type="button" onclick="' . $h($a['onclick'] ?? '') . '"' . $attrs . '>' . $inner . '</button>';
        }
    }
    echo '</div></div>' . "\n";
}
