# self-service/new-ticket.php

> PHP - 1146 lines

Self-Service Portal — New ticket.

Chrome (head, theme, header, nav, footer) comes from includes/header.php and
includes/footer.php; shared styling from assets/css/self-service.css.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1081 |  | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |

## How it hangs together

**Includes:** [self-service/includes/header.php](/code/files/self-service/includes/header.php.md), [self-service/includes/record-modal.php](/code/files/self-service/includes/record-modal.php.md), [self-service/includes/footer.php](/code/files/self-service/includes/footer.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [t()](/code/functions/t.md)

