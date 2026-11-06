<?php
/**
 * System Help — Setting up groups and teams from single sign-on (3.5.0).
 *
 * A step-by-step guide with a checklist for each identity provider: the parts
 * done in the provider (tabs: Entra, Okta, Keycloak, other), then the parts done
 * in FreeITSM, then testing. Written for someone who has not done this before -
 * Ed asked for it after setting it up against Entra himself. Signposted from the
 * Groups and teams tab of the provider dialog (System -> SSO).
 *
 * Ticks are remembered in this browser only (localStorage), per provider tab.
 * Every "go to System -> X" is a link that opens in a new tab, so the guide stays
 * open beside the screen being set up.
 */
require __DIR__ . '/_init.php';
$helpSlug = 'sso-groups';
require __DIR__ . '/_top.php';

/**
 * A "System -> X" link that opens in a new tab.
 *
 * @param string $path  the System page path relative to system/, e.g. 'sso/'
 * @param string $label  the link text as HTML (trusted, not escaped)
 */
function sgLink(string $path, string $label): string {
    return '<a class="help-ext" href="../' . htmlspecialchars($path) . '" target="_blank" rel="noopener">' . $label . '</a>';
}
/**
 * One tick-off step.
 *
 * @param string $id  the step's key, used to remember its tick in localStorage
 * @param string $title  the step heading as HTML (trusted, not escaped)
 * @param string $body  the step's detail as HTML (trusted, not escaped); '' for none
 */
function sgCheck(string $id, string $title, string $body = ''): string {
    return '<li><label class="help-check"><input type="checkbox" data-check="' . htmlspecialchars($id) . '">'
         . '<span class="help-check-body"><span class="help-check-title">' . $title . '</span>' . ($body !== '' ? '<span>' . $body . '</span>' : '') . '</span></label></li>';
}
$sso     = sgLink('sso/', 'System &rarr; SSO');
$teams   = sgLink('teams/', 'System &rarr; Teams');
$modules = sgLink('modules/', 'System &rarr; Modules');
$analysts = sgLink('analysts/', 'System &rarr; Analysts');
$roles    = sgLink('roles/', 'System &rarr; Roles');
?>

<!-- 1. How it works -->
<div class="help-section" id="overview">
    <div class="help-section-header"><?php echo helpSectionNum('overview'); ?>
        <div>
            <h3>How it works</h3>
            <p>Let your identity provider decide who gets into FreeITSM and what they can do, instead of setting each person up by hand.</p>
        </div>
    </div>
    <p>When someone signs in with single sign-on, your identity provider (Microsoft Entra, Okta, Keycloak...) tells FreeITSM a few things about them - their name, their email, and the <strong>groups</strong> they belong to. FreeITSM can use those groups in three ways:</p>
    <div class="help-cards cols-3">
        <div class="help-card"><h4>Who can be an analyst</h4><p>Only people in your chosen group can sign in to the analyst side. Take someone out of the group and they are refused at their next sign-in.</p></div>
        <div class="help-card"><h4>Who is an administrator</h4><p>People in an administrator group get System; everyone else does not. Optional.</p></div>
        <div class="help-card"><h4>Which teams people are in</h4><p>Each group can put people in a FreeITSM team, and the team gives them their modules, companies and roles.</p></div>
    </div>
    <div class="help-flow">
        <div class="help-flow-step"><strong>Your identity provider</strong><br>Sam is in <em>IT Service Desk</em></div>
        <div class="help-flow-arrow">&rarr;</div>
        <div class="help-flow-step"><strong>Sam signs in</strong><br>FreeITSM reads the group</div>
        <div class="help-flow-arrow">&rarr;</div>
        <div class="help-flow-step"><strong>FreeITSM</strong><br>Sam joins the <em>Service Desk</em> team and gets its modules</div>
    </div>
    <p>It is checked at <strong>every</strong> sign-in, so you manage people in one place - your identity provider - and FreeITSM keeps up. Work through the checklist below in order; your ticks are remembered in this browser, so you can stop and come back.</p>
</div>

