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
                                        saved_attribute_count int(10) UNSIGNED)");

    if (!$result) {
      throw new Exception("cannot create " . TABLE_NAME . " table.");
    }
  }

  //updates project stats table with info from redcap_data
  private function update_stats_table() {
    $result = ExternalModules::query("REPLACE INTO " . TABLE_NAME . " (
                                        project_id,
                                        saved_attribute_count)
                                      SELECT
                                        project_id,
                                        COUNT(*) AS saved_attribute_count
                                      FROM redcap_data
                                      GROUP BY project_id;");

    if (!$result) {
      throw new Exception("cannot update " . TABLE_NAME . " table.");
    }
  }

  /*takes in an email and returns a properly formated email link while also
    adhearing to the template provided by the user.*/
  function get_mailer_link($email) {
    $cc = $this->getSystemSetting("rpc_cc");
    $subject = $this->getSystemSetting("rpc_subject");
    $body = $this->getSystemSetting("rpc_body");

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
}

?>
