# self-service/includes/header.php

> PHP - 349 lines

Self-service portal — the shared page top.

Every authenticated portal page used to repeat this boilerplate inline, and
the header/nav CSS was copy-pasted verbatim into all four of them. Adding a
page meant a fifth copy plus editing five files to add a nav link. This is
that chrome, once.

A page includes it like this, having set nothing else up itself:

    <?php
    `$pageTitleKey` = 'self-service.dashboard.title';   // a KEY, not t(...) —
    `$activeNav`    = 'dashboard';                     // i18n isn't up yet
    require __DIR__ . '/includes/header.php';
    ?>
    …page content…
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

Optional extras a page may set BEFORE including this:
  `$pageStyles`  — a string of page-specific CSS (keep it genuinely page-specific;
                 anything shared belongs in assets/css/self-service.css)
  `$bodyClass`   — extra class on <body>
  `$pageHead`    — raw markup for <head> (a page needing an extra script/stylesheet,
                 e.g. the rich-text editor on new-ticket.php). Only ONE page
                 wants that, so it opts in rather than every page loading it.

⚠️ Load order matters: theme.css must come BEFORE self-service.css so the
token definitions are in scope when the portal stylesheet reads them.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 137 |  | read [users_assets](/code/tables/users_assets.md), read [assets](/code/tables/assets.md) |  |
| 153 |  | read [lms_course_assignments](/code/tables/lms_course_assignments.md), read [lms_courses](/code/tables/lms_courses.md) |  |
| 307 |  | read [portal_notifications](/code/tables/portal_notifications.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/self_service_settings.php](/code/files/includes/self_service_settings.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [self-service/includes/auth.php](/code/files/self-service/includes/auth.php.md), [includes/managers.php](/code/files/includes/managers.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md), [includes/back_link.php](/code/files/includes/back_link.php.md), [includes/notification_bell.php](/code/files/includes/notification_bell.php.md), [self-service/includes/user-menu.php](/code/files/self-service/includes/user-menu.php.md)

**Included by:** [self-service/catalogue.php](/code/files/self-service/catalogue.php.md), [self-service/course.php](/code/files/self-service/course.php.md), [self-service/help-centre.php](/code/files/self-service/help-centre.php.md), [self-service/help.php](/code/files/self-service/help.php.md), [self-service/index.php](/code/files/self-service/index.php.md), [self-service/my-equipment.php](/code/files/self-service/my-equipment.php.md), [self-service/new-ticket.php](/code/files/self-service/new-ticket.php.md), [self-service/tickets.php](/code/files/self-service/tickets.php.md), [self-service/training.php](/code/files/self-service/training.php.md)

**Loads scripts:** `self-service/assets/js/tz.js`

**Calls:** [backLinkHtml()](/code/functions/backLinkHtml.md), [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [lmsAssignmentReachSql()](/code/functions/lmsAssignmentReachSql.md), [managerHasTeam()](/code/functions/managerHasTeam.md), [notificationBellRender()](/code/functions/notificationBellRender.md), [selfServicePortalLogoUrl()](/code/functions/selfServicePortalLogoUrl.md), [selfServicePortalSettings()](/code/functions/selfServicePortalSettings.md), [t()](/code/functions/t.md)

