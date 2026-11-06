# assets/js/notification-bell.js

> JavaScript - 360 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Notifications Developer Guide](https://github.com/edmozley/freeitsm/wiki/Notifications-Developer-Guide)

The notification bell - ONE script for both the analyst app (discussion #55) and the self-service portal (discussion #62).

NotificationBell.init({
      list:       URL returning { unread, notifications } (and just { unread }
                  with count_only=1),
      markRead:   URL taking { ids } or { all },
      clear:      URL taking { ids } or { all, include_unread },
      linkPrefix: prepended to each notification's relative link,
      seenKey:    sessionStorage key for the chime's baseline
  });

The markup comes from `notificationBellMarkup()` (includes/notification_bell.php)
and the look from assets/css/notification-bell.css. Everything below moved
here from includes/waffle-menu.php unchanged apart from the URLs, so the two
bells cannot drift: a fix to one is a fix to both.

Needs window.t (i18n.js) for the common.notifications.* strings, and uses
showConfirm / showToast when present.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `init(cfg)` | 26 | Wires up the bell on this page - badge, panel, mark-read, clear and the 60-second poll - from the URLs in cfg. |
| `esc(s)` | 40 | Escapes a value for safe use in HTML. |
| `ago(utc)` | 45 | Stored UTC without a zone marker; left as-is, Safari and Firefox read it as local time and every notification looks hours old. |
| `describe(n)` | 58 | The notification's one-line description in the viewer's language, falling back to the actor's name when no string exists for the event type. |
| `render(items)` | 67 | Draws the notification rows into the panel, or the empty message when there are none. |
| `paintBadge(unread)` | 95 | Shows the unread count on the bell (capped at 99+) and hides the badge at zero. |
| `recordSeen(unread)` | 110 | Stores the unread count as this tab's baseline for the chime. |
| `chimeIfNew(unread)` | 114 | Plays the chime and raises desktop notifications when the unread count has risen since the last poll in this tab. |
| `desktopNotify()` | 138 | Raises desktop notifications for unread items newer than any already shown, at most three plus a "more" line; the first run only records a baseline. |
| `onclick()` | 166 | Clicking the desktop notification focuses the tab and opens what it was about. |
| `onclick()` | 179 | Clicking the "more" notification focuses the tab and opens the bell panel. |
| `poll()` | 188 | Fetches the unread count only, repaints the badge and chimes if something new arrived. |
| `open()` | 196 | Opens the panel and loads the full list, re-baselining the chime because the viewer is now looking at it. |
| `clearOne(id, item)` | 257 | Awaited, unlike mark-read: this one removes something from the screen, so a row must not vanish before the server has agreed that it is gone. |
| `doClearAll(includeUnread)` | 333 | Deletes notifications for good - read ones only unless includeUnread - then reloads the panel, saying so when nothing was cleared. |

