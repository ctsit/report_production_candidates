<?php

  use ReportProductionCandidatesModule\ExternalModule\ExternalModule;

  function purpose_num_to_purpose_name($purpose_num) {
    switch($purpose_num) {
      case 0: return "Practice / Just for fun";
      case 1: return "Other";
      case 2: return "Research";
      case 3: return "Quality Improvement";
      case 4: return "Operational Support";
    }
  }

  function uid_to_username( $uid ) {

    $externalModule = new ExternalModule();
    $sql = "SELECT username FROM redcap_user_information WHERE ui_id='$uid'";
    $result = $externalModule->runSQL( $sql, true );
    if ( !$result ) {
      return false;
    }

    return $result["username"];
  }

  function get_last_user( $pid ) {

    $externalModule = new ExternalModule();
    $sql = "SELECT last_user FROM " . TABLE_NAME . " where project_id=$pid";
    $result = $externalModule->runSQL( $sql, true );
    if ( !$result ) {
      return false;
    }

  return $result["last_user"];
}

  function get_user_email( $username ) {

    $externalModule = new ExternalModule();
    $sql = "SELECT user_email FROM redcap_user_information WHERE username='$username'";
    $result = $externalModule->runSQL( $sql, true );
     if ( !$result ) {
       return false;
     }

   return $result["user_email"];
 }

?>