<!-- 2. Before you start -->
<div class="help-section" id="before">
    <div class="help-section-header"><?php echo helpSectionNum('before'); ?>
        <div>
            <h3>Before you start</h3>
        </div>
    </div>
    <div class="help-progress" data-progress="before"><span class="help-progress-text"></span><div class="help-progress-bar"><span></span></div></div>
    <ul class="help-checklist" data-list="before">
        <?php echo sgCheck('before-provider', 'Your identity provider already signs people in to FreeITSM',
            'If not, set that up first - see the ' . sgLink('help/sso.php', 'Authentication help') . '. You should be able to click <em>Sign in with ...</em> on the login page and get in.'); ?>
        <?php echo sgCheck('before-global', 'The provider is not tied to one company',
            'In ' . $sso . ', edit the provider, open <em>Portal and company</em> and check <em>Company</em> is <strong>Global</strong>. A provider that belongs to a company only appears on that company\'s self-service portal, so the analyst login has no button for it.'); ?>
        <?php echo sgCheck('before-teams', 'You have the teams you want people put into',
            'Create them in ' . $teams . ' (for example <em>Service Desk</em>, <em>Infrastructure</em>).'); ?>
        <?php echo sgCheck('before-modules', 'Each team has the modules it should have',
            'Give modules to teams in ' . $modules . '. A team with no modules gives its members nothing - the commonest reason it "does not work".'); ?>
        <?php echo sgCheck('before-roles', 'Optional: give teams the right to change settings',
            'Opening a module lets people work in it; changing its <em>settings</em> (for example Tickets settings) needs a role. In ' . $roles . ', open or create a role, tick what it may change, and add the <strong>team</strong> under <em>Teams</em>. Everyone your groups put in that team gets it - nothing to type.'); ?>
        <?php echo sgCheck('before-local', 'You can still get in another way',
            'Keep <em>Allow local login</em> switched on in ' . $sso . ' while you set this up, so a mistake can never lock you out.'); ?>
    </ul>
</div>

<!-- 3. In your identity provider -->
<div class="help-section" id="provider">
    <div class="help-section-header"><?php echo helpSectionNum('provider'); ?>
        <div>
            <h3>In your identity provider</h3>
            <p>Make it send the groups when someone signs in, and put people in the right groups. Pick yours:</p>
        </div>
    </div>
    <div class="help-tabs" role="tablist">
        <button type="button" data-tab="entra" class="active">Microsoft Entra ID</button>
        <button type="button" data-tab="okta">Okta</button>
        <button type="button" data-tab="keycloak">Keycloak</button>
        <button type="button" data-tab="other">Other providers</button>
    </div>
    <div class="help-progress" data-progress="provider"><span class="help-progress-text"></span><div class="help-progress-bar"><span></span></div></div>

    <div class="help-tab-panel" data-tab="entra">
        <ul class="help-checklist" data-list="entra">
            <?php echo sgCheck('entra-groups', 'Create a security group for your analysts',
                'In the <a class="help-ext" href="https://entra.microsoft.com" target="_blank" rel="noopener">Entra admin centre</a>: <em>Groups &rarr; All groups &rarr; New group</em>. Group type <strong>Security</strong>, a name such as <em>FreeITSM Staff</em>, membership <strong>Assigned</strong>, then add the people. Make more groups if you want people in different teams (for example <em>FreeITSM Infrastructure</em>).'); ?>
            <?php echo sgCheck('entra-claim', 'Add the groups claim to your FreeITSM app',
                '<em>App registrations &rarr;</em> your FreeITSM app <em>&rarr; Token configuration &rarr; Add groups claim</em>. Tick <strong>Security groups</strong>, leave the <strong>ID</strong> token set to <strong>Group ID</strong>, and click <strong>Add</strong>. You should see a row called <code>groups</code>.'); ?>
            <?php echo sgCheck('entra-ids', 'Note each group\'s Object ID',
                'Entra sends groups as codes, not names. Open each group (<em>Groups &rarr; All groups &rarr;</em> the group) and note its <strong>Object ID</strong> - for example <code>78d21822-f5ee-...</code>. You will give these names in FreeITSM so you never have to see the codes again.'); ?>
        </ul>
        <div class="help-note"><strong>Seeing a code you do not recognise?</strong> Entra can also send codes for directory roles your account holds, such as Global Administrator. Leave those unnamed and unused. People in more than 200 groups get a link instead of a list; if that is you, choose <em>Groups assigned to the application</em> when adding the claim and assign the groups to the app under <em>Enterprise applications</em>.</div>
    </div>

    <div class="help-tab-panel" data-tab="okta" hidden>
        <ul class="help-checklist" data-list="okta">
            <?php echo sgCheck('okta-groups', 'Create a group for your analysts',
                'In the Okta admin console: <em>Directory &rarr; Groups &rarr; Add group</em>, for example <em>FreeITSM Staff</em>, and assign the people. Add more groups for different teams if you want.'); ?>
            <?php echo sgCheck('okta-claim', 'Send the groups in the sign-in',
                'If your FreeITSM issuer URL ends in <code>/oauth2/default</code> (a custom authorization server): <em>Security &rarr; API &rarr; Authorization Servers &rarr; default &rarr; Claims &rarr; Add Claim</em> - name <code>groups</code>, include in <strong>ID Token</strong> (Always), value type <strong>Groups</strong>, filter <em>Starts with</em> <code>FreeITSM</code>.<br>If it is just <code>https://your-org.okta.com</code> (the org server): <em>Applications &rarr;</em> your FreeITSM app <em>&rarr; Sign On &rarr; OpenID Connect ID Token &rarr; Edit</em>, set <strong>Groups claim type</strong> to Filter, name <code>groups</code>, <em>Starts with</em> <code>FreeITSM</code>.'); ?>
            <?php echo sgCheck('okta-scope', 'Add the groups scope if your setup asks for one',
                'Some Okta setups only send groups when asked for the <code>groups</code> scope. If no groups arrive later, add <code>groups</code> to <em>Scopes</em> on the provider\'s <em>Connection</em> tab in ' . $sso . '.'); ?>
        </ul>
        <div class="help-note ok">Okta sends group <strong>names</strong>, so there is nothing to look up.</div>
    </div>

    <div class="help-tab-panel" data-tab="keycloak" hidden>
        <ul class="help-checklist" data-list="keycloak">
            <?php echo sgCheck('kc-groups', 'Create a group for your analysts',
                'In the Keycloak admin console, in your realm: <em>Groups &rarr; Create group</em>, for example <em>FreeITSM Staff</em>, then add the users to it (<em>Users &rarr;</em> the user <em>&rarr; Groups &rarr; Join group</em>).'); ?>
            <?php echo sgCheck('kc-mapper', 'Add a Group Membership mapper to the FreeITSM client',
                '<em>Clients &rarr;</em> your FreeITSM client <em>&rarr; Client scopes &rarr;</em> the one ending <em>-dedicated</em> <em>&rarr; Add mapper &rarr; By configuration &rarr; Group Membership</em>. Name and <strong>Token Claim Name</strong> both <code>groups</code>; switch <strong>Full group path</strong> <em>off</em> (otherwise names arrive as <code>/FreeITSM Staff</code>); leave <strong>Add to ID token</strong> and <strong>Add to userinfo</strong> on. Save.'); ?>
        </ul>
        <div class="help-note">Using realm roles instead of groups? Choose <code>realm_access.roles</code> as <em>Where the groups come from</em>, and in <em>Client scopes &rarr; roles &rarr; Mappers &rarr; realm roles</em> switch <strong>Add to ID token</strong> on.</div>
    </div>

    <div class="help-tab-panel" data-tab="other" hidden>
        <ul class="help-checklist" data-list="other">
            <?php echo sgCheck('other-claim', 'Find out how your provider sends groups or roles',
                'Look in its documentation for "groups claim" or "roles claim" for OpenID Connect. Most call it <code>groups</code> or <code>roles</code>; some use their own name. It must be in the <strong>ID token</strong> or the <strong>userinfo</strong> response.'); ?>
            <?php echo sgCheck('other-groups', 'Put your analysts in a group', 'And make any other groups you want to map to teams.'); ?>
        </ul>
        <div class="help-note"><strong>Google Workspace</strong> does not send groups in its sign-in at all, so this feature cannot be used with Google; manage access in <?php echo $analysts; ?> instead.</div>
    </div>
