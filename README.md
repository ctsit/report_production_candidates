# Report Production Candidates

This REDCap module creates and displays a list of REDCap projects that should be moved into production. This module is integrated with Stanford University's [Go to Prod plugin](https://github.com/aandresalvarez/go_to_prod) and provides an interface for REDCap Admins to contact owners of projects for follow up.

## Prerequisites
- REDCap >= 8.0.0 (for versions < 8.0.0, [REDCap Modules](https://github.com/vanderbilt/redcap-external-modules) is required).
- [go_to_prod plugin](https://github.com/aandresalvarez/go_to_prod) installed on your REDCap instance.

## System-level Installation
1. Clone this repo into to `<redcap-root>/modules/report_production_candidates_v<module_version_number>`.
2. Go to **Control Center > Manage External Modules** and enable _Report Production Candidates_.

## Configuration
- Users can optionally preload an email template that will be used when they click a username in the report.  See [Sample Email Configuration](samples/email_configuration.md) for an example.

## Using this module
Go to **Control Center > Report Production Candidates** and to view the reports. Admins can use the Go to Prod button to review the project's production readiness and move the project into production. They can also click on REDCap usernames within the report to send those users an email. This email can be pre-filled with a template via the module's configuration page.

## ToDo

Add support for parameters described in [Sample Email Configuration](samples/email_configuration.md)
