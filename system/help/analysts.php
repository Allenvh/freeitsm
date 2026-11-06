<?php
/**
 * System Help — Analysts.
 */
require __DIR__ . '/_init.php';
$helpSlug = 'analysts';
require __DIR__ . '/_top.php';
?>

<!-- 1. Overview -->
<div class="help-section" id="overview">
    <div class="help-section-header"><?php echo helpSectionNum('overview'); ?>
        <div>
            <h3>What this page controls</h3>
            <p>An <strong>analyst</strong> is someone who works <em>in</em> FreeITSM — takes tickets, updates assets, writes knowledge. (The people who <em>raise</em> tickets are requesters, and they are created automatically from inbound email or the self-service portal; you don't add them here.)</p>
        </div>
    </div>
    <p>System &rarr; Analysts is the one place you create those accounts and decide, for each of them, four separate things:</p>
    <div class="help-cards">
        <div class="help-card">
            <h4>Are they an administrator?</h4>
            <p>Administrators can open the System module. Everyone else cannot see it at all.</p>
        </div>
        <div class="help-card">
            <h4>How do they sign in?</h4>
            <p>A local username and password, or an identity provider you've configured under Single Sign-On.</p>
        </div>
        <div class="help-card">
            <h4>What can they reach?</h4>
            <p>Which companies and which modules — set on this page, and topped up by any team they're in.</p>
        </div>
        <div class="help-card">
            <h4>Which teams are they in?</h4>
            <p>Teams decide which ticket departments they see. This is the setting most often behind &ldquo;why can't I see any tickets?&rdquo;</p>
        </div>
    </div>
    <p>The table shows one row per analyst, with their username, full name, email, teams, status, whether they're an admin, and when they last signed in. The four buttons on each row are <strong>Edit</strong>, <strong>Assign teams</strong>, <strong>Reset password</strong> and <strong>Delete</strong>.</p>
    <div class="help-note"><strong>Read the chips next to a name.</strong> On a multi-company install, an analyst who can't reach every company gets an orange chip telling you how many they <em>can</em> reach &mdash; and a red <strong>no companies</strong> chip if the answer is none, which means they will see no tickets at all. Hover it for the list.</div>
</div>

<!-- 2. Finding people in the list -->
<div class="help-section" id="list">
    <div class="help-section-header"><?php echo helpSectionNum('list'); ?>
        <div>
            <h3>Finding people in the list</h3>
            <p>With a handful of analysts the plain list is all you need. With a few hundred, the bar above the list lets you search, filter and group them. It starts as the plain list, so nothing changes until you choose something.</p>
        </div>
    </div>
    <div class="help-table"><table>
        <tr><th>Control</th><th>What it does</th></tr>
        <tr><td><strong>Search</strong></td><td>Matches name, username and email as you type. Several words must all match, in any order.</td></tr>
        <tr><td><strong>Group by</strong></td><td>Company, team, department, job title, status, administrator or sign-in method. Company only appears once you have more than one.</td></tr>
        <tr><td><strong>Then by</strong></td><td>A second level inside each group - for example company, then team.</td></tr>
        <tr><td><strong>Layout</strong></td><td><strong>Collapsible tree</strong> (groups you open and close, with Expand and Collapse for all of them), <strong>Tabs</strong> (one tab per group, like the settings screens) or <strong>One page with a jump list</strong> (every group on one page, with a list down the side to jump between them - a dropdown on a phone).</td></tr>
        <tr><td><strong>Sort</strong></td><td>Full name, username, or last sign-in (most recent first; never signed in at the bottom).</td></tr>
        <tr><td><strong>Per page</strong></td><td>All, or 25, 50 or 100 at a time. When grouped, each group has its own pages.</td></tr>
        <tr><td><strong>Filters</strong></td><td>Company, team (including <em>No team</em>), status, administrator, sign-in method and <em>Never signed in</em>. The number on the button says how many are on; <strong>Clear</strong> turns them all off.</td></tr>
    </table></div>
    <div class="help-note"><strong>Someone can be in more than one group.</strong> Grouped by team, an analyst in two teams appears under both, and the counts include them in both. Grouped by company, the company is every one they can work in - their own and any a team gives them - and a <strong>via team</strong> chip marks the people who are only there because of a team. People who can reach every company are in <strong>All companies</strong>.</div>
    <div class="help-note"><strong>Your layout is remembered.</strong> Grouping, layout, sort, page size, the status filter, which groups you left open and which tab you were on all come back next time - for you, not for everyone. The search and the other filters start empty each visit. To give everyone else a starting point, set the list up and use <strong>Default</strong> &rarr; <strong>Make this view the default for everyone</strong>; anyone who has never changed their own view gets it, and <strong>Go back to the default view</strong> undoes your own changes.</div>
    <div class="help-note ok"><strong>Hiding leavers.</strong> Deactivate is the usual way to handle someone leaving (see Leavers below), so inactive accounts build up. Set <strong>Status</strong> to <em>Active only</em> and it stays that way for you.</div>
</div>

<!-- 3. Adding an analyst -->
<div class="help-section" id="adding">
    <div class="help-section-header"><?php echo helpSectionNum('adding'); ?>
        <div>
            <h3>Adding an analyst</h3>
            <p>Press <strong>Add</strong>, fill in the form, press <strong>Save</strong>. Only three fields are actually required.</p>
        </div>
    </div>
    <div class="help-table"><table>
        <tr><th>Field</th><th>What it does</th></tr>
        <tr><td><strong>Username</strong></td><td>Required, and must be unique — saving a duplicate is rejected. This is what they type to sign in.</td></tr>
        <tr><td><strong>Full name</strong></td><td>Required. The name shown on tickets, assignments and everywhere else in the app.</td></tr>
        <tr><td><strong>Email</strong></td><td>Optional, but set it if you use SSO — it is what links their identity-provider account to this one on first sign-in.</td></tr>
        <tr><td><strong>Password</strong></td><td>Required when creating. When editing, leave it blank to keep the existing password.</td></tr>
        <tr><td><strong>Sign-in method</strong></td><td><strong>Follow team</strong> by default, or set one for this person: local username and password, or one of your configured providers. See below.</td></tr>
        <tr><td><strong>Access all companies</strong></td><td>Only appears once you have more than one company. On by default.</td></tr>
        <tr><td><strong>Active</strong></td><td>On by default. Turn it off to stop someone signing in without deleting their history.</td></tr>
        <tr><td><strong>Administrator</strong></td><td>Off by default. Grants the System module. See below.</td></tr>
        <tr><td><strong>Access all modules</strong></td><td>On by default. Turn it off to restrict them, then grant specific modules on System &rarr; Modules.</td></tr>
    </table></div>
    <div class="help-note"><strong>A new analyst is not in any team.</strong> Creating the account is only half the job — use <strong>Assign teams</strong> afterwards, or think carefully about the department consequences in the Teams section below.</div>
</div>

<!-- 4. Administrators -->
<div class="help-section" id="admin">
    <div class="help-section-header"><?php echo helpSectionNum('admin'); ?>
        <div>
            <h3>Administrators</h3>
            <p>The <strong>Administrator</strong> toggle is the single key to the System module — this whole area: analysts, teams, company access, SSO, security, encryption, the API and the rest. An analyst without it cannot open System, cannot see it in the waffle menu, and cannot call its endpoints even by typing the URL directly.</p>
        </div>
    </div>
    <p>It is deliberately separate from module access. You cannot grant &ldquo;System&rdquo; as a module on System &rarr; Modules; the admin flag is the only route in. The one exception is <strong>Preferences</strong>, which every analyst can always reach because it only changes their own settings.</p>
    <div class="help-note warn"><strong>The last administrator is protected.</strong> FreeITSM will refuse to delete, demote or deactivate the only remaining active admin — you'd lock yourself out of System with no way back. Grant admin to someone else first, then change the original.</div>
    <div class="help-note"><strong>Grant it sparingly.</strong> An administrator can see and change every company's configuration, read the topology, mint API keys and reset anyone's password. Most analysts, including senior ones, never need it.</div>
</div>

<!-- 5. Sign-in method -->
<div class="help-section" id="signin">
    <div class="help-section-header"><?php echo helpSectionNum('signin'); ?>
        <div>
            <h3>Sign-in method</h3>
            <p>Leave this as <strong>Local (username &amp; password)</strong> unless you've set up an identity provider under System &rarr; Single Sign-On. Choosing a provider means this person signs in through it; their two accounts are matched up automatically on first sign-in, <em>by email address</em>.</p>
        </div>
    </div>
    <p>So if you assign a provider, the analyst's <strong>Email</strong> field must match the address they hold at that provider — otherwise the link never forms.</p>
    <p><strong>Follow team</strong> takes the method from the teams this person is in, set on System &rarr; Teams. It is the default for a new analyst, so a new starter picks up the right method just by joining their team. The line under the dropdown says where the answer comes from. If their teams set <em>different</em> methods, nothing is guessed: they keep signing in the way they did, their row shows a <strong>Sign-in conflict</strong> badge — click it for a picture of which team says what, and the ways to fix it — and you settle it by choosing a method for them here. System help &rarr; Teams has the details.</p>
    <div class="help-note"><strong>A provider really is the only way in.</strong> An analyst assigned to a single sign-on provider such as Entra, Google or Okta, whether directly or through their team, cannot use the password form — it tells them to use the sign-in button, so a password left on the account is not a back door. (A directory provider — LDAP or Active Directory — is different: its people sign in on the password form, with their directory password.) If you need a way in when the provider is down, keep a local administrator account; see System help &rarr; Single Sign-On for the break-glass advice.</div>
    <div class="help-note ok"><strong>Deleting a provider is safe.</strong> If you later remove an SSO provider, every analyst who used it quietly reverts to local sign-in rather than being locked out.</div>
</div>

<!-- 6. Company & module access -->
<div class="help-section" id="access">
    <div class="help-section-header"><?php echo helpSectionNum('access'); ?>
        <div>
            <h3>Company &amp; module access</h3>
            <p>Two independent switches, each with the same shape: an <em>all</em> toggle, or a specific list.</p>
        </div>
    </div>

    <h4>Companies</h4>
    <p>The company controls only appear once a second company exists — on a single-company install there is nothing to choose. Turn <strong>Access all companies</strong> off and you get a checkbox list; tick the companies this person may work in. They will see tickets, assets and everything else for those companies only.</p>
    <div class="help-note"><strong>Team grants are added, never taken away.</strong> If the analyst is in a team that has company access of its own, they get that <em>as well as</em> what you tick here. Unticking a box on this page cannot remove access their team gives them — the modal tells you when that's happening. To take it back, change the team on System &rarr; Teams.</div>

    <h4>Modules</h4>
    <p>Turn <strong>Access all modules</strong> off and the analyst is restricted to whatever is ticked for them on System &rarr; Modules. Leave it on and they can open everything.</p>
    <div class="help-note warn"><strong>Module access combines with their teams, and how it combines depends on your privilege mode.</strong> In <em>most privilege</em> mode the analyst gets the union — a team can only widen what they can reach. In <em>least privilege</em> mode it's the intersection, so adding someone to a team with a narrow module list can <em>remove</em> modules from them. If a module vanishes from someone's waffle after a team change, this is why.</div>
</div>

<!-- 7. Teams & departments -->
<div class="help-section" id="teams">
    <div class="help-section-header"><?php echo helpSectionNum('teams'); ?>
        <div>
            <h3>Teams &amp; departments</h3>
            <p>Use <strong>Assign teams</strong> on a row to put someone into teams. This is the same relationship you can edit from the other end on System &rarr; Teams — change it in either place, it's the same list.</p>
        </div>
    </div>
    <p>Teams matter more than they look, because <strong>teams decide which ticket departments an analyst can see</strong>:</p>
    <div class="help-steps">
        <div class="help-step"><span class="help-step-num">1</span><div>An analyst in <strong>no team at all</strong> sees <strong>every</strong> active department.</div></div>
        <div class="help-step"><span class="help-step-num">2</span><div>An analyst in <strong>one or more teams</strong> sees only the departments linked to those teams.</div></div>
    </div>
    <div class="help-note warn"><strong>The trap:</strong> putting someone into a team that has <em>no</em> departments gives them <em>zero</em> departments — a narrower result than leaving them out of teams altogether. If a new analyst reports an empty ticket list right after you added them to a team, check that team's departments first.</div>
</div>

<!-- 8. Resetting a password -->
<div class="help-section" id="passwords">
    <div class="help-section-header"><?php echo helpSectionNum('passwords'); ?>
        <div>
            <h3>Resetting a password</h3>
            <p>The key button on a row sets a new local password. Type it twice; it must be at least eight characters, and not a published or very common password such as the default. It takes effect immediately and the analyst is not emailed — you'll need to tell them yourself.</p>
        </div>
    </div>
    <p>Any administrator can reset any account's password, including their own. There's no &ldquo;must change at next sign-in&rdquo; flag here; if you want passwords to age out, use the <strong>Password expiry</strong> setting on System &rarr; Security.</p>
</div>

<!-- 9. Leavers -->
<div class="help-section" id="leavers">
    <div class="help-section-header"><?php echo helpSectionNum('leavers'); ?>
        <div>
            <h3>Leavers</h3>
            <p>When someone leaves, press the <strong>bin icon</strong> on their row. It opens <strong>When someone leaves</strong>, which shows what they hold, offers to hand their open work to someone else, and gives you three choices.</p>
        </div>
    </div>
    <div class="help-table"><table>
        <tr><th>Choice</th><th>What happens</th><th>Undo?</th></tr>
        <tr><td><strong>Deactivate</strong></td><td>They can no longer sign in, and their API keys stop working. Their name stays on every ticket, note, change and audit entry they touched, so your history still reads correctly. <strong>This is the right choice for almost every leaver.</strong></td><td>Yes - tick Active again</td></tr>
        <tr><td><strong>Anonymise</strong></td><td>Everything Deactivate does, then their name and contact details are removed. Their records all stay, credited to "Former analyst #42", and their personal settings and sign-in links are deleted. Use it when someone asks to be forgotten.</td><td>No</td></tr>
        <tr><td><strong>Delete</strong></td><td>Removes the account and their personal settings. Only possible when their name is on no history at all - an account made by mistake, or a test login.</td><td>No</td></tr>
    </table></div>
    <p><strong>Their open work</strong> - open tickets, problems, changes and tasks, and the articles, projects, contracts and domains they own - can be moved to another analyst, or left unassigned, as part of the same step. Each item moves exactly as if you had reassigned it by hand, so its own history shows the change. Closed items keep their name: that is history, not work.</p>
    <p><strong>Also set up for them</strong> lists things routed to them that you need to change yourself, such as a form that sends approvals to them or an SLA alert that notifies them. FreeITSM does not change those for you: clearing an approver could let requests through unchecked.</p>
    <div class="help-note warn"><strong>Anonymise changes names FreeITSM stored next to their account</strong> - on tickets, audit trails and checklists - but not a name somebody typed into the text of a note or an email.</div>
    <div class="help-note">FreeITSM won't let you do any of this to <strong>your own account</strong>, or to the <strong>last active administrator</strong>.</div>
    <div class="help-note ok"><strong>Done.</strong> Add the person, set admin only if they need System, tick their companies and modules, and put them in the right teams — then check the chips on their row read the way you expect.</div>
</div>

<?php require __DIR__ . '/_bottom.php'; ?>
