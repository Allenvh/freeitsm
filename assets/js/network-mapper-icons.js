/**
 * Network Mapper — icon library.
 *
 * Maps icon_key → inner SVG markup (paths/shapes). Each entry expects a 24×24
 * viewBox and uses currentColor + stroke-width 1.8 for crisp rendering at the
 * palette tile (40px) and on-canvas node sizes (small=48 / medium=72 /
 * large=96). Style is intentionally Feather-flavoured to match the rest of the
 * UI's icon vocabulary.
 *
 * Two consumers:
 *   1. CMDB class default icons — `cmdb_classes.icon_id` FK to `cmdb_icons`,
 *      whose `icon_key` matches a key here. Class icons render across CMDB +
 *      Network Mapper. Adding an icon here but not seeding the `cmdb_icons`
 *      row means class settings can't pick it — but per-node override still
 *      works since that's a free-text VARCHAR.
 *   2. Per-node icon override on Network Mapper diagrams
 *      (`network_diagram_nodes.icon_override`). Free-form string, any key
 *      below is fair game even if not in `cmdb_icons`.
 *
 * Category metadata drives the per-node icon picker UI — icons grouped under
 * their category in the modal. Adding a new icon: append to ICONS + META, and
 * (if it should also be class-pickable) add to the cmdb_icons seed in
 * api/system/db_verify.php and database/freeitsm.sql.
 */
