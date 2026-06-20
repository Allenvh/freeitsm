<?php
/**
 * Company (tenant) switcher for the shared header.
 *
 * renderTenantSwitcher() emits NOTHING unless multi-tenancy is active (i.e. a
 * second company exists). So on a single-company install the header is exactly
 * as it was — this is the opt-in/invisible-at-N=1 principle in the UI.
 *
 * Pure presentation; all logic lives in tenancy.php. Defensive throughout — it
 * must never break the header, so any error simply renders nothing.
 *
 * NOTE: not yet wired into the header — included/called by a later slice once
 * verified in a running app.
 */
require_once __DIR__ . '/tenancy.php';

/**
 * Render the company switcher (button + dropdown) for the header.
 *
 * @param PDO $conn      a database connection
 * @param int $analystId the logged-in analyst
 */
function renderTenantSwitcher(PDO $conn, int $analystId): void {
    try {
        if (!isMultiTenant($conn)) {
            return; // dormant on single-company installs
        }
        $accessibleIds = getAccessibleTenantIds($conn, $analystId);
        if (count($accessibleIds) < 1) {
            return;
        }
        $tenants = array_values(array_filter(
            getAllTenants($conn, true),
            fn($t) => in_array($t['id'], $accessibleIds, true)
        ));
        if (count($tenants) < 1) {
            return;
        }
        $activeId   = getActiveTenantId($conn, $analystId);
        $active     = getTenantById($conn, $activeId);
        $activeName = $active['name'] ?? '';
    } catch (Exception $e) {
        return; // never break the header
    }
    ?>
    <style>
        .tenant-switcher { position: relative; margin-right: 16px; }
        .tenant-switcher-btn {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.12);
            color: #fff; border: none; cursor: pointer;
            padding: 6px 12px; border-radius: 4px;
            font-size: 13px; font-weight: 600; font-family: inherit;
            transition: background 0.15s;
            max-width: 240px;
        }
        .tenant-switcher-btn:hover { background: rgba(255,255,255,0.22); }
        .tenant-switcher-btn .ts-label {
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .tenant-switcher-btn svg { flex-shrink: 0; }
        .tenant-switcher-overlay {
            position: fixed; inset: 0; z-index: 1098; display: none;
        }
        .tenant-switcher-overlay.active { display: block; }
        .tenant-switcher-panel {
            position: absolute; top: 100%; left: 0; margin-top: 8px;
            background: #fff; border-radius: 8px;
            box-shadow: 0 6px 30px rgba(0,0,0,0.25);
            min-width: 240px; max-height: 360px; overflow-y: auto;
            z-index: 1100; display: none; padding: 6px;
        }
        .tenant-switcher-panel.active { display: block; }
        .tenant-switcher-head {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.04em; color: #999; padding: 8px 10px 4px;
        }
        .tenant-switcher-item {
            display: flex; align-items: center; justify-content: space-between;
            gap: 10px; width: 100%; text-align: left;
            padding: 9px 10px; border: none; background: none; cursor: pointer;
            font-size: 13px; color: #333; border-radius: 6px;
        }
        .tenant-switcher-item:hover { background: #f5f5f5; }
        .tenant-switcher-item.current { background: #e8f4fd; font-weight: 600; }
        .tenant-switcher-item .ts-check { color: #0078d4; flex-shrink: 0; }
    </style>

    <div class="tenant-switcher">
        <div class="tenant-switcher-overlay" id="tenantSwitcherOverlay" onclick="closeTenantSwitcher()"></div>
        <button class="tenant-switcher-btn" onclick="toggleTenantSwitcher()" title="Switch company">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"></path><path d="M5 21V7l8-4v18"></path><path d="M19 21V11l-6-4"></path></svg>
            <span class="ts-label"><?php echo htmlspecialchars($activeName); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
        <div class="tenant-switcher-panel" id="tenantSwitcherPanel">
            <div class="tenant-switcher-head">Company</div>
            <?php foreach ($tenants as $t): $isCurrent = ((int)$t['id'] === (int)$activeId); ?>
            <button class="tenant-switcher-item <?php echo $isCurrent ? 'current' : ''; ?>"
                    onclick="switchTenant(<?php echo (int)$t['id']; ?>)">
                <span><?php echo htmlspecialchars($t['name']); ?></span>
                <?php if ($isCurrent): ?>
                <svg class="ts-check" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <?php endif; ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    function toggleTenantSwitcher() {
        var panel = document.getElementById('tenantSwitcherPanel');
        var overlay = document.getElementById('tenantSwitcherOverlay');
        var active = panel.classList.contains('active');
        if (active) { closeTenantSwitcher(); }
        else { panel.classList.add('active'); overlay.classList.add('active'); }
    }
    function closeTenantSwitcher() {
        document.getElementById('tenantSwitcherPanel').classList.remove('active');
        document.getElementById('tenantSwitcherOverlay').classList.remove('active');
    }
    async function switchTenant(tenantId) {
        try {
            var resp = await fetch('<?php echo BASE_URL; ?>api/system/set_active_tenant.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ tenant_id: tenantId })
            });
            var data = await resp.json();
            if (data.success) {
                window.location.reload();
            } else if (window.showToast) {
                showToast(data.error || 'Could not switch company', 'error');
            }
        } catch (e) {
            if (window.showToast) showToast('Could not switch company', 'error');
        }
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeTenantSwitcher();
    });
    </script>
    <?php
}
