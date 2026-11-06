<?php
/**
 * The optional module sidebar (discussion #124): a rail of module icons down the
 * left of every analyst screen, for anybody who would rather see their modules
 * than open the waffle each time.
 *
 * - OFF unless the analyst turns it on (Preferences -> Display -> Sidebar). The
 *   waffle stays unless they choose to hide its button (below).
 * - The analyst ticks which modules it holds; it only ever offers modules they
 *   can open, and it follows THEIR module order (GH #187) - the same
 *   analystVisibleModules() list the waffle, the home page and ⌘K draw from, so
 *   they can never disagree.
 * - Desktop only. A phone keeps its compact menu; assets/css/app-sidebar.css
 *   hides the rail below 768px and the page never moves there.
 *
 * Preferences (user_preferences):
 *   app_sidebar          '1' = on; anything else = off
 *   app_sidebar_modules  JSON array of the module keys ticked; absent = every module
 *   app_sidebar_names    how module names show (discussion #124, Dr4inder):
 *                        'icons'  (or absent) - icons only, the name as a tooltip;
 *                        'hover'  - the rail widens OVER the page on hover or
 *                                   keyboard focus, so nothing shifts;
 *                        'pinned' - always open with names; the page moves over.
 *                        The pin button at the foot of the rail switches
 *                        between 'hover' and 'pinned'.
 *   app_sidebar_waffle   'hide' = hide the waffle BUTTON while the rail shows
 *                        (discussion #124, Kraleemil); absent = show it. Only
 *                        the button goes - the panel stays, so the rail's
 *                        Recent button (and Recent's search) still open it. A
 *                        phone has no rail, so it always keeps the button.
 *
 * Drawn by renderWaffleMenuPanel(), which every analyst header already calls,
 * so no page needs editing to have it.
 *
 * @wiki App-Sidebar
 */

require_once __DIR__ . '/display_order.php';

/**
 * The signed-in analyst's sidebar settings: ['on' => bool, 'modules' => ?array,
 * 'names' => string, 'waffle' => bool]. 'modules' null means "never chosen" =
 * every module. Read once per request.
 */
function appSidebarPrefs(): array
{
    static $memo = null;
    if ($memo !== null) return $memo;
    $memo = ['on' => false, 'modules' => null, 'names' => 'icons', 'waffle' => true];
    $aid = (int)($_SESSION['analyst_id'] ?? 0);
    if ($aid <= 0 || !function_exists('connectToDatabase')) return $memo;
    try {
        $st = connectToDatabase()->prepare(
            "SELECT preference_key, preference_value FROM user_preferences
              WHERE analyst_id = ? AND preference_key IN ('app_sidebar', 'app_sidebar_modules', 'app_sidebar_names', 'app_sidebar_waffle')"
        );
        $st->execute([$aid]);
        $rows = $st->fetchAll(PDO::FETCH_KEY_PAIR);
        $memo['on'] = ($rows['app_sidebar'] ?? '') === '1';
        if (in_array($rows['app_sidebar_names'] ?? '', ['hover', 'pinned'], true)) $memo['names'] = $rows['app_sidebar_names'];
        $memo['waffle'] = ($rows['app_sidebar_waffle'] ?? '') !== 'hide';
        if (isset($rows['app_sidebar_modules']) && $rows['app_sidebar_modules'] !== '') {
            $memo['modules'] = displayOrderParse($rows['app_sidebar_modules']);
        }
    } catch (Throwable) {
        // No preferences table yet: no sidebar.
    }
    return $memo;
}

/**
 * The modules on this analyst's sidebar, in their module order: the ones they
 * can open, narrowed to the ones they ticked.
 *
 * @param array $modules  the $modules list from includes/waffle-menu.php
 */
function appSidebarModules(array $modules): array
{
    $prefs = appSidebarPrefs();
    $out = [];
    foreach (analystVisibleModules($modules) as $key => $module) {
        if ($prefs['modules'] !== null && !in_array($key, $prefs['modules'], true)) continue;
        $out[$key] = $module;
    }
    return $out;
}

/**
 * Print the sidebar, when this analyst has it on.
 *
 * @param array $modules  the $modules list from includes/waffle-menu.php
 * @param mixed $current_module  the key of the module being shown, marked as current
 */
