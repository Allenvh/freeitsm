# people/includes/render.php

> PHP - 384 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [People Developer Guide](https://github.com/edmozley/freeitsm/wiki/People-Developer-Guide)

People — the section cards a person page and a company page share.

Server-rendered: the page is one read of includes/people.php, and every
section in it has already been through its module's access and company
checks. A section the reader may not see is simply not in `$sections`, so
nothing here has to know about permissions.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [pplE()](/code/functions/pplE.md) | 20 | HTML-escapes any value for output. |
| [pplUrl()](/code/functions/pplUrl.md) | 26 | An app-relative path made absolute with BASE_URL; '#' when there is none. |
| [pplStats()](/code/functions/pplStats.md) | 35 | The coloured strip of counts across the top of a page. |
| [pplCard()](/code/functions/pplCard.md) | 67 | One section card: a heading with its count, then a table or "Nothing yet". |
| [pplLink()](/code/functions/pplLink.md) | 93 | A link to an app-relative URL, or just the escaped text when the reader has nowhere to go. |
| [pplPill()](/code/functions/pplPill.md) | 105 | A status pill, optionally in a colour (passed as the --pill CSS variable); '' for empty text. |
| [pplSectionTickets()](/code/functions/pplSectionTickets.md) | 118 | The Tickets card: number, subject, status, priority, then the analyst (person page) or requester (company page), and the open/total count. |
| [pplSectionAssets()](/code/functions/pplSectionAssets.md) | 142 | The Assets card: asset, type, model, status, then the date assigned (person page) or who holds it (company page). |
| [pplSectionContracts()](/code/functions/pplSectionContracts.md) | 163 | The Contracts card: contract, the other party (unless `$withParty` is false), status and end date, with inactive contracts marked. |
| [pplSectionDomains()](/code/functions/pplSectionDomains.md) | 186 | The Domains card: domain, the roles it plays when the rows carry them, status, expiry and grade. |
| [pplSectionCoursesPerson()](/code/functions/pplSectionCoursesPerson.md) | 207 | A person's Courses card: each course with its status pill, progress (or completion date) and deadline, overdue ones marked. |
| [pplSectionCoursesCompany()](/code/functions/pplSectionCoursesCompany.md) | 230 | A company's Courses card: each course with how many of its people have finished it and how many are part-way. |
| [pplSectionForms()](/code/functions/pplSectionForms.md) | 243 | The Forms card: form, when submitted, who by (company page), approval state and the ticket it raised. |
| [pplRaciDuties()](/code/functions/pplRaciDuties.md) | 265 | A person's RACI duties on one project, one line per letter: "Accountable for Phones working on day one, Every desk patched and 2 more". The full list is the line's tooltip. |
| [pplSectionProjects()](/code/functions/pplSectionProjects.md) | 283 | Projects (3.2.0): a person's memberships with their role and RACI duties, or a company's projects. |
| [pplSectionContractorTasks()](/code/functions/pplSectionContractorTasks.md) | 309 | Contractors (3.3.0): the tasks given to a supplier, or to one person there. |
| [pplSections()](/code/functions/pplSections.md) | 331 | Every section present, in a fixed order. |
| [pplHead()](/code/functions/pplHead.md) | 351 | The <head> every People page shares. `$extraCss`: page stylesheets, linked BEFORE mobile.css so the mobile layer still wins its ties (Techniques §9). |
| [pplInitials()](/code/functions/pplInitials.md) | 377 | Two letters for an avatar. |

## How it hangs together

**Included by:** [people/companies.php](/code/files/people/companies.php.md), [people/company.php](/code/files/people/company.php.md), [people/contact.php](/code/files/people/contact.php.md), [people/help.php](/code/files/people/help.php.md), [people/index.php](/code/files/people/index.php.md), [people/person.php](/code/files/people/person.php.md), [people/supplier.php](/code/files/people/supplier.php.md), [people/suppliers.php](/code/files/people/suppliers.php.md)

**Calls:** [fmt_date()](/code/functions/fmt_date.md), [fmt_datetime()](/code/functions/fmt_datetime.md), [pageTitle()](/code/functions/pageTitle.md), [peopleBareDate()](/code/functions/peopleBareDate.md), [t()](/code/functions/t.md)

