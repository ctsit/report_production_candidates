<?php
use ExternalModules\ExternalModules;

require_once APP_PATH_DOCROOT . 'ControlCenter/header.php';
require_once 'helper.php';

//Run the cron if it has not ran for the first time
$module = new ReportProductionCandidatesModule\ExternalModule\ExternalModule();
if (!$module->check_stats_table_exists()) {
  $module->report_production_candidates_cron();
}

//get data from db
$sql = "SELECT
          redcap_projects.project_id,
          redcap_projects.app_title AS project_title,
          redcap_record_counts.record_count,
          redcap_project_stats.saved_attribute_count,
          DATEDIFF(NOW(), redcap_projects.creation_time) AS age,
          redcap_projects.project_pi_firstname,
          redcap_projects.project_pi_lastname,
          redcap_projects.project_pi_email,
          redcap_projects.created_by AS creator_id,
          redcap_projects.purpose AS purpose_num,
          redcap_projects.last_logged_event AS most_recent_activity
        FROM redcap_project_stats
          INNER JOIN redcap_projects
            ON redcap_project_stats.project_id = redcap_projects.project_id
          INNER JOIN redcap_record_counts
            ON redcap_project_stats.project_id = redcap_record_counts.project_id
        WHERE
          redcap_projects.status = 0
          AND redcap_projects.purpose != 0
          AND (redcap_record_counts.record_count > 100
          OR redcap_project_stats.saved_attribute_count > 500)
          AND DATEDIFF(NOW(), redcap_projects.creation_time) > 30";

$result = ExternalModules::query($sql);

//check if query was successful
if(!$result) {
  echo "<p> Cannot generate report due to an internal database issue </p>";
  require_once APP_PATH_DOCROOT . 'ControlCenter/footer.php';
  exit(0);
}

//convert data from mysqli obj to an associative array
$result = $result->fetch_all(MYSQLI_ASSOC);

//start printing data table
echo "<table class='dataTable cell-border'>
        <thead>
            <tr>
              <th></th>
              <th>PID</th>
              <th>Project Name</th>
              <th>Records</th>
              <th>Saved Attributes</th>
              <th>Age(Days)</th>
              <th>PI Name</th>
              <th>Creator</th>
              <th>Purpose</th>
              <th>Most Recent Activity</th>
              <th>Last User</th>
            </tr>
        </thead>
        <tbody>";

//print table body
$odd_row = true;
foreach ($result as $project) {

  //process data into useful information
  $project["go_prod_url"] = APP_PATH_WEBROOT_FULL . "plugins/go_prod/?pid=" . $project["project_id"];
  $project["project_home_url"] = APP_PATH_WEBROOT_FULL . "redcap_v" . REDCAP_VERSION . "/ProjectSetup/index.php?pid=" . $project["project_id"];
  $project["creator_username"] = uid_to_username($project["creator_id"]);
  $project["creator_email"] = get_user_email($project["creator_username"]);
  $project["last_user"] = get_last_user($project["project_id"]);
  $project["last_user_email"] = get_user_email($project["last_user"]);
  $project["purpose"] = purpose_num_to_purpose_name($project["purpose_num"]);

  echo $odd_row ? "<tr class='odd'>" : "<tr class='even'>";
  echo "<td><a href='" . $project["go_prod_url"] . "' class='btn btn-default'>Go to Prod</td>";
  echo "<td><a href='" . $project["project_home_url"] . "'>" . $project["project_id"] . "</a></td>";
  echo "<td><a href='" . $project["project_home_url"] . "'>" . $project["project_title"] . "</a></td>";
  echo "<td>" . $project["record_count"] . "</td>";
  echo "<td>" . $project["saved_attribute_count"] . "</td>";
  echo "<td>" . $project["age"] . "</td>";

  //Not all projects have pi's so format output accordingly
  if(empty($project['project_pi_firstname'])) {
    echo "<td> No Data </td>";
  } else {
    $link = $module->get_mailer_link($project['project_pi_email'], $project);
    echo "<td><a href='" . $link . "'>" . $project["project_pi_firstname"] . " " . $project["project_pi_lastname"] . "</td>";
  }

  //sometimes creators can not be found on the system so we format output accordingly
if($project["creator_username"]) {
      $link = $module->get_mailer_link($project["creator_email"], $project);
      echo "<td><a href='" . $link . "'>" . $project["creator_username"] . "</a></td>";
  } else {
      echo "<td>Could not find creator's name</td>";
  }

  //print out project purpose and the when the most recent activity was
  echo "<td>" . $project["purpose"] . "</td>";
  echo "<td>" . $project["most_recent_activity"] . "</td>";

  //print out last project user
  $link = $module->get_mailer_link($project["last_user_email"], $project);
  echo "<td><a href='" . $link . "'>" . $project["last_user"] . "</a></td>";
  echo "</tr>";

  //update row to maintain consistant css
  $odd_row = !$odd_row;
}

echo "</tbody></table>";

require_once APP_PATH_DOCROOT . 'ControlCenter/footer.php';
?>
