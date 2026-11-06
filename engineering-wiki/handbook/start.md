# Engineering wiki

How FreeITSM is built - for the developer who wants to know how it all hangs together: which file includes which, where the shared functions live, how an API endpoint is protected, what each table and column is for, and why things were done the way they were.

> [!NOTE]
> This wiki is for people who change FreeITSM's code. Nothing here is needed to run a service desk.

## Two kinds of page

| Section | Written by | What it holds |
|---|---|---|
| **Handbook** | people | The *why*: how the parts fit together, the decisions behind them, the traps. |
| **Code map** | the scanner | The *what*: every file, function, API endpoint and table, and what uses what. |

The scanner never touches the Handbook, so a page written here is safe. The Code map is rebuilt from the code itself, so it is never out of date for long - and it is only as good as the comments it reads. [[handbook/comments|Writing comments for the wiki]] explains the few conventions that turn a comment into documentation.

## Getting around

It works like a browser:

- **Click** a link to open it in the current tab. **Back** and **Forward** work as you would expect.
- **Right-click** a link for *Open in new tab*, *Open beside* (a second pane, side by side) and *Copy link*.
- **Ctrl+click** or **middle-click** a link to open it in a new tab.
- The **search** box looks through every page, both sections.
- *Copy link* gives an address that opens straight at that page, so you can paste it into a ticket or a chat.

## Start here

- [[handbook/comments|Writing comments for the wiki]] - the conventions the scanner reads
