<?php

class Entry {
  public function __construct($status, $mac, $name) {
    $this->status = trim($status);
    $this->mac = trim($mac);
    $this->name = trim($name);
  }

  public function online() {
    return ether_online($this->mac);
  }

  public function bool_status() {
    return $this->status == "1";
  }

  public function __toString() {
    $result = $this->bool_status() ? 'on ' : "off";
    $result .= $this->online() ? ' <> Online  ' : ' <> Offline ';
    return $result." <> ".$this->mac." <> ".$this->name;
  }
}

function read_config() {
  return json_decode(file_get_contents(__DIR__.'/config.json'), true);
}

function save_config($post_data) {
  array(
    "mode" => "auto",
    "up" => "10",
    "down" => "500",
  );
  file_put_contents(__DIR__.'/config.json', json_encode($post_data));
}

function save_ethers($content) {
  file_put_contents(ethers_file_name(), $content);
}

function ethers_file_name() {
  return __DIR__."/ethers";
}

function read_ethers() {
  return file_get_contents(ethers_file_name());
}

function read_entries() {
  $content_lines = explode("\n", read_ethers());
  $result = array();
  foreach ($content_lines as $line) {
    list($status, $mac, $name) = explode("|", $line);
    $result[] = new Entry($status, $mac, $name);
  }
  return $result;
}

function ether_online($mac) {
  foreach (macs_up() as $mac_up) {
    if ($mac == $mac_up) return true;
  }
  return false;
}

$macs_up = false;
function macs_up() {
  global $macs_up;
  if (!$macs_up) {
    $macs_up = explode("\n", shell_exec(__DIR__."/macs_up.sh"));
  }
  return $macs_up;
}

function macs_up_not_in_ethers() {
  $all_macs_up = macs_up();
  $entries = read_entries();
  $results = [];
  foreach ($all_macs_up as $mac) {
    $in = false;
    foreach ($entries as $entry) {
      if ($entry->mac == $mac) {
        $in = true;
        break;
      }
    }
    if (!$in) { $results[] = $mac; }
  }
  return $results;
}

function checked($config, $key, $value) {
  return ($config[$key] == $value) ? 'checked="checked"' : '';
}

$config = read_config();