# system/help/_init.php

> PHP - 84 lines

Shared boot for System help topic pages. Include at the very TOP of a topic page (top-level scope, not inside a function) so config/i18n globals and the waffle-menu chain resolve correctly:

<?php require __DIR__ . '/_init.php';
        `$helpSlug` = 'security';           // the entry in _registry.php
        require __DIR__ . '/_top.php'; ?>
      <div class="help-section" id="passwords">
          <div class="help-section-header"><?php echo helpSectionNum('passwords'); ?>
              <div>
                  <h3>Passwords</h3>
                  <p>The section's standfirst.</p>
              </div>
          </div>
          <p>Body copy, indented automatically — see the heading model.</p>
      </div>
  <?php require __DIR__ . '/_bottom.php'; ?>

The hero, standfirst and sidebar nav all come from the page's registry entry,
so the landing page's search index and the page's own sections cannot drift
apart. A page may still set `$helpHero` / `$helpSub` / `$helpNav` explicitly to
override the registry.

🎨 These pages use the SHARED house style in assets/css/help.css — the same
`help-*` classes as the 26 module guides, not a private copy. They were
built six weeks before that stylesheet existed and were missed by the sweep
that moved everything else onto it, so until 1.8.0 they carried their own
118-line block under a `syshelp-` prefix. Do not reintroduce one: layout CSS
belongs in help.css so every guide gets it. See the wiki page Help Page
House Style before adding anything here.

System help is English-only (consistent with the System module), so content
is written inline rather than via i18n keys.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [helpSectionNum()](/code/functions/helpSectionNum.md) | 74 | The number badge for a section, derived from the page's registry entry. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/areas.php](/code/files/system/includes/areas.php.md), [system/help/_registry.php](/code/files/system/help/_registry.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md)

**Included by:** [system/help/ai.php](/code/files/system/help/ai.php.md), [system/help/analysts.php](/code/files/system/help/analysts.php.md), [system/help/api.php](/code/files/system/help/api.php.md), [system/help/branding.php](/code/files/system/help/branding.php.md), [system/help/calendar-sync.php](/code/files/system/help/calendar-sync.php.md), [system/help/colours.php](/code/files/system/help/colours.php.md), [system/help/companies.php](/code/files/system/help/companies.php.md), [system/help/contributors.php](/code/files/system/help/contributors.php.md), [system/help/cost-centres.php](/code/files/system/help/cost-centres.php.md), [system/help/date-formats.php](/code/files/system/help/date-formats.php.md), [system/help/db-verify.php](/code/files/system/help/db-verify.php.md), [system/help/debug-tools.php](/code/files/system/help/debug-tools.php.md), [system/help/demo-data.php](/code/files/system/help/demo-data.php.md), [system/help/docker.php](/code/files/system/help/docker.php.md), [system/help/email-routing-test.php](/code/files/system/help/email-routing-test.php.md), [system/help/encryption.php](/code/files/system/help/encryption.php.md), [system/help/feature-bingo.php](/code/files/system/help/feature-bingo.php.md), [system/help/integrations.php](/code/files/system/help/integrations.php.md), [system/help/managers.php](/code/files/system/help/managers.php.md), [system/help/modules.php](/code/files/system/help/modules.php.md), [system/help/orphaned-tickets.php](/code/files/system/help/orphaned-tickets.php.md), [system/help/photo-album.php](/code/files/system/help/photo-album.php.md), [system/help/portal-profile.php](/code/files/system/help/portal-profile.php.md), [system/help/preferences.php](/code/files/system/help/preferences.php.md), [system/help/roles.php](/code/files/system/help/roles.php.md), [system/help/search.php](/code/files/system/help/search.php.md), [system/help/security.php](/code/files/system/help/security.php.md), [system/help/sso-groups.php](/code/files/system/help/sso-groups.php.md), [system/help/sso.php](/code/files/system/help/sso.php.md), [system/help/status-portal.php](/code/files/system/help/status-portal.php.md), [system/help/teams.php](/code/files/system/help/teams.php.md), [system/help/topology.php](/code/files/system/help/topology.php.md), [system/help/webhooks.php](/code/files/system/help/webhooks.php.md)

