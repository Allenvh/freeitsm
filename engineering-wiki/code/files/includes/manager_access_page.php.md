# includes/manager_access_page.php

> PHP - 697 lines

Manager access - one manager's management lines, FULL SCREEN (discussion #62).

WHY A PAGE AND NOT A MODAL
--------------------------
Ed: "some people will work with large organisations with lots of departments
and lots of users, so if we are making a picker to grant them access then this
should be full screen not in a modal." So: a page, searched and paged on the
server (api/tickets/manager_access.php), never "load everyone".

ONE body, two doors - like the person editor. tickets/manager-access.php and
asset-management/manager-access.php each print their own module's header and
call `managerAccessRender()`; System -> Managers links to the Tickets one.

Strings are resolved here, not exported: Assets pages do not export the
tickets namespace to JavaScript.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [managerAccessRender()](/code/functions/managerAccessRender.md) | 26 | Print the page body (below the module header). Call once, inside <body>. |

## How it hangs together

**Included by:** [asset-management/manager-access.php](/code/files/asset-management/manager-access.php.md), [tickets/manager-access.php](/code/files/tickets/manager-access.php.md)

**Calls:** [t()](/code/functions/t.md)

