<?php
/**
 * System Help — Modules.
 */
require __DIR__ . '/_init.php';
$helpSlug = 'modules';
require __DIR__ . '/_top.php';
?>
<!-- 1. Overview -->
<div class="help-section" id="overview">
    <div class="help-section-header"><?php echo helpSectionNum('overview'); ?>
        <div>
            <h3>What this area does</h3>
            <p>Modules lets you decide, per analyst, which parts of FreeITSM each person can open — Tickets, Assets, Knowledge, Changes, Calendar, Checks, Reporting, Software, Forms, Contracts, Wiki, Tasks, CMDB and System. It's a single grid of analysts against modules, with a toggle in every cell.</p>
        </div>
    </div>
    <p>The access grid governs <em>who</em> may reach each module. Only active analysts appear in it.</p>
    <p><strong>Availability</strong>, at the top of the page, is different: it switches a module off for the <strong>whole installation</strong>. A switched-off module disappears for everyone, administrators included - from the launcher and menus, from other modules' buttons and <em>Link to</em> options, from the REST API, from workflow triggers and actions, and from its scheduled jobs. Opening its address takes you back to the home page, and its endpoints answer that it is switched off.</p>
    <p><strong>Ticket features</strong>, under the modules, switch off parts of Tickets on their own: <strong>CSAT</strong> (no surveys are sent and old survey links say feedback is no longer being collected), <strong>SLA</strong> (no clocks, breach warnings or SLA workflow triggers; the business calendars stay, because web chat uses them for its opening hours), <strong>Time tracking</strong> (no recording time anywhere, including the REST API) and the <strong>Duty rota</strong>. Tickets keeps working without any of them. Switching SLA back on asks whether to send the warnings and breaches that were missed while it was off, or to skip them.</p>
    <div class="help-note"><strong>Nothing is deleted.</strong> The module's records, settings and everyone's access grants stay exactly as they were, and switching it back on brings it all back. Every module is on unless you switch it off, so upgrading FreeITSM never switches anything off. <strong>Tickets</strong> and <strong>System</strong> are always on.</div>
    <div class="help-note">Changes save the moment you flip a toggle — there is no Save button. A small confirmation appears after each change.</div>
</div>

<!-- 2. The access matrix -->
<div class="help-section" id="matrix">
    <div class="help-section-header"><?php echo helpSectionNum('matrix'); ?>
        <div>
            <h3>The access matrix</h3>
            <p>Each row is an analyst. The first toggle is <strong>All access</strong>; the remaining columns are the individual modules.</p>
        </div>
    </div>
    <div class="help-steps">
        <div class="help-step"><div class="help-step-num">1</div><div><strong>All access on.</strong> The analyst can open every module. The individual module toggles are all ticked and greyed out — there's nothing to choose, because they already have the lot. This is the default for a new analyst.</div></div>
        <div class="help-step"><div class="help-step-num">2</div><div><strong>All access off.</strong> The row switches to restricted mode and the individual toggles become editable. Untick the modules this analyst should <em>not</em> see, and leave the rest ticked.</div></div>
        <div class="help-step"><div class="help-step-num">3</div><div><strong>Adjust per module.</strong> With All access off, tick or untick any module to grant or remove it. Each change is saved on its own.</div></div>
    </div>
    <div class="help-note warn"><strong>System is always granted.</strong> The System column stays ticked and locked even in restricted mode — so an analyst who can reach this page can never accidentally lock themselves (or others) out of System administration.</div>
</div>

<!-- 3. What analysts see -->
<div class="help-section" id="effect">
    <div class="help-section-header"><?php echo helpSectionNum('effect'); ?>
        <div>
            <h3>What analysts see</h3>
        </div>
    </div>
    <p>Access controls where a module shows up for that analyst across the app:</p>
    <div class="help-cards">
        <div class="help-card">
            <h4>The waffle menu &amp; navigation</h4>
            <p>Modules the analyst isn't allowed don't appear in their app switcher or navigation — they simply aren't offered.</p>
        </div>
        <div class="help-card">
            <h4>The analyst using the app now</h4>
            <p>If you change your own access, it takes effect on your current session; for others it applies from their next sign-in or page load.</p>
        </div>
    </div>
    <div class="help-note">Removing a module only hides it from that one analyst. It doesn't delete any data or affect other analysts — restoring access brings the module straight back.</div>
</div>

<!-- 4. Read only (#180) -->
<div class="help-section" id="read-only">
    <div class="help-section-header"><?php echo helpSectionNum('read-only'); ?>
        <div>
            <h3>Read only</h3>
            <p><strong>Change Management</strong>, <strong>Forms</strong> and <strong>Tasks</strong> offer a second level for a grant. Open the module on this page and choose <strong>Full</strong> or <strong>Read only</strong> beside each team and analyst you tick.</p>
        </div>
    </div>
    <div class="help-cards">
        <div class="help-card">
            <h4>A reviewer</h4>
            <p>Read only lets someone open the module and read everything in it - records, approval history, text history and exports - and change nothing. The server refuses their changes on screen and through an API key acting as them.</p>
        </div>
        <div class="help-card">
            <h4>An approver</h4>
            <p>Approving is not editing. A read-only analyst named on a change's CAB, or as a change's or form's approver, can still vote or decide.</p>
        </div>
        <div class="help-card">
            <h4>An auditor</h4>
            <p>Read only access to <strong>Tasks</strong>, with the <strong>Run the evidence report</strong> permission where Tasks restricts it, lets someone read every task, its history and the evidence report for repeating tasks, and print or export it - and change nothing. Attaching or removing documents counts as a change too.</p>
        </div>
    </div>
    <div class="help-note">Levels combine like grants: under <strong>Most permissive</strong>, the highest level wins, so an analyst's own Full beats a team's Read only; under <strong>Least permissive</strong>, the lowest wins. Anyone with <em>all modules</em> has Full everywhere. The effective access checker shows the level and where it came from. Other modules have no level - they are Full wherever granted.</div>
</div>

<!-- 5. Good to know -->
<div class="help-section" id="notes">
    <div class="help-section-header"><?php echo helpSectionNum('notes'); ?>
        <div>
            <h3>Good to know</h3>
        </div>
    </div>
    <ul>
        <li><strong>Only active analysts are listed.</strong> Deactivated accounts don't appear in the grid.</li>
        <li><strong>All access is the same as ticking everything.</strong> Internally, an analyst with no restrictions stored is treated as having every module — turning All access off and on simply switches between "everything" and "only the ticked ones".</li>
        <li><strong>No undo prompt.</strong> Because each toggle saves immediately, double-check before removing a module from someone who relies on it. Re-ticking it restores access instantly.</li>
    </ul>
    <div class="help-note ok"><strong>Tip:</strong> leave most analysts on All access and only restrict the handful who should be limited to a few modules — it's quicker to manage and easy to reason about.</div>
</div>
<?php require __DIR__ . '/_bottom.php'; ?>
