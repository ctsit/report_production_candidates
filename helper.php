<?php

use ExternalModules\ExternalModules;

function purpose_num_to_purpose_name($purpose_num) {
  switch($purpose_num) {
    case 0: return "Practice / Just for fun";
    case 1: return "Other";
    case 2: return "Research";
    case 3: return "Quality Improvement";
    case 4: return "Operational Support";
  }
}

function uid_to_username($uid) {
  $result = ExternalModules::query("SELECT username FROM redcap_user_information WHERE ui_id='$uid'");
  if (!$result) {
    return false;
  }
  return $result->fetch_assoc()["username"];
}

function get_last_user($pid) {
  $result = ExternalModules::query("SELECT user FROM redcap_log_event WHERE ts IN (SELECT MAX(ts) FROM redcap_log_event WHERE project_id='$pid')");
  if (!$result) {
    return false;
  }

  return $result->fetch_assoc()["user"];
}

?>
