<?php
namespace ReportProductionCandidatesModule\ExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use REDCap;

define("TABLE_NAME", "redcap_project_stats");

class ExternalModule extends AbstractExternalModule {

  //main cron executed by redcap every day
  function report_production_candidates_cron() {
    try {
      //create project stats table if it doesn't already exist
      if(!self::check_stats_table_exists()) {
        self::create_stats_table();
        REDCap::logEvent("Created " . TABLE_NAME . " table for the report_production_candidates module.");
      }

      //update table
      self::update_stats_table();
      REDCap::logEvent("report_production_candidates_cron updated the " . TABLE_NAME  . " table.");

    } catch(Exception $e) {
      REDCap::logEvent("Aborting report_production_candidates_cron: " . $e->getMessage());
    }
  }

  //checks if project stats table exists
  function check_stats_table_exists() {
    $result = ExternalModules::query("SHOW TABLES LIKE '" . TABLE_NAME  . "'");

    if(!$result) {
      throw new Exception("cannot access database.");
    }

    if($result->num_rows) {
      return true;
    }

    return false;
  }


  //creates project_stats table
  private function create_stats_table() {
    $result = ExternalModules::query("CREATE TABLE " . TABLE_NAME . " (
                                        project_id int(10) PRIMARY KEY,
                                        saved_attribute_count int(10) UNSIGNED,
                                        last_user varchar(255))");

    if (!$result) {
      throw new Exception("cannot create " . TABLE_NAME . " table.");
    }
  }


  // make sure every project has a row in the stats table
  private function add_rows_to_stats_table() {
    $sql = "INSERT INTO " . TABLE_NAME . " (project_id) select project_id from redcap_projects";
    $result = ExternalModules::query($sql);
  }


  //update project stats table with info from redcap_data
  private function update_saved_attribute_count_in_stats_table() {
    $result = ExternalModules::query("update " . TABLE_NAME . " as ps
      set saved_attribute_count =
      (SELECT COUNT(*) FROM redcap_data as d where ps.project_id = d.project_id)");

    if (!$result) {
      throw new Exception("cannot update " . TABLE_NAME . " table.");
    }
  }

  // update project stats table with info from redcap_log_event
  private function update_last_user_in_stats_table() {
    // Get list of project_ids so we can iterate on them
    $sql = "select project_id from redcap_projects";
    $result = ExternalModules::query($sql);

    //check if query was successful
    if(!$result) {
      exit(0);
    }

    //convert data from mysqli obj to an associative array
    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    foreach ($data as $project) {
      $pid = $project["project_id"];
      $result = ExternalModules::query("update " . TABLE_NAME . "
        set last_user = (SELECT user FROM redcap_log_event as el inner join
        redcap_user_rights as ur on el.user = ur.username and el.project_id = ur.project_id and ur.project_id=$pid
        order by ts desc limit 1) where project_id = $pid;");

      if (!$result) {
        throw new Exception("cannot update " . TABLE_NAME . " table.");
      }
    }

  }

  private function update_stats_table() {
    self::add_rows_to_stats_table();
    self::update_saved_attribute_count_in_stats_table();
    self::update_last_user_in_stats_table();
  }

  /*takes in an email and returns a properly formated email link while also
    adhearing to the template provided by the user.*/
  function get_mailer_link($email, $data) {
    $cc = $this->getSystemSetting("rpc_cc");

    $subject = $this->getSystemSetting("rpc_subject");
    $subject = $this->pipe_to_template($subject, $data);

    $body = $this->getSystemSetting("rpc_body");
    $body = $this->pipe_to_template($body, $data);

    $link = "mailto:" . $email . "?cc=";

    //add emails in cc list
    for($i = count($cc) - 1; $i >= 0; $i--) {
      $link .= $cc[$i];
      if($i > 0) {
        $link .= ";";
      }
    }

    $link .= "&subject=" . rawurlencode($subject);
    $link .= "&body=" . rawurlencode($body);

    return $link;
  }


/**
 * Pipes the data in $data into the appropriate spots in $template
 *
 * Example: "Hello, [first_name]!" turns into "Hello, Joe Doe!".
 *
 * @param string $template
 *   The template to be piped to.
 * @param array $data
 *   A dictionary of source data. The key corresponds to the parameter the
 *   function looks for in $template. The key's value is the thing that is actually
 *   put into the template. This function supports nesting values, which are mapped
 *   on the subject string as nesting square brackets (e.g. [user][first_name]).
 *
 * @return string
 *   The processed string, with the replaced values from source data.
 */
protected function pipe_to_template($subject, $data) {
    preg_match_all('/(\[[^\[]*\])+/', $subject, $matches);

    foreach ($matches[0] as $wildcard) {
        $parts = substr($wildcard, 1, -1);
        $parts = explode('][', $parts);

        $value = '';
        if (count($parts) == 1) {
            // This wildcard has no children.
            if (isset($data[$parts[0]])) {
                $value = $data[$parts[0]];
            }
        }
        else {
            $child = array_shift($parts);
            if (isset($data[$child]) && is_array($data[$child])) {
                // Wildcard with children. Call function recursively.
                $value = send_rx_piping('[' . implode('][', $parts) . ']', $data[$child]);
            }
        }

        // Search and replace.
        $subject = str_replace($wildcard, $value, $subject);
    }

    return $subject;
}


}

?>