(function () {
    'use strict';

    const ICONS = {
        // ---- Compute ----
        server:           '<rect x="3" y="4" width="18" height="6" rx="1.5"/><circle cx="7" cy="7" r="0.9" fill="currentColor"/><line x1="11" y1="7" x2="18" y2="7"/><rect x="3" y="14" width="18" height="6" rx="1.5"/><circle cx="7" cy="17" r="0.9" fill="currentColor"/><line x1="11" y1="17" x2="18" y2="17"/>',
        'server-rack':    '<rect x="4" y="2" width="16" height="20" rx="1.5"/><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/><circle cx="7" cy="4.5" r="0.6" fill="currentColor"/><circle cx="7" cy="9.5" r="0.6" fill="currentColor"/><circle cx="7" cy="14.5" r="0.6" fill="currentColor"/><circle cx="7" cy="19.5" r="0.6" fill="currentColor"/>',
        'server-blade':   '<rect x="3" y="3" width="18" height="18" rx="1.5"/><line x1="7" y1="3" x2="7" y2="21"/><line x1="11" y1="3" x2="11" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/><circle cx="5" cy="19" r="0.6" fill="currentColor"/><circle cx="9" cy="19" r="0.6" fill="currentColor"/><circle cx="13" cy="19" r="0.6" fill="currentColor"/><circle cx="17" cy="19" r="0.6" fill="currentColor"/>',
        'server-tower':   '<rect x="7" y="3" width="10" height="18" rx="1"/><circle cx="12" cy="7" r="1.2"/><line x1="9" y1="11" x2="15" y2="11"/><line x1="9" y1="14" x2="15" y2="14"/><circle cx="9.5" cy="18" r="0.6" fill="currentColor"/><circle cx="14.5" cy="18" r="0.6" fill="currentColor"/>',
        mainframe:        '<rect x="2" y="5" width="20" height="14" rx="1.5"/><line x1="2" y1="10" x2="22" y2="10"/><line x1="2" y1="14" x2="22" y2="14"/><circle cx="5" cy="7.5" r="0.6" fill="currentColor"/><circle cx="5" cy="12" r="0.6" fill="currentColor"/><circle cx="5" cy="16.5" r="0.6" fill="currentColor"/><rect x="9" y="6.5" width="11" height="2" rx="0.3"/><rect x="9" y="11" width="11" height="2" rx="0.3"/><rect x="9" y="15.5" width="11" height="2" rx="0.3"/>',
        vm:               '<rect x="3" y="4" width="14" height="14" rx="1.5"/><path d="M7 20h14V6"/><line x1="6" y1="9" x2="14" y2="9"/><line x1="6" y1="13" x2="11" y2="13"/>',
        function:         '<path d="M9 3h6a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H9a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4z"/><path d="M8 9c2 0 2 6 4 6s2-6 4-6"/>',
        workstation:      '<rect x="3" y="4" width="18" height="12" rx="1.5"/><line x1="8" y1="20" x2="16" y2="20"/><line x1="12" y1="16" x2="12" y2="20"/>',

        // ---- Database ----
        database:           '<ellipse cx="12" cy="5.5" rx="8" ry="2.5"/><path d="M4 5.5v6c0 1.4 3.6 2.5 8 2.5s8-1.1 8-2.5v-6"/><path d="M4 11.5v6c0 1.4 3.6 2.5 8 2.5s8-1.1 8-2.5v-6"/>',
        'database-cluster': '<ellipse cx="6.5" cy="6" rx="3.5" ry="1.5"/><path d="M3 6v8c0 0.9 1.6 1.5 3.5 1.5s3.5-0.6 3.5-1.5V6"/><ellipse cx="17.5" cy="6" rx="3.5" ry="1.5"/><path d="M14 6v8c0 0.9 1.6 1.5 3.5 1.5s3.5-0.6 3.5-1.5V6"/><ellipse cx="12" cy="18" rx="3.5" ry="1.5"/><path d="M8.5 18v3c0 0.9 1.6 1.5 3.5 1.5s3.5-0.6 3.5-1.5v-3"/>',
        'database-cache':   '<ellipse cx="12" cy="6" rx="8" ry="2.5"/><path d="M4 6v6c0 1.4 3.6 2.5 8 2.5s8-1.1 8-2.5V6"/><polyline points="10 17 14 17 11 22 13 22 9 17"/>',

        // ---- Storage ----
        storage:         '<ellipse cx="12" cy="6" rx="8" ry="2.5"/><path d="M4 6v3c0 1.4 3.6 2.5 8 2.5s8-1.1 8-2.5V6"/><path d="M4 11v3c0 1.4 3.6 2.5 8 2.5s8-1.1 8-2.5v-3"/><path d="M4 16v2c0 1.4 3.6 2.5 8 2.5s8-1.1 8-2.5v-2"/>',
        'storage-san':   '<rect x="3" y="4" width="18" height="6" rx="1.5"/><rect x="3" y="14" width="18" height="6" rx="1.5"/><circle cx="6" cy="7" r="0.7" fill="currentColor"/><circle cx="9" cy="7" r="0.7" fill="currentColor"/><circle cx="6" cy="17" r="0.7" fill="currentColor"/><circle cx="9" cy="17" r="0.7" fill="currentColor"/><line x1="13" y1="10" x2="13" y2="14"/><line x1="17" y1="10" x2="17" y2="14"/>',
        'storage-tape':  '<circle cx="8" cy="12" r="4"/><circle cx="8" cy="12" r="1.5"/><circle cx="17" cy="12" r="2"/><rect x="3" y="4" width="18" height="16" rx="1.5" stroke-dasharray="2 2" opacity="0.4"/>',
        backup:          '<path d="M21 12a9 9 0 1 1-3-6.7"/><polyline points="21 4 21 10 15 10"/>',

        // ---- Network ----
        network:         '<circle cx="6" cy="6" r="2.5"/><circle cx="18" cy="6" r="2.5"/><circle cx="12" cy="18" r="2.5"/><line x1="7.5" y1="7.5" x2="11" y2="16"/><line x1="16.5" y1="7.5" x2="13" y2="16"/><line x1="8.5" y1="6" x2="15.5" y2="6"/>',
        router:          '<rect x="3" y="11" width="18" height="8" rx="1.5"/><circle cx="7" cy="15" r="0.8" fill="currentColor"/><circle cx="10" cy="15" r="0.8" fill="currentColor"/><circle cx="13" cy="15" r="0.8" fill="currentColor"/><polyline points="6 7 9 4 12 7"/><polyline points="18 7 15 4 12 7"/><line x1="9" y1="4" x2="9" y2="11"/><line x1="15" y1="4" x2="15" y2="11"/>',
        switch:          '<rect x="2" y="8" width="20" height="9" rx="1.5"/><line x1="6" y1="12" x2="6" y2="13"/><line x1="9" y1="12" x2="9" y2="13"/><line x1="12" y1="12" x2="12" y2="13"/><line x1="15" y1="12" x2="15" y2="13"/><line x1="18" y1="12" x2="18" y2="13"/><circle cx="20" cy="11" r="0.6" fill="currentColor"/>',
        firewall:        '<rect x="3" y="4" width="18" height="16" rx="1"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="14" x2="21" y2="14"/><line x1="9" y1="4" x2="9" y2="9"/><line x1="15" y1="9" x2="15" y2="14"/><line x1="7" y1="14" x2="7" y2="20"/><line x1="13" y1="14" x2="13" y2="20"/><line x1="19" y1="14" x2="19" y2="20"/><line x1="12" y1="4" x2="12" y2="9"/>',
        'load-balancer': '<rect x="8" y="9" width="8" height="6" rx="1"/><polyline points="3 4 6 7 6 9"/><polyline points="3 12 6 12"/><polyline points="3 20 6 17 6 15"/><polyline points="21 4 18 7 18 9"/><polyline points="21 12 18 12"/><polyline points="21 20 18 17 18 15"/>',
        proxy:           '<circle cx="5" cy="12" r="2"/><circle cx="19" cy="12" r="2"/><rect x="9" y="9" width="6" height="6" rx="0.8"/><line x1="7" y1="12" x2="9" y2="12"/><line x1="15" y1="12" x2="17" y2="12"/>',
        vpn:             '<path d="M17.5 16a4.5 4.5 0 1 0-1.3-8.8 6 6 0 0 0-11.6 2.3A4 4 0 0 0 5 16h12.5z"/><rect x="9" y="14" width="6" height="6" rx="1"/><path d="M10.5 14v-1.5a1.5 1.5 0 0 1 3 0V14"/>',
        gateway:         '<path d="M4 21V8l8-5 8 5v13"/><path d="M9 21v-7a3 3 0 0 1 6 0v7"/>',
        'wireless-ap':   '<circle cx="12" cy="18" r="2"/><path d="M6 13a8 8 0 0 1 12 0"/><path d="M3 9a13 13 0 0 1 18 0"/>',
        modem:           '<rect x="3" y="13" width="18" height="7" rx="1.5"/><circle cx="7" cy="16.5" r="0.7" fill="currentColor"/><circle cx="10" cy="16.5" r="0.7" fill="currentColor"/><path d="M9 8a6 6 0 0 1 6 0"/><path d="M7 5a10 10 0 0 1 10 0"/>',
        cdn:             '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/><line x1="12" y1="3" x2="12" y2="8"/><line x1="12" y1="16" x2="12" y2="21"/><line x1="3" y1="12" x2="8" y2="12"/><line x1="16" y1="12" x2="21" y2="12"/>',
        dns:             '<rect x="3" y="4" width="18" height="14" rx="1.5"/><text x="12" y="14" text-anchor="middle" font-size="6" font-family="ui-monospace,monospace" fill="currentColor" stroke="none">DNS</text><line x1="3" y1="18" x2="9" y2="22"/><line x1="21" y1="18" x2="15" y2="22"/>',

        // ---- Security ----
        shield:          '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/>',
        lock:            '<rect x="5" y="11" width="14" height="10" rx="1.5"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
        key:             '<circle cx="7" cy="15" r="4"/><line x1="10" y1="12" x2="20" y2="3"/><line x1="18" y1="5" x2="20" y2="7"/><line x1="15" y1="8" x2="17" y2="10"/>',
        ids:             '<rect x="3" y="4" width="14" height="14" rx="1.5"/><circle cx="16" cy="17" r="4"/><line x1="19" y1="20" x2="22" y2="23"/><line x1="6" y1="8" x2="14" y2="8"/><line x1="6" y1="12" x2="11" y2="12"/>',
        siem:            '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/><polyline points="9 18 10 22 14 22 15 18"/>',

        // ---- Cloud ----
        cloud:            '<path d="M17.5 19a4.5 4.5 0 1 0-1.3-8.8 6 6 0 0 0-11.6 2.3A4 4 0 0 0 5 19h12.5z"/>',
        'cloud-private':  '<path d="M17.5 17a4.5 4.5 0 1 0-1.3-8.8 6 6 0 0 0-11.6 2.3A4 4 0 0 0 5 17h12.5z"/><rect x="9" y="14" width="6" height="6" rx="1"/><path d="M10.5 14v-1a1.5 1.5 0 0 1 3 0v1"/>',
        'cloud-public':   '<path d="M17.5 19a4.5 4.5 0 1 0-1.3-8.8 6 6 0 0 0-11.6 2.3A4 4 0 0 0 5 19h12.5z"/><line x1="9" y1="14" x2="15" y2="14"/><polyline points="13 12 15 14 13 16"/>',
        'cloud-hybrid':   '<path d="M14.5 14a3 3 0 1 0-0.9-5.9 4 4 0 0 0-7.7 1.5A2.7 2.7 0 0 0 4.5 14h10z"/><path d="M19 21a3 3 0 0 0 0-6 4 4 0 0 0-7.8-0.7"/>',
        region:           '<circle cx="12" cy="12" r="9"/><ellipse cx="12" cy="12" rx="9" ry="3.5"/><line x1="12" y1="3" x2="12" y2="21"/>',

        // ---- Containers ----
        container:        '<path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"/><line x1="4" y1="7.5" x2="12" y2="12"/><line x1="20" y1="7.5" x2="12" y2="12"/><line x1="12" y1="12" x2="12" y2="21"/>',
        'container-pod':  '<rect x="3" y="3" width="8" height="8" rx="1"/><rect x="13" y="3" width="8" height="8" rx="1"/><rect x="3" y="13" width="8" height="8" rx="1"/><rect x="13" y="13" width="8" height="8" rx="1"/>',
        kubernetes:       '<polygon points="12 2 21 7 19 17 12 22 5 17 3 7"/><polygon points="12 7 16 10 15 15 12 17 9 15 8 10"/>',
        registry:         '<rect x="3" y="4" width="18" height="4" rx="0.8"/><rect x="3" y="10" width="18" height="4" rx="0.8"/><rect x="3" y="16" width="18" height="4" rx="0.8"/><circle cx="6" cy="6" r="0.6" fill="currentColor"/><circle cx="6" cy="12" r="0.6" fill="currentColor"/><circle cx="6" cy="18" r="0.6" fill="currentColor"/>',

        // ---- Application & Data ----
        application:    '<rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><circle cx="6" cy="6.5" r="0.7" fill="currentColor"/><circle cx="8.5" cy="6.5" r="0.7" fill="currentColor"/><circle cx="11" cy="6.5" r="0.7" fill="currentColor"/>',
        service:        '<circle cx="12" cy="12" r="3.5"/><path d="M12 2v3M12 19v3M4.93 4.93l2.12 2.12M16.95 16.95l2.12 2.12M2 12h3M19 12h3M4.93 19.07l2.12-2.12M16.95 7.05l2.12-2.12"/>',
        website:        '<circle cx="12" cy="12" r="9"/><line x1="3" y1="12" x2="21" y2="12"/><path d="M12 3a14 14 0 0 1 4 9 14 14 0 0 1-4 9 14 14 0 0 1-4-9 14 14 0 0 1 4-9z"/>',
        api:            '<polyline points="8 5 3 12 8 19"/><polyline points="16 5 21 12 16 19"/><line x1="14" y1="4" x2="10" y2="20"/>',
        microservice:   '<rect x="2" y="4" width="6" height="6" rx="1"/><rect x="16" y="4" width="6" height="6" rx="1"/><rect x="9" y="14" width="6" height="6" rx="1"/><line x1="8" y1="7" x2="16" y2="7"/><line x1="6" y1="10" x2="11" y2="14"/><line x1="18" y1="10" x2="13" y2="14"/>',
        queue:          '<rect x="3" y="8" width="4" height="8" rx="0.5"/><rect x="8" y="8" width="4" height="8" rx="0.5"/><rect x="13" y="8" width="4" height="8" rx="0.5"/><polyline points="18 9 21 12 18 15"/>',
        cache:          '<rect x="3" y="4" width="18" height="16" rx="2"/><polyline points="10 9 14 9 11 14 13 14 9 19"/>',
        dashboard:      '<path d="M12 14a4 4 0 0 0 4-4"/><circle cx="12" cy="14" r="0.8" fill="currentColor"/><path d="M3 18a9 9 0 0 1 18 0"/>',

        // ---- Endpoints ----
        laptop:         '<rect x="4" y="5" width="16" height="10" rx="1.5"/><path d="M2 19h20l-2-2H4l-2 2z"/>',
        mobile:         '<rect x="7" y="2" width="10" height="20" rx="2"/><line x1="11" y1="19" x2="13" y2="19"/>',
        tablet:         '<rect x="4" y="3" width="16" height="18" rx="2"/><line x1="11" y1="18" x2="13" y2="18"/>',
        iot:            '<rect x="6" y="6" width="12" height="12" rx="1"/><rect x="9" y="9" width="6" height="6"/><line x1="6" y1="9" x2="3" y2="9"/><line x1="6" y1="12" x2="3" y2="12"/><line x1="6" y1="15" x2="3" y2="15"/><line x1="18" y1="9" x2="21" y2="9"/><line x1="18" y1="12" x2="21" y2="12"/><line x1="18" y1="15" x2="21" y2="15"/><line x1="9" y1="6" x2="9" y2="3"/><line x1="12" y1="6" x2="12" y2="3"/><line x1="15" y1="6" x2="15" y2="3"/><line x1="9" y1="18" x2="9" y2="21"/><line x1="12" y1="18" x2="12" y2="21"/><line x1="15" y1="18" x2="15" y2="21"/>',
        printer:        '<polyline points="6 9 6 3 18 3 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="7"/>',

        // ---- Monitoring & Ops ----
        monitor:        '<path d="M3 12a9 9 0 0 1 18 0"/><line x1="12" y1="12" x2="16" y2="8"/><circle cx="12" cy="12" r="1" fill="currentColor"/>',
        alert:          '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10 21a2 2 0 0 0 4 0"/>',
        log:            '<path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="14 3 14 9 20 9"/><line x1="7" y1="13" x2="17" y2="13"/><line x1="7" y1="16" x2="17" y2="16"/><line x1="7" y1="19" x2="13" y2="19"/>',

        // ---- People & Org ----
        person:         '<circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/>',
        team:           '<circle cx="9" cy="8" r="3.5"/><path d="M2 21v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1"/><circle cx="17.5" cy="9.5" r="2.5"/><path d="M22 18.5v-1a3.5 3.5 0 0 0-3.5-3.5h-1"/>',
        org:            '<rect x="9" y="2" width="6" height="5" rx="0.5"/><rect x="2" y="14" width="6" height="5" rx="0.5"/><rect x="9" y="14" width="6" height="5" rx="0.5"/><rect x="16" y="14" width="6" height="5" rx="0.5"/><line x1="12" y1="7" x2="12" y2="11"/><line x1="5" y1="14" x2="5" y2="11"/><line x1="19" y1="14" x2="19" y2="11"/><line x1="5" y1="11" x2="19" y2="11"/>',

        // ---- Files & Misc ----
        document:       '<path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="14 3 14 9 20 9"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="13" y2="17"/>',
        folder:         '<path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>',
        globe:          '<circle cx="12" cy="12" r="9"/><line x1="3" y1="12" x2="21" y2="12"/><path d="M12 3a14 14 0 0 1 4 9 14 14 0 0 1-4 9 14 14 0 0 1-4-9 14 14 0 0 1 4-9z"/>',
        mail:           '<rect x="3" y="5" width="18" height="14" rx="1.5"/><polyline points="3 6 12 13 21 6"/>',
        calendar:       '<rect x="3" y="5" width="18" height="16" rx="1.5"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="8" y1="3" x2="8" y2="6"/><line x1="16" y1="3" x2="16" y2="6"/>',
        box:            '<path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"/><line x1="4" y1="7.5" x2="12" y2="12"/><line x1="20" y1="7.5" x2="12" y2="12"/><line x1="12" y1="12" x2="12" y2="21"/>'
    };

    // Category metadata for the per-node icon picker UI. Keys must match
    // ICONS above. Order here drives display order in the picker modal.
    const META = {
        server:           { label: 'Server',            category: 'compute' },
        'server-rack':    { label: 'Server (rack)',     category: 'compute' },
        'server-blade':   { label: 'Server (blade)',    category: 'compute' },
        'server-tower':   { label: 'Server (tower)',    category: 'compute' },
        mainframe:        { label: 'Mainframe',         category: 'compute' },
        vm:               { label: 'Virtual Machine',   category: 'compute' },
        function:         { label: 'Function',          category: 'compute' },
        workstation:      { label: 'Workstation',       category: 'compute' },

        database:           { label: 'Database',          category: 'database' },
        'database-cluster': { label: 'Database cluster',  category: 'database' },
        'database-cache':   { label: 'Database (cache)',  category: 'database' },

        storage:        { label: 'Storage',         category: 'storage' },
        'storage-san':  { label: 'SAN',             category: 'storage' },
        'storage-tape': { label: 'Tape backup',     category: 'storage' },
        backup:         { label: 'Backup',          category: 'storage' },

        network:         { label: 'Network',          category: 'network' },
        router:          { label: 'Router',           category: 'network' },
        switch:          { label: 'Switch',           category: 'network' },
        firewall:        { label: 'Firewall',         category: 'network' },
        'load-balancer': { label: 'Load balancer',    category: 'network' },
        proxy:           { label: 'Proxy',            category: 'network' },
        vpn:             { label: 'VPN',              category: 'network' },
        gateway:         { label: 'Gateway',          category: 'network' },
        'wireless-ap':   { label: 'Wireless AP',      category: 'network' },
        modem:           { label: 'Modem',            category: 'network' },
        cdn:             { label: 'CDN',              category: 'network' },
        dns:             { label: 'DNS',              category: 'network' },

        shield: { label: 'Shield',                  category: 'security' },
        lock:   { label: 'Lock',                    category: 'security' },
        key:    { label: 'Key',                     category: 'security' },
        ids:    { label: 'IDS / IPS',               category: 'security' },
        siem:   { label: 'SIEM',                    category: 'security' },

        cloud:           { label: 'Cloud',           category: 'cloud' },
        'cloud-private': { label: 'Private cloud',   category: 'cloud' },
        'cloud-public':  { label: 'Public cloud',    category: 'cloud' },
        'cloud-hybrid':  { label: 'Hybrid cloud',    category: 'cloud' },
        region:          { label: 'Region',          category: 'cloud' },

        container:        { label: 'Container',          category: 'containers' },
        'container-pod':  { label: 'Pod',                category: 'containers' },
        kubernetes:       { label: 'Kubernetes',         category: 'containers' },
        registry:         { label: 'Registry',           category: 'containers' },

        application:  { label: 'Application',    category: 'app' },
        service:      { label: 'Service',        category: 'app' },
        website:      { label: 'Website',        category: 'app' },
        api:          { label: 'API',            category: 'app' },
        microservice: { label: 'Microservice',   category: 'app' },
        queue:        { label: 'Message queue',  category: 'app' },
        cache:        { label: 'Cache',          category: 'app' },
        dashboard:    { label: 'Dashboard',      category: 'app' },

        laptop:  { label: 'Laptop',           category: 'endpoint' },
        mobile:  { label: 'Mobile',           category: 'endpoint' },
        tablet:  { label: 'Tablet',           category: 'endpoint' },
        iot:     { label: 'IoT device',       category: 'endpoint' },
        printer: { label: 'Printer',          category: 'endpoint' },

        monitor: { label: 'Monitor / gauge',  category: 'ops' },
        alert:   { label: 'Alert',            category: 'ops' },
        log:     { label: 'Log',              category: 'ops' },

        person: { label: 'Person',  category: 'people' },
        team:   { label: 'Team',    category: 'people' },
        org:    { label: 'Org',     category: 'people' },

        document: { label: 'Document', category: 'files' },
        folder:   { label: 'Folder',   category: 'files' },
        globe:    { label: 'Globe',    category: 'files' },
        mail:     { label: 'Mail',     category: 'files' },
        calendar: { label: 'Calendar', category: 'files' },
        box:      { label: 'Generic',  category: 'files' }
    };

    // Category display labels and order (drives the per-node picker layout)
    const CATEGORIES = [
        { key: 'compute',    label: 'Compute & Servers' },
        { key: 'database',   label: 'Databases' },
        { key: 'storage',    label: 'Storage' },
        { key: 'network',    label: 'Networking' },
        { key: 'security',   label: 'Security' },
        { key: 'cloud',      label: 'Cloud' },
        { key: 'containers', label: 'Containers' },
        { key: 'app',        label: 'Applications & Data' },
        { key: 'endpoint',   label: 'Endpoints' },
        { key: 'ops',        label: 'Monitoring & Ops' },
        { key: 'people',     label: 'People & Org' },
        { key: 'files',      label: 'Files & Generic' }
    ];

    const FALLBACK = ICONS.box;

    /**
     * Build a full <svg> element string for a given icon_key.
     *   key   — icon_key (e.g. 'server'); unknown keys fall back to box
     *   size  — pixel width/height (defaults to 24)
     *   extra — extra attributes string for the <svg> (e.g. 'class="nm-icon"')
     */
    function renderIcon(key, size, extra) {
        const inner = ICONS[key] || FALLBACK;
        const sz = size || 24;
        const attrs = extra || '';
        return '<svg xmlns="http://www.w3.org/2000/svg" width="' + sz + '" height="' + sz +
               '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" ' +
               'stroke-linecap="round" stroke-linejoin="round" ' + attrs + '>' + inner + '</svg>';
    }

    window.NM_ICONS = ICONS;
    window.NM_ICON_META = META;
    window.NM_ICON_CATEGORIES = CATEGORIES;
    window.nmRenderIcon = renderIcon;
})();
