# photo_album

System -> Photo Album: webcam photos as ASCII art. Only the art is stored, never the photo. Personal to each analyst.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `title` | `VARCHAR(150) NOT NULL` |  |
| `style` | `VARCHAR(20) NOT NULL DEFAULT 'braille'` |  |
| `palette` | `VARCHAR(20) NOT NULL DEFAULT 'green'` |  |
| `width_chars` | `SMALLINT NOT NULL` |  |
| `height_chars` | `SMALLINT NOT NULL` |  |
| `art` | `MEDIUMTEXT NOT NULL` |  |
| `colours` | `MEDIUMTEXT NULL` |  |
| `thumbnail` | `MEDIUMTEXT NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/photo_album.php](/code/files/api/system/photo_album.php.md):158 |  | insert | `analyst_id`, `title`, `style`, `palette`, `width_chars`, `height_chars`, `art`, `colours`, `thumbnail`, `created_datetime` |  |
| [api/system/photo_album.php](/code/files/api/system/photo_album.php.md):167 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/photo_album.php](/code/files/api/system/photo_album.php.md) | L78, L97 |  |