</div>

<!-- 4. In FreeITSM -->
<div class="help-section" id="freeitsm">
    <div class="help-section-header"><?php echo helpSectionNum('freeitsm'); ?>
        <div>
            <h3>In FreeITSM</h3>
            <p>All of this is on one tab: <?php echo $sso; ?>, <strong>Edit</strong> your provider, then <strong>Groups and teams</strong>.</p>
        </div>
    </div>
    <div class="help-progress" data-progress="freeitsm"><span class="help-progress-text"></span><div class="help-progress-bar"><span></span></div></div>
    <ul class="help-checklist" data-list="freeitsm">
        <?php echo sgCheck('fi-claim', 'Choose where the groups come from',
            'Pick <code>groups</code> (Entra, Okta, a Keycloak group mapper) or <code>realm_access.roles</code> (Keycloak roles), or <em>Something else</em> and type the name your provider uses. Leave the rest empty for now and press <strong>Save</strong>.'); ?>
        <?php echo sgCheck('fi-first', 'Sign in once through the provider',
            'Sign out, then on the login page click <em>Sign in with ...</em> and sign in as someone in your analyst group. (If they have no FreeITSM account yet, tick <em>Auto-create IT analysts</em> on the <em>Signing in</em> tab first.) This lets FreeITSM see which groups your provider sends.'); ?>
        <?php echo sgCheck('fi-name', 'Name the groups',
            'Sign back in as an administrator and open the tab again. <strong>Groups seen</strong> now lists every group that sign-in sent. Type a name beside each one you will use - with Entra, match the codes to the Object IDs you noted. Every dropdown below then shows your names.'); ?>
        <?php echo sgCheck('fi-analyst', 'Choose the analyst group',
            'In <strong>Analyst group</strong>, choose your <em>FreeITSM Staff</em> group. Only its members will be able to sign in as analysts. (Leave it on <em>- None -</em> to let anyone the provider signs in be an analyst.)'); ?>
        <?php echo sgCheck('fi-teams', 'Link groups to teams',
            'Under <strong>Teams</strong>, choose a group and the team it should put people in. Use <strong>Add</strong> for more rows - one group can fill several teams, and several groups can fill one team.'); ?>
        <?php echo sgCheck('fi-admin', 'Choose an administrator group (optional)',
            'Only if you want your provider to decide who is an administrator. People in it get System; people not in it lose it. The last administrator is never removed.'); ?>
        <?php echo sgCheck('fi-companies', 'Multi-company installs: decide companies for new analysts',
            'On the <em>Signing in</em> tab, <em>Company access for auto-created analysts</em>. If your teams already give companies, choose <strong>None</strong> so the teams decide.'); ?>
        <?php echo sgCheck('fi-save', 'Press Save', ''); ?>
    </ul>
</div>

<!-- 5. Test it -->
<div class="help-section" id="test">
    <div class="help-section-header"><?php echo helpSectionNum('test'); ?>
        <div>
            <h3>Test it</h3>
        </div>
    </div>
    <div class="help-progress" data-progress="test"><span class="help-progress-text"></span><div class="help-progress-bar"><span></span></div></div>
    <ul class="help-checklist" data-list="test">
        <?php echo sgCheck('t-in', 'Sign in as someone in the analyst group',
            'They should get in and see only the modules their teams give them.'); ?>
        <?php echo sgCheck('t-team', 'Check they were put in the team',
            'In ' . $analysts . ' or ' . $teams . ', they should now be a member. The <em>effective access</em> checker in ' . $modules . ' shows exactly what they can open.'); ?>
        <?php echo sgCheck('t-leaver', 'Try a leaver',
            'Take a test person out of the analyst group in your identity provider, wait a minute, and sign in as them. They should be refused with <em>"Your account is not in the group that gives analyst access"</em>. Put them back afterwards.'); ?>
    </ul>
    <div class="help-note ok"><strong>Done.</strong> From now on, manage who is an analyst - and which teams they are in - in your identity provider. Changes take effect at each person's next sign-in.</div>
</div>