function renderAppSidebar(array $modules, $current_module): void
{
    if (!appSidebarPrefs()['on']) return;
    $h = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES);
    $recent = t('common.waffle.tab_recent');
    $choose = t('common.sidebar.customise');
    $names  = appSidebarPrefs()['names'];
    $pinned = $names === 'pinned';
    // Icons only keeps the name as a tooltip; with names on show, a tooltip would
    // repeat the word beside it.
    $tip = fn($s) => $names === 'icons' ? ' title="' . $h($s) . '"' : '';
    ?>
    <link rel="stylesheet" href="<?php echo $h(BASE_URL); ?>assets/css/app-sidebar.css?v=9">
    <nav class="app-sidebar app-sidebar--<?php echo $h($names); ?><?php echo appSidebarPrefs()['waffle'] ? '' : ' app-sidebar--nowaffle'; ?>" id="appSidebar" aria-label="<?php echo $h(t('common.sidebar.label')); ?>">
        <?php /* The list scrolls when the modules do not all fit. The arrows have a
                 row each, above and below it, so they never cover a module; a
                 row only opens while there is more that way, so there is no
                 empty gap. A short list that fits has neither. Pointer only: a
                 keyboard tabbing down the links scrolls the list by itself. */ ?>
        <div class="app-sidebar-scroll">
        <button type="button" class="app-sidebar-arrow app-sidebar-arrow--up" tabindex="-1" aria-hidden="true" title="<?php echo $h(t('common.sidebar.scroll_top')); ?>" onclick="appSidebarScroll(-1)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 15 12 9 18 15"/></svg>
        </button>
        <div class="app-sidebar-modules" id="appSidebarList">
            <?php foreach (appSidebarModules($modules) as $key => $module): ?>
            <a href="<?php echo $h(BASE_URL . $module['path']); ?>" class="app-sidebar-link<?php echo $key === $current_module ? ' current' : ''; ?>"
               <?php echo $tip($module['name']); ?> aria-label="<?php echo $h($module['name']); ?>"<?php echo $key === $current_module ? ' aria-current="page"' : ''; ?>>
                <span class="waffle-module-icon <?php echo $h($key); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $module['icon']; ?></svg>
                </span>
                <span class="app-sidebar-name" aria-hidden="true"><?php echo $h($module['name']); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <button type="button" class="app-sidebar-arrow app-sidebar-arrow--down" tabindex="-1" aria-hidden="true" title="<?php echo $h(t('common.sidebar.scroll_bottom')); ?>" onclick="appSidebarScroll(1)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        </div>
        <div class="app-sidebar-foot">
            <button type="button" class="app-sidebar-tool" onclick="appSidebarRecent(event)"<?php echo $tip($recent); ?> aria-label="<?php echo $h($recent); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 14"/></svg>
                <span class="app-sidebar-name" aria-hidden="true"><?php echo $h($recent); ?></span>
            </button>
            <a class="app-sidebar-tool" href="<?php echo $h(BASE_URL); ?>system/preferences/#app-sidebar"<?php echo $tip($choose); ?> aria-label="<?php echo $h($choose); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/><circle cx="9" cy="6" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                <span class="app-sidebar-name" aria-hidden="true"><?php echo $h(t('common.sidebar.customise_short')); ?></span>
            </a>
            <?php /* Pin: open with names for good, or back to opening on hover. Only
                     offered once names are in use - with Icons only there is
                     nothing to pin open. */ ?>
            <?php if ($names !== 'icons'): ?>
            <button type="button" class="app-sidebar-tool app-sidebar-pin" id="appSidebarPin" onclick="appSidebarPin()" aria-pressed="<?php echo $pinned ? 'true' : 'false'; ?>"
                    aria-label="<?php echo $h(t($pinned ? 'common.sidebar.unpin_tip' : 'common.sidebar.pin_tip')); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="17" x2="12" y2="22"/><path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"/></svg>
                <span class="app-sidebar-name" aria-hidden="true"><?php echo $h(t($pinned ? 'common.sidebar.unpin' : 'common.sidebar.pin')); ?></span>
            </button>
            <?php endif; ?>
        </div>
    </nav>
    <script>
    /** The rail's Recent button: the waffle's own Recent trail, so there is one list of where you have been. */
    function appSidebarRecent(e) {
        e.stopPropagation();
        if (typeof toggleWaffleMenu !== 'function') return;
        const panel = document.getElementById('wafflePanel');
        if (!panel) return;
        // Opened from the rail it sits by the rail's Recent button, bottom left,
        // not up under the waffle; closeWaffleMenu() takes the class off again.
        panel.classList.add('waffle-panel--rail');
        if (!panel.classList.contains('active')) toggleWaffleMenu();
        if (typeof waffleShowTab === 'function') waffleShowTab('recent');
        e.currentTarget.blur();   // or :focus-within holds a hover rail open over the panel
    }

    /**
     * The scroll arrows: each arrow, and its row, shows only while there is
     * more of the list that way - re-checked on scroll and whenever the
     * sidebar or window changes size. A row opening or shutting changes the
     * list's height; that only ever clamps scrollTop, so it cannot flip-flop.
     * A click glides to that end (instantly for anyone who asked for less motion).
     */
    function appSidebarScroll(dir) {
        const list = document.getElementById('appSidebarList');
        if (!list) return;
        const still = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        // TRAP: open the far end's arrow row FIRST, at once, and hold it open
        // until the list gets there (list.heading, read by the check below).
        // Going down, the up row would otherwise open once the list moved and
        // take 24px off it, after the browser had fixed "the bottom" - so the
        // scroll stopped 24px short.
        list.heading = dir;
        const far = document.querySelector(dir > 0 ? '.app-sidebar-arrow--up' : '.app-sidebar-arrow--down');
        if (far && !far.classList.contains('show')) {
            far.style.transition = 'none';
            far.classList.add('show');
            void far.offsetHeight;   // apply it now, before the scroll is measured
            far.style.transition = '';
        }
        list.scrollTo({ top: dir > 0 ? list.scrollHeight : 0, behavior: still ? 'auto' : 'smooth' });
    }
    (function () {
        const list = document.getElementById('appSidebarList');
        if (!list) return;
        const nav = document.getElementById('appSidebar');
        const up = nav.querySelector('.app-sidebar-arrow--up'), down = nav.querySelector('.app-sidebar-arrow--down');
        const check = () => {
            // 2px slack: zoomed screens can stop a fraction short of the end.
            const atTop = list.scrollTop <= 2;
            const atBottom = list.scrollTop + list.clientHeight >= list.scrollHeight - 2;
            if ((list.heading > 0 && atBottom) || (list.heading < 0 && atTop)) list.heading = 0;
            up.classList.toggle('show', !atTop || list.heading > 0);
            down.classList.toggle('show', !atBottom || list.heading < 0);
        };
        // Your own wheel or swipe takes over from an arrow's glide.
        list.addEventListener('wheel', () => { list.heading = 0; }, { passive: true });
        list.addEventListener('touchstart', () => { list.heading = 0; }, { passive: true });
        list.addEventListener('scroll', check, { passive: true });
        if (window.ResizeObserver) new ResizeObserver(check).observe(list);
        window.addEventListener('resize', check);
        // The module you are in, when it is below the fold: bring it into the
        // middle of the list, or every page of it opens with it out of sight.
        const cur = list.querySelector('.app-sidebar-link.current');
        if (cur) {
            const at = cur.getBoundingClientRect().top - list.getBoundingClientRect().top + list.scrollTop;
            if (at + cur.offsetHeight > list.clientHeight) list.scrollTop = at - (list.clientHeight - cur.offsetHeight) / 2;
        }
        check();
        // Rows slide only from here on: on page load they are simply in place,
        // not sliding open every time you change module.
        requestAnimationFrame(() => requestAnimationFrame(() => list.parentElement.classList.add('ready')));
    })();

    /**
     * The rail's pin: switch between always open with names ('pinned') and
     * opening on hover ('hover'), saved for next time. The page moves over or
     * back at once - the CSS keys the page's padding off the rail's class.
     */
    async function appSidebarPin() {
        const nav = document.getElementById('appSidebar');
        const btn = document.getElementById('appSidebarPin');
        if (!nav || !btn) return;
        const pin = !nav.classList.contains('app-sidebar--pinned');
        nav.classList.toggle('app-sidebar--pinned', pin);
        nav.classList.toggle('app-sidebar--hover', !pin);
        btn.setAttribute('aria-pressed', pin ? 'true' : 'false');
        btn.setAttribute('aria-label', window.t(pin ? 'common.sidebar.unpin_tip' : 'common.sidebar.pin_tip'));
        btn.querySelector('.app-sidebar-name').textContent = window.t(pin ? 'common.sidebar.unpin' : 'common.sidebar.pin');
        btn.blur();   // or :focus-within would hold a just-unpinned rail open
        try {
            await fetch(<?php echo json_encode(BASE_URL . 'api/system/set_user_preference.php'); ?>, {
                method: 'POST', credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ key: 'app_sidebar_names', value: pin ? 'pinned' : 'hover' })
            });
        } catch (e) { /* the switch still holds for this page */ }
    }
    </script>
    <?php
}
