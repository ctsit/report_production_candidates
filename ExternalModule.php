<?php
namespace ReportProductionCandidatesModule\ExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

define("TABLE_NAME", "redcap_project_stats");

class ExternalModule extends AbstractExternalModule {


  private function check_stats_table_exists() {

    $result = ExternalModules::query("SHOW TABLES LIKE '" . TABLE_NAME  . "'");

    if(!$result) {
      throw new Exception("cannot access database.");
    }

    if($result->num_rows) {
      return true;
    }

    return false;
  }

  private function create_stats_table() {
    $result = ExternalModules::query("CREATE TABLE " . TABLE_NAME . " (project_id int(10), record_count int(10) UNSIGNED, saved_attribute_count int(10) UNSIGNED)");
    if (!$result) {
      throw new Exception("cannot create " . TABLE_NAME . " table.");
    }
  }


}

?>
