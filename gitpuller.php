<?php
// wird von GitHub als Webhook bei jeden Push zum Repository aufgerufen
if ($_GET["token"] = "PRNuxt8F3X5Kd45qxzTpJBDanYdWXSSccBbXD4NQ") {
  exec("git pull");
}

?>
