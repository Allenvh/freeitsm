# includes/domains/customer.php

> PHP - 120 lines

Domains — the CUSTOMER a domain is looked after for (#153).

A domain already belongs to a company (tenant_id), so the customer is just a
person: someone from the users table, in the domain's own company. There is
deliberately no second company column — the domain's company IS the
customer's company.

#162 widened it: the customer may instead be a SUPPLIER - in practice the
install's list of organisations - and optionally one of that supplier's
contacts. A domain names a person OR a supplier, never both. Suppliers and
their contacts are install-wide (they have no company), so naming one leaks
nothing between companies.

Every read and write checks `domainCustomerReady()` / `domainPartiesReady()`
first, so an install that has not run Database Verification since upgrading
keeps working without the columns instead of failing every domain query.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainCustomerReady()](/code/functions/domainCustomerReady.md) | 24 | Has Database Verification added domains.customer_user_id yet? |
| [domainPartiesReady()](/code/functions/domainPartiesReady.md) | 34 | Has Database Verification added the #162 columns (customer_supplier_id, customer_contact_id, tech_analyst_id) yet? All three arrive together. |
| [domainCustomerPersonOk()](/code/functions/domainCustomerPersonOk.md) | 49 | May `$userId` be the customer of a domain in company `$tenantId`? An active user in that company; on a single-company install, any active user. A user with no company counts as the Default company's, as everywhere else. |
| [domainCustomerSupplierSearch()](/code/functions/domainCustomerSupplierSearch.md) | 71 | Suppliers and supplier contacts matching `$q`, for the same picker (#162). A contact carries its supplier, so choosing one sets both. |
| [domainCustomerSearch()](/code/functions/domainCustomerSearch.md) | 103 | People who could be the customer of a domain in company `$tenantId`, matching `$q`. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 | `domainCustomerReady` | read [domains](/code/tables/domains.md) |  |
| 38 | `domainPartiesReady` | read [domains](/code/tables/domains.md) |  |
| 51 | `domainCustomerPersonOk` | read [users](/code/tables/users.md) |  |
| 75 | `domainCustomerSupplierSearch` | read [suppliers](/code/tables/suppliers.md) |  |
| 82 | `domainCustomerSupplierSearch` | read [contacts](/code/tables/contacts.md), read [suppliers](/code/tables/suppliers.md) |  |
| 106 | `domainCustomerSearch` | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/domains/people.php](/code/files/api/domains/people.php.md), [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), [includes/domains/read.php](/code/files/includes/domains/read.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md)

**Calls:** [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

