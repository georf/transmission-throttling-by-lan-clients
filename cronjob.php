<?php
(php_sapi_name() === 'cli') || die("CLI php script");
require_once(__DIR__."/functions.php");

function my($command) {
  syslog(LOG_INFO, $command);
  syslog(LOG_INFO, shell_exec($command));
}

$entries = read_entries();
$online = false;
foreach ($entries as $entry) {
  if ($entry->bool_status() && $entry->online()) {
   $online = true;
   break;
  }
}
if ($online) {
  file_put_contents('/opt/throttling/enabled', '1');
} else {
  file_put_contents('/opt/throttling/enabled', '0');
}


switch ($config["mode"]) {
  case 'auto':
  default:
    if ($online) {
      my("transmission-remote -as");
    } else {
      my("transmission-remote -AS -D -U");
    }
    break;
  
  case 'off':
    my("transmission-remote -AS -d 1 -u 1");
    break;

  case 'specified':
    my("transmission-remote -AS -d ".$config['down']." -u ".$config['up']);
    break;

  case 'full':
    my("transmission-remote -AS -D -U");
    break;
}
