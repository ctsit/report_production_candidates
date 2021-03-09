# Report Production Candidates

This REDCap module shows a list of REDCap projects that probably should be moved into production. It allows admins to contact owners of projects for follow up and provides optional support for the University of Florida's fork of Stanford University's <a href='https://github.com/ctsit/goprod'>Go to Prod module</a>.

## Prerequisites
- REDCap >= 9.7.8

## Easy Installation
- Obtain this module from the Consortium [REDCap Repo](https://redcap.vanderbilt.edu/consortium/modules/index.php) from the control center.

## Manual installation
- Clone this repo into `<redcap-root>/modules/report_production_candidates_v<module_version_number>`.

## Optional installation
- Clone the repo https://github.com/ctsit/goprod into `<redcap-root>/modules/report_production_candidates_v<module_version_number>`. RPC will automatically detect its presence and provide links from candidate projects list into the GoProd project review workflow.

## Configuration
- Users can optionally preload an email template that will be used when they click a username in the report.  See [Sample Email Configuration](samples/email_configuration.md) for an example.
- Users may also toggle whether or not to provide a link to the Go to Prod module page for each project listed in their report.

## Using this module
Go to **Control Center > Report Production Candidates** and to view the reports. If use of the Go to Prod module is enabled in the configuration, admins can use the Go to Prod link to review the project's production readiness and move the project into production. They can also click on REDCap usernames within the report to send those users an email. This email can be pre-filled with a template via the module's configuration page.

## Notes regarding initial load of the report

Report Production Candidates can seem very slow on the initial load. Please be patient if the first display of report takes many minutes. On a large or old REDCap system this is completely normal. Fortunately this will only happen on the _very first_ load of the report.

Report Production Candidates uses summary data about projects that can only be acquired through queries of some of the largest tables in the REDCap database. On a large REDCap system with lots of activity or lots of stored data, these tables have millions of records. To circumvent the delays caused by querying these tables, this module uses a nightly cron job to gather the summary data. It writes the summary data into a table it can quickly query when running the report. That said, when first enabling the module, that table is empty. The reporting script detects the empty table and starts the initial run of the cron job that refreshes the table. That is why the first load of the report is slow. Normal report display should be about 50 projects/second.
