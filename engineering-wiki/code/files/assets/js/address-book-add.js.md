# assets/js/address-book-add.js

> JavaScript - 97 lines

"Add to address book" for a person who is not in one (#133).

Shared by Tickets → Users and Assets → Users, so the two screens cannot
disagree about when it is offered or what it says. The server decides both:
this only draws what api/tickets/address_book_add.php returns, and asks
before writing - a new contact in somebody's address book is not a thing to
do on a single click.

  AddressBookAdd.mount(hostElement, userId, personName, apiBase, onDone)

## Functions

| Function | Line | What it does |
|---|--:|---|
| `t(k, p)` | 17 | Translates a key under common.address_book_add. |
| `confirmText(book, person)` | 23 | The confirmation message for adding a person to an address book - what will be written, any category or group it is filed under, and the checks the server makes. |
| `add(book, userId, person, apiBase, onDone, btn)` | 38 | Asks for confirmation, then adds the person to the address book through address_book_add.php and calls onDone when it worked. The button is disabled while the request runs. |
| `mount(host, userId, person, apiBase, onDone)` | 72 | Draw the button(s) into `host` if this person can be added anywhere. One address book: one button naming it. Several: one each. None, or a person who is already linked: nothing at all. |

## Loaded by

- [asset-management/users.php](/code/files/asset-management/users.php.md)
- [tickets/users.php](/code/files/tickets/users.php.md)

