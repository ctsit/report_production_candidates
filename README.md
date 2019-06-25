# Report Production Candidates

This REDCap module creates and displays a list of REDCap projects that should probably be moved into production. This module is integrated with Stanford University's [Go to Prod plugin](https://github.com/aandresalvarez/go_to_prod) and provides an interface for REDCap Admins to contact owners of projects for follow up.

## Prerequisites
- REDCap >= 8.0.3 (for versions < 8.0.3, [REDCap Modules](https://github.com/vanderbilt/redcap-external-modules) is required).
- A go\_to\_prod plugin installed on your REDCap instance:
  - The original [go\_to\_prod plugin](https://github.com/aandresalvarez/go_to_prod)
  - [goprod\_v2.0](https://github.com/aandresalvarez/goprod_v2.0) (not currently functional)

## System-level Installation
1. Clone this repo into `<redcap-root>/modules/report_production_candidates_v<module_version_number>`.
2. Go to **Control Center > Manage External Modules** and enable _Report Production Candidates_.

## Configuration
- Users must choose which version of the plugin they wish to use.
- Users can optionally preload an email template that will be used when they click a username in the report.  See [Sample Email Configuration](samples/email_configuration.md) for an example.

## Using this module
Go to **Control Center > Report Production Candidates** and to view the reports. Admins can use the Go to Prod button to review the project's production readiness and move the project into production. They can also click on REDCap usernames within the report to send those users an email. This email can be pre-filled with a template via the module's configuration page.

## Notes regarding initial load of the report

Report Production Candidates can seem very slow on the initial load. Please be patient if the first display of report takes many minutes. On a large or old REDCap system this is completely normal. Fortunately this will only happen on the _very first_ load of the report.

Report Production Candidates uses summary data about projects that can only be acquired through queries of some of the largest tables in the REDCap database. On a large REDCap system with lots of activity or lots of stored data, these tables have millions of records. To circumvent the delays caused by querying these tables, this module uses a nightly cron job to gather the summary data. It writes the summary data into a table it can quickly query when running the report. That said, when first enabling the module, that table is empty. The reporting script detects the empty table and starts the initial run of the cron job that refreshes the table. That is why the first load of the report is slow. Normal report display should be about 50 projects/second.
