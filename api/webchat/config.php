<?php
/**
 * PUBLIC endpoint: return a widget's browser-facing config so widget.js can render.
 * No authentication — a website visitor hits this. Guarded by the origin allowlist.
 * Never returns anything sensitive (no key, no company internals) — just look & feel.
 */
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/webchat/public.php';

webchatHandlePreflight();

$key  = trim((string) ($_GET['key'] ?? ''));
$conn = connectToDatabase();
$widget = webchatPublicResolve($conn, $key);

// Availability (business-hours calendar) drives whether the widget presents itself as
// live or "leave a message". A widget with no calendar is always open.
$isOpen    = webchatIsOpenNow($conn, isset($widget['business_calendar_id']) ? (int) $widget['business_calendar_id'] : null);
$aiEnabled = !empty($widget['ai_enabled']);

echo json_encode([
    'success' => true,
    'config'  => [
        'name'            => $widget['name'],
        'greeting'        => $widget['greeting'] ?: '',
        'accent'          => $widget['accent_colour'] ?: '#2563eb',
        'launcher_text'   => $widget['launcher_text'] ?: '',
        'require_email'   => (bool) $widget['require_email'],
        // Resolved offline notice (custom or a sensible default) — the widget shows it
        // when closed, so a closed widget is never silent.
        'offline_message' => webchatOfflineMessage($widget),
        'is_open'         => $isOpen,
        // AI answers + which escalation routes the widget should offer. Only meaningful
        // while open — a closed widget takes the enquiry as a ticket regardless.
        'ai_enabled'      => $aiEnabled,
        'ai_offer_agent'  => $aiEnabled && !empty($widget['ai_offer_agent']),
        'ai_offer_email'  => $aiEnabled && !empty($widget['ai_offer_email']),
    ],
]);
