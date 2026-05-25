<?php
/**
 * Tickets Module Header Component
 *
 * Required: $current_page variable should be set before including.
 * Assumes the parent page has called I18n::initFromSession(); if not,
 * t() falls back to returning keys verbatim (no fatal, just untranslated).
 */

// Path prefix for navigation links (can be overridden by including page)
$path_prefix = $path_prefix ?? '../';
$current_module = 'tickets';
$module_title = function_exists('t') ? t('tickets.title') : 'Tickets';

// Ensure user is logged in
if (!isset($_SESSION['analyst_id'])) {
    header('Location: ' . $path_prefix . 'login.php');
    exit;
}

$analyst_name = $_SESSION['analyst_name'] ?? 'Analyst';
$current_page = $current_page ?? '';

// Helper that prefers translated label, falls back to literal if i18n isn't loaded
$navLabel = function ($key, $fallback) {
    return function_exists('t') ? t('tickets.nav.' . $key) : $fallback;
};

// Include the shared waffle menu component
require_once $path_prefix . 'includes/waffle-menu.php';
?>

<div class="header tickets-header">
    <div class="waffle-menu-container">
        <?php renderWaffleMenuButton(); ?>
        <?php renderWaffleMenuPanel($modules, $current_module, $path_prefix); ?>
        <span class="module-title"><?php echo htmlspecialchars($module_title); ?></span>
    </div>
    <nav class="header-nav">
        <a href="<?php echo $path_prefix; ?>tickets/" class="nav-btn <?php echo $current_page === 'inbox' ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($navLabel('inbox', 'Inbox')); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
            </svg>
            <span><?php echo htmlspecialchars($navLabel('inbox', 'Inbox')); ?></span>
        </a>
        <a href="<?php echo $path_prefix; ?>tickets/dashboard/" class="nav-btn <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($navLabel('dashboard', 'Dashboard')); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span><?php echo htmlspecialchars($navLabel('dashboard', 'Dashboard')); ?></span>
        </a>
        <a href="<?php echo $path_prefix; ?>tickets/users.php" class="nav-btn <?php echo $current_page === 'users' ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($navLabel('users', 'Users')); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span><?php echo htmlspecialchars($navLabel('users', 'Users')); ?></span>
        </a>
        <a href="<?php echo $path_prefix; ?>tickets/calendar.php" class="nav-btn <?php echo $current_page === 'calendar' ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($navLabel('calendar', 'Calendar')); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span><?php echo htmlspecialchars($navLabel('calendar', 'Calendar')); ?></span>
        </a>
        <a href="<?php echo $path_prefix; ?>tickets/rota.php" class="nav-btn <?php echo $current_page === 'rota' ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($navLabel('rota', 'Rota')); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
                <circle cx="8" cy="15" r="1"></circle>
                <circle cx="12" cy="15" r="1"></circle>
                <circle cx="16" cy="15" r="1"></circle>
            </svg>
            <span><?php echo htmlspecialchars($navLabel('rota', 'Rota')); ?></span>
        </a>
        <a href="<?php echo $path_prefix; ?>tickets/csat/" class="nav-btn <?php echo $current_page === 'csat' ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($navLabel('csat', 'CSAT')); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            <span><?php echo htmlspecialchars($navLabel('csat', 'CSAT')); ?></span>
        </a>
        <a href="<?php echo $path_prefix; ?>tickets/settings/" class="nav-btn <?php echo $current_page === 'settings' ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($navLabel('settings', 'Settings')); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
            <span><?php echo htmlspecialchars($navLabel('settings', 'Settings')); ?></span>
        </a>
        <a href="<?php echo $path_prefix; ?>tickets/help.php" class="nav-btn <?php echo $current_page === 'help' ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($navLabel('help', 'Help')); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
            <span><?php echo htmlspecialchars($navLabel('help', 'Help')); ?></span>
        </a>
    </nav>
    <?php renderHeaderRight($analyst_name, $path_prefix); ?>
</div>

<?php renderWaffleMenuJS(); ?>
