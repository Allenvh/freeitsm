<?php
/**
 * Shared renderer for a single Debug Tool page. A per-tool page is just:
 *
 *   <?php $DEBUG_TOOL_SLUG = 'd001'; require __DIR__ . '/../includes/tool-page.php';
 *
 * Included at top-level scope (NOT inside a function) so the header/waffle-menu
 * globals resolve correctly. Looks the tool up in the registry, renders its
 * detail + run UI, and wires the run/copy behaviour against its API script.
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/i18n.php';
require_once __DIR__ . '/tools.php';
I18n::initFromSession();

$current_page = 'debug-tools';
$path_prefix  = '../../../';
$translationNamespaces = ['common', 'system'];

if (!isset($_SESSION['analyst_id'])) {
    header('Location: ' . $path_prefix . 'login.php');
    exit;
}

$tool = isset($DEBUG_TOOL_SLUG) ? getDebugToolBySlug($DEBUG_TOOL_SLUG) : null;
if (!$tool) {
    http_response_code(404);
    header('Location: ' . $path_prefix . 'system/debug-tools/');
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars(I18n::getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Desk - <?php echo htmlspecialchars($tool['id'] . ' · ' . $tool['title']); ?></title>
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/inbox.css">
    <style>
        .debug-container { height: calc(100vh - 48px); overflow-y: auto; padding: 0 20px 40px; }
        .debug-back { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: #546e7a; text-decoration: none; margin: 18px 0 14px; }
        .debug-back:hover { text-decoration: underline; }
        .diag-card { background: #fff; border-radius: 10px; padding: 24px 26px; box-shadow: 0 1px 6px rgba(0,0,0,0.08); border: 1px solid #eee; }
        .diag-head { display: flex; align-items: center; gap: 14px; margin-bottom: 6px; }
        .diag-id { background: #546e7a; color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 4px; font-family: 'Consolas', monospace; }
        .diag-title { font-size: 19px; color: #333; margin: 0; flex: 1; }
        .diag-category { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #888; background: #f5f5f5; padding: 4px 8px; border-radius: 4px; }
        .diag-when { font-size: 13.5px; color: #555; line-height: 1.55; margin: 10px 0 16px; }
        .diag-section-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin: 14px 0 6px; font-weight: 600; }
        .diag-checks { margin: 0 0 6px 18px; padding: 0; font-size: 12.5px; color: #555; line-height: 1.6; }
        .diag-meta { font-size: 12px; color: #777; display: flex; gap: 22px; flex-wrap: wrap; padding: 12px 0; border-top: 1px solid #f0f0f0; margin-top: 14px; }
        .diag-meta strong { color: #333; }
        .diag-destructive { font-size: 12.5px; color: #b71c1c; background: #fdecea; border: 1px solid #f5c6cb; border-radius: 6px; padding: 10px 14px; margin: 14px 0 0; }
        .diag-input-row { display: flex; align-items: center; gap: 10px; margin: 16px 0 4px; padding-top: 14px; border-top: 1px solid #f0f0f0; }
        .diag-input-label { font-size: 13px; color: #444; font-weight: 500; white-space: nowrap; }
        .diag-input { flex: 1; max-width: 360px; padding: 8px 11px; border: 1px solid #ccc; border-radius: 5px; font-size: 13px; font-family: 'Consolas', monospace; }
        .diag-input:focus { outline: none; border-color: #546e7a; }
        .diag-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 16px; }
        .run-btn { background: #546e7a; color: #fff; border: none; padding: 9px 22px; border-radius: 5px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .run-btn:hover { background: #37474f; }
        .run-btn:disabled { background: #bbb; cursor: not-allowed; }
        .copy-btn { background: #f5f5f5; color: #333; border: 1px solid #ddd; padding: 8px 16px; border-radius: 5px; font-size: 12px; font-weight: 500; cursor: pointer; display: none; }
        .copy-btn:hover { background: #eee; }
        .copy-btn.copied { background: #2e7d32; color: #fff; border-color: #2e7d32; }
        .output-panel { display: none; margin-top: 16px; background: #1e1e1e; border-radius: 6px; padding: 14px 16px; color: #d4d4d4; font-family: 'Consolas', 'Courier New', monospace; font-size: 12px; line-height: 1.5; max-height: 520px; overflow: auto; white-space: pre-wrap; word-break: break-word; }
        .spinner-inline { display: inline-block; width: 12px; height: 12px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <div class="main-container" style="display: block; background: #f5f7fa;">
        <div class="debug-container">
            <a href="<?php echo $path_prefix; ?>system/debug-tools/" class="debug-back">&larr; <?php echo htmlspecialchars(t('system.debug.heading')); ?></a>

            <div class="diag-card" data-diag-file="<?php echo htmlspecialchars($tool['file']); ?>">
                <div class="diag-head">
                    <span class="diag-id"><?php echo htmlspecialchars($tool['id']); ?></span>
                    <h2 class="diag-title"><?php echo htmlspecialchars($tool['title']); ?></h2>
                    <span class="diag-category"><?php echo htmlspecialchars($tool['category']); ?></span>
                </div>

                <p class="diag-when"><?php echo htmlspecialchars($tool['when']); ?></p>

                <div class="diag-section-label"><?php echo htmlspecialchars(t('system.debug.checks_label')); ?></div>
                <ul class="diag-checks">
                    <?php foreach ($tool['checks'] as $c): ?>
                        <li><?php echo htmlspecialchars($c); ?></li>
                    <?php endforeach; ?>
                </ul>

                <div class="diag-meta">
                    <span><strong><?php echo htmlspecialchars(t('system.debug.runtime_label')); ?></strong> <?php echo htmlspecialchars($tool['duration']); ?></span>
                    <span><strong><?php echo htmlspecialchars(t('system.debug.side_effects_label')); ?></strong> <?php echo htmlspecialchars($tool['persists']); ?></span>
                </div>

                <?php if (!empty($tool['destructive'])): ?>
                    <div class="diag-destructive">⚠ <?php echo htmlspecialchars($tool['persists']); ?></div>
                <?php endif; ?>

                <?php if (!empty($tool['input'])): ?>
                    <div class="diag-input-row">
                        <label class="diag-input-label" for="diagInput"><?php echo htmlspecialchars($tool['input']['label']); ?></label>
                        <input type="text" class="diag-input" id="diagInput"
                               data-input-name="<?php echo htmlspecialchars($tool['input']['name']); ?>"
                               placeholder="<?php echo htmlspecialchars($tool['input']['placeholder'] ?? ''); ?>">
                    </div>
                <?php endif; ?>

                <div class="diag-actions">
                    <button class="copy-btn"><?php echo htmlspecialchars(t('system.debug.copy')); ?></button>
                    <button class="run-btn"><?php echo htmlspecialchars(t('system.debug.run')); ?></button>
                </div>

                <div class="output-panel"></div>
            </div>
        </div>
    </div>

    <script>window.translations = <?php echo json_encode(I18n::exportForJs($translationNamespaces), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;</script>
    <script src="<?php echo $path_prefix; ?>assets/js/i18n.js"></script>
    <script>
    (function () {
        var card    = document.querySelector('.diag-card');
        var runBtn  = card.querySelector('.run-btn');
        var copyBtn = card.querySelector('.copy-btn');
        var panel   = card.querySelector('.output-panel');
        var file    = card.getAttribute('data-diag-file');
        var input   = card.querySelector('.diag-input');
        var API     = <?php echo json_encode($path_prefix . 'api/system/debug-tools/'); ?>;

        runBtn.addEventListener('click', async function () {
            var query = '';
            if (input) {
                var val = input.value.trim();
                if (!val) {
                    panel.style.display = 'block';
                    panel.textContent = window.t('system.debug.input_required');
                    input.focus();
                    return;
                }
                query = '?' + encodeURIComponent(input.getAttribute('data-input-name')) + '=' + encodeURIComponent(val);
            }
            runBtn.disabled = true;
            var original = runBtn.innerHTML;
            runBtn.innerHTML = '<span class="spinner-inline"></span> ' + window.t('system.debug.running');
            panel.style.display = 'block';
            panel.textContent = window.t('system.debug.output_running');
            copyBtn.style.display = 'none';
            try {
                var res = await fetch(API + file + query, { method: 'GET', credentials: 'same-origin', headers: { 'Cache-Control': 'no-cache' } });
                panel.textContent = await res.text();
                copyBtn.style.display = 'inline-block';
            } catch (e) {
                panel.textContent = window.t('system.debug.fetch_failed', { message: e.message });
            } finally {
                runBtn.disabled = false;
                runBtn.innerHTML = original;
            }
        });

        copyBtn.addEventListener('click', async function () {
            try {
                await navigator.clipboard.writeText(panel.textContent);
                copyBtn.classList.add('copied');
                copyBtn.textContent = window.t('system.debug.copied');
                setTimeout(function () { copyBtn.classList.remove('copied'); copyBtn.textContent = window.t('system.debug.copy'); }, 1800);
            } catch (e) {
                var range = document.createRange();
                range.selectNodeContents(panel);
                var sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);
            }
        });
    })();
    </script>
</body>
</html>
