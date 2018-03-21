# Change Log
All notable changes to the Report Production Candidates module will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [1.0.2] - 2018-03-21
### Added
- Add caching of last_user information for performance reasons (Philip Chase)
- Add documentation (Philip Chase)

### Changed
- Streamline display (Philip Chase)


## [1.0.1] - 2018-02-02
### Added
- Add Marly Cormar to the list of authors (Dileep)

### Changed
- Replace call to mysqli_result::fetch_all() with a loop to comply with php5 (Dileep)

## [1.0.0] - 2018-02-02
### Summary
- This is the first release

### Added
- Remove ToDo section of README as that work was done. (Philip Chase)
- Fix author institutions (Philip Chase)
- Set minimum REDCap version (Philip Chase)
- Fix typo in README (Philip Chase)
- Set minimum width of most_recent_activity column (Philip Chase)
- Rescale project title column to accommodate larger project titles (Dileep)
- Add support for parameter substitution in the subject line and correct typo in the example template (Philip Chase)
- Describe new functionality in email_configuration.md (Dileep)
- rename key 'age' to 'project_age' in project variable (Dileep)
- Change default message when no PI is found to something more descriptive (Dileep)
- Simplify logic for printing PI name (Dileep)
- rename 'project_name' key in project variable to 'project_title' (Dileep)
- consolidate project data into single variable (Dileep)
- Remove repeated section from email_configuration.md (Dileep)
- Remove repeating 'mailto:' from email links (Dileep)
- Modify calls to get_mailer_link to substitute desired parameters into email body (Dileep)
- Modify get_mailer_link method to substitute parameters within the email template with data (Dileep)
- Add method pipe parameter substitution data into email templates (Dileep)
- Add variable to hold parameter substitution data for each project (Dileep)
- Remove html tags from sample email template (Philip Chase)
- Add instructions for project owners in the sample email template (Philip Chase)
- Revise description in config.json (Philip Chase)
- Add a sample email configuration, corresponding changes in the README, and a ToDo list (Philip Chase)
- Clean up comment (Philip Chase)
- Remove redundant 'mailto' from email link (Philip Chase)
- Revert "Change email body content from text to rich-text" (Dileep)
- Change email body content from text to rich-text (Dileep)
- Add comments to clarify code in report.php and ExternalModule.php (Dileep)
- Refactor project links in report.php (Dileep)
- Update README (Dileep)
- Modify mailer links to provide a email template specified by project configurations (Dileep)
- Modify report.php to run the cron if it has not ran for the first time (Dileep)
- Update prerequisites in README (Dileep)
- Add 'go to prod' button to report.php (Dileep)
- Add mailto link to last user column in report.php (Dileep)
- Modify get_last_user to not return survey_respondents as users (Dileep)
- Add mailto links to the PI Name and Creator columns in report.php (Dileep)
- Add get_user_email method to helper.php (Dileep)
- Modify project_id and project_name columns into links to the corresponding project page (Dileep)
- Modify report.php to display the last user on a project (Dileep)
- Modify view to display production candidate information (Dileep)
- Add a plugin page for the report (Dileep)
- Add comments to clarify function of ExternalModule methods (Dileep)
- Modify cron to update project_stats table when it runs (Dileep)
- Add cron that creates project_stats table if it does not already exist (Dileep)
- Add method to create project_stats table (Dileep)
- Add method to check if project_stats table exists (Dileep)
- Add module skeleton (Dileep)
- Initial commit (Dileep)
- Initial commit (Dileep)