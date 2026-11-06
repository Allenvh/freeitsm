<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/**
 * A task description is rich text written by an analyst and shown to other analysts.
 * It must never reach the page raw: `<img src=x onerror=...>` runs the moment it is
 * inserted. Every place that renders it goes through safeHtmlFragment() (directly, or
 * via taskDescriptionHtml() in tasks.js), and every page that does loads safe-html.js.
 *
 * Static guard - the execution proof (payload fires through the old insert, not through
 * the new one) was done in headless Chrome when this was fixed for 3.12.0.
 */
$root = dirname(__DIR__);
$pass = 0; $fail = 0;
$ok = function (string $label, bool $cond, string $why = '') use (&$pass, &$fail) {
    if ($cond) { ++$pass; echo "PASS $label\n"; } else { ++$fail; echo "FAIL $label" . ($why ? "  [$why]" : '') . "\n"; }
};

// 1. No template or innerHTML takes a description raw.
$raw = [];
foreach (glob("$root/assets/js/task*.js") as $file) {
    foreach (file($file) as $n => $line) {
        if (preg_match('/\$\{\s*[\w.]*\bdescription\s*(\|\|[^}]*)?\}/', $line) || preg_match('/innerHTML\s*=\s*[\w.]*\bdescription\b/', $line)) {
            $raw[] = basename($file) . ':' . ($n + 1);
        }
    }
}
$ok('no task script inserts a description raw', !$raw, implode(', ', $raw));

// 2. The two known renderers use the cleaner.
$tasks = file_get_contents("$root/assets/js/tasks.js");
$ok('the task editor renders through taskDescriptionHtml()', str_contains($tasks, '<div id="descriptionEditor">${taskDescriptionHtml(task.description)}</div>'));
$ok('...which cleans with safeHtmlFragment() and fails closed to escaped text',
    (bool)preg_match('/function taskDescriptionHtml\(html\)\s*\{.*?safeHtmlFragment\(html\)\s*:\s*esc\(html\)/s', $tasks));
$ok('the calendar/timeline quick panel cleans it', str_contains(file_get_contents("$root/assets/js/tasks-quick-panel.js"), 'safeHtmlFragment(task.description)'));

// 3. Every page that loads a task renderer loads the cleaner first.
foreach (['tasks/index.php' => 'tasks.js', 'tasks/calendar/index.php' => 'tasks-quick-panel.js', 'tasks/timeline/index.php' => 'tasks-quick-panel.js'] as $page => $script) {
    $src = file_get_contents("$root/$page");
    $s = strpos($src, 'safe-html.js'); $r = strpos($src, "assets/js/$script");
    $ok("$page loads safe-html.js before $script", $s !== false && $r !== false && $s < $r);
}

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
