#!/usr/bin/env php
<?php

/**
 * @file
 * Runs a drush script on all sites on a docroot pair.
 */

include __DIR__ . '/lib/colors.php';

$script = basename($argv[0]);
if (empty($argv[3])) {
  echo "Usage: $script drush-script sitegroup environment\n";
  exit(1);
}

$script = $argv[1];
$sitegroup = $argv[2];
$environment = empty($argv[3]) ? '01_live' : $argv[3];
$interval = empty($argv[4]) ? 1 : $argv[4];

$data = date('YmdHi');
$log_dir = '/tmp';
$log_file = "${log_dir}/${script}-${date}.csv";
$drush_script = __DIR__ . "/scripts/${script}.php";
$json_file = "/mnt/gfs/${sitegroup}.${environment}/files-private/sites.json";

if (!is_writable($log_dir)) {
  echo "${red}${log_dir} not writable${reset}\n";
  exit(1);
}

if (!file_exists($json_file)) {
  echo "${red}${json_file} not found${reset}\n";
  exit(1);
}

$json = file_get_contents($json_file);
$site_data = json_decode($json, true);
$sites = array_keys($site_data['sites']);

foreach ($sites as $uri) {
  $result = shell_exec("/usr/bin/env drush @${sitegroup}.${environment} php-script $drush_script --uri=$uri");
  $output = "${sitegroup},${environment},${uri},${result}\n";
  file_put_contents($log_file, $output);
  echo $output;
}

