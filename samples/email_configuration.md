# Sample Email Configuration

The emails drafted by the `report_production_candidates` plugin are built upon a template stored in the system-level module configuration. That configuration is blank by default.  It needs to be configured to provide a message subject and body.


## Parameters

Both the subject and the body of the email support a simple parameter substitution not unlike REDCap data piping. These parameters are supported:

    * project_title
    * project_home_url
    * go_prod_url
    * creator_username
    * creator_email
    * last_user
    * last_user_email
    * purpose
    * project_id
    * record_count
    * saved_attribute_count
    * project_age
    * project_pi_firstname
    * project_pi_lastname
    * project_pi_email
    * most_recent_activity

Parameters must be enclosed in square brackets to be recognized by the template engine.  e.g. [project_title] will be replaced by the REDCap project's title. Keep in mind that some parameters can be substituted by an empty value. For example, if you use 'project_pi_firstname' as a parameter with a project that does not have a PI then that parameter will be substituted by an empty string.


## Sample message

Here is a sample message from a generic REDCap support team informing the recipient about the need to move a particular project to production.

> CC: redcap-support@example.org
>
> Subject: Moving your REDCap project to production
>
> Body:
>
> The REDCap project "[project name]", accessible at [project_home_url], may need some attention to assure your data is properly protected.  The amount of data stored within it suggests your data might be better protected if the project were moved into REDCap's production status. Production status turns on a data audit trail so that one can always answer the question "Who changed what when?". Production mode also allows data dictionary checks to be reviewed before implementation. REDCap's automated review can generate warnings whenever a data dictionary change would put data at risk and give you the option to reconsider those changes.
>
> We are contacting you directly to engage you in a discussion about whether this project should move to production and what steps would be needed to make that happen. We would have made the move to production ourselves, but our reports suggest the project may need some changes before it can safely be moved to production.
>
> If you are interested in the data protections production status affords your data, please use review tool at [go_prod_url] to check your project and address any issues found. Once all of the issues are addressed, click the "Request to move project to production" link at the Project Setup page and take the "Move the production survey checklist" that appears in the pop up box.
>
> Regards,
> Your REDCap Support Team
