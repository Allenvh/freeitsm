# webchat_widgets

Embed config for one website chat widget. Drives a messaging_channels row (channel_type='webchat', provider='freeitsm'); company routing + active flag live there. widget_key is public (ships in the site's <script>) — abuse is contained by allowed_origins + rate limiting, not by hiding this.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `channel_id` | `INT NOT NULL` |  |
| `widget_key` | `VARCHAR(64) NOT NULL` |  |
| `allowed_origins` | `LONGTEXT NULL` |  |
| `greeting` | `VARCHAR(500) NULL` |  |
| `accent_colour` | `VARCHAR(20) NULL` |  |
| `launcher_text` | `VARCHAR(60) NULL` |  |
| `offline_message` | `VARCHAR(500) NULL` |  |
| `require_email` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `business_calendar_id` | `INT NULL` | Availability (business-hours calendar), offline email delivery, and AI answers. |
| `email_when_away` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `ai_enabled` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `ai_mode` | `VARCHAR(10) NOT NULL DEFAULT 'assist'` |  |
| `ai_offer_agent` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `ai_offer_email` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md):130 |  | update | `allowed_origins`, `greeting`, `accent_colour`, `launcher_text`, `offline_message`, `require_email`, `business_calendar_id`, `email_when_away`, `ai_enabled`, `ai_mode` |  |
| [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md):162 |  | insert | `channel_id`, `widget_key`, `allowed_origins`, `greeting`, `accent_colour`, `launcher_text`, `offline_message`, `require_email`, `business_calendar_id`, `email_when_away` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/webchat/delete_widget.php](/code/files/api/webchat/delete_widget.php.md) | L36 |  |
| [api/webchat/get_widgets.php](/code/files/api/webchat/get_widgets.php.md) | L30 |  |
| [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md) | L109, L157 |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) | webchatLoadByChannel L90, webchatLoadByKey L112 |  |

