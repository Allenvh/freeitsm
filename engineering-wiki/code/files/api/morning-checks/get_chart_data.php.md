# api/morning-checks/get_chart_data.php

> API endpoint - 120 lines

API Endpoint: Get Chart Data for Morning Checks (Last 30 Days)

Returns one dataset per ACTIVE status (so admins can add/remove
status options and the chart picks up the change). Historical
results saved against a status that's since been deactivated /
deleted won't appear in the chart but the underlying row keeps
its label string.

Response shape:
  {
    dates:    ["May 25", ...]            // 30 human-readable labels
    rawDates: ["2026-05-25", ...]        // matching ISO dates (for click-through)
    datasets: [
      { label: "Green", colour: "#28a745", data: [0,1,2,...] },
      ...
    ]
  }

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/get_chart_data.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson) |
| Reads | `GET endDate` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

