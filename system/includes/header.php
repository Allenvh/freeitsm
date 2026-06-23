<?php
/**
 * System Module Header Component
 */

$path_prefix = $path_prefix ?? '../';
$current_module = 'system';
$module_title = function_exists('t') ? t('system.title') : 'System';

// Ensure user is logged in
if (!isset($_SESSION['analyst_id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$analyst_name = $_SESSION['analyst_name'] ?? 'Analyst';
$current_page = $current_page ?? '';

// Include the shared waffle menu component
require_once $path_prefix . 'includes/waffle-menu.php';
?>

<div class="header system-header">
    <div class="waffle-menu-container">
        <?php renderWaffleMenuButton(); ?>
        <?php renderWaffleMenuPanel($modules, $current_module, $path_prefix); ?>
        <?php // System has too many areas to fit in the navbar, so navigation lives in the
              // landing-page cards (with search). The title links back there as the way home. ?>
        <a href="<?php echo BASE_URL; ?>system/" class="module-title" style="text-decoration:none;color:inherit;" title="<?php echo htmlspecialchars(t('system.landing.heading')); ?>"><?php echo $module_title; ?></a>
    </div>
    <?php renderHeaderRight($analyst_name, $path_prefix); ?>
</div>

<?php renderWaffleMenuJS(); ?>