<!-- 6. If it does not work -->
<div class="help-section" id="trouble">
    <div class="help-section-header"><?php echo helpSectionNum('trouble'); ?>
        <div>
            <h3>If it does not work</h3>
        </div>
    </div>
    <div class="help-table"><table>
        <thead><tr><th>What you see</th><th>What to check</th></tr></thead>
        <tbody>
            <tr><td>No <em>Sign in with ...</em> button on the analyst login</td><td>The provider belongs to a company. Set <em>Company</em> to <strong>Global</strong> on its <em>Portal and company</em> tab in <?php echo $sso; ?>.</td></tr>
            <tr><td><em>Groups seen</em> stays empty after a sign-in</td><td><em>Where the groups come from</em> was not saved before the sign-in, or the provider is not sending the claim yet - recheck the steps for your provider. A new group can take a minute or two to appear in Entra sign-ins.</td></tr>
            <tr><td>Signed in, but sees no modules</td><td>The team has no modules: give it some in <?php echo $modules; ?>. Or no team row matches one of their groups.</td></tr>
            <tr><td>Sees every module, not just the team's</td><td>The person has <em>all modules</em> of their own (accounts made before you set this up keep what they had). Switch it off in <?php echo $analysts; ?>, or in <?php echo $modules; ?>.</td></tr>
            <tr><td>"Your account is not in the group that gives analyst access"</td><td>They are not in the group chosen as <em>Analyst group</em> - working as intended for a leaver. Otherwise check they are in it in your provider, and that it is the right group in the dropdown.</td></tr>
            <tr><td>Can open a module but not change its settings</td><td>Settings need a role. Give the team one in <?php echo $roles; ?> (tick the permissions, add the team under <em>Teams</em>).</td></tr>
            <tr><td>A group shows as a code, not a name</td><td>Give it a name under <em>Groups seen</em>.</td></tr>
        </tbody>
    </table></div>
    <p>More detail on every setting: <?php echo sgLink('help/sso.php#sso-groups', 'Authentication help - Groups from the identity provider'); ?>.</p>
</div>

<script>
/* Tabs + checklist. Ticks and the chosen tab are remembered in this browser
   only; the page works the same without them. */
(function () {
    var KEY = 'freeitsm.help.ssoGroups';
    var state = {};
    try { state = JSON.parse(localStorage.getItem(KEY) || '{}') || {}; } catch (e) { state = {}; }
    state.checks = state.checks || {};
    function save() { try { localStorage.setItem(KEY, JSON.stringify(state)); } catch (e) {} }

    var tab = state.tab || 'entra';
    function showTab(t) {
        tab = t; state.tab = t; save();
        document.querySelectorAll('.help-tabs button').forEach(function (b) { b.classList.toggle('active', b.getAttribute('data-tab') === t); });
        document.querySelectorAll('.help-tab-panel').forEach(function (p) { p.hidden = p.getAttribute('data-tab') !== t; });
        progress();
    }
    function progress() {
        document.querySelectorAll('.help-progress').forEach(function (bar) {
            var which = bar.getAttribute('data-progress');
            var list = document.querySelector('.help-checklist[data-list="' + (which === 'provider' ? tab : which) + '"]');
            if (!list) return;
            var all = list.querySelectorAll('input[type=checkbox]'), done = 0;
            all.forEach(function (c) { if (c.checked) done++; });
            bar.querySelector('.help-progress-text').textContent = done + ' of ' + all.length + ' done';
            bar.querySelector('.help-progress-bar span').style.width = (all.length ? Math.round(done * 100 / all.length) : 0) + '%';
        });
    }
    document.querySelectorAll('.help-check input').forEach(function (c) {
        var id = c.getAttribute('data-check');
        c.checked = !!state.checks[id];
        c.closest('.help-check').classList.toggle('done', c.checked);
        c.addEventListener('change', function () {
            state.checks[id] = c.checked; save();
            c.closest('.help-check').classList.toggle('done', c.checked);
            progress();
        });
    });
    document.querySelectorAll('.help-tabs button').forEach(function (b) {
        b.addEventListener('click', function () { showTab(b.getAttribute('data-tab')); });
    });
    showTab(tab);
})();
</script>

<?php require __DIR__ . '/_bottom.php'; ?>
