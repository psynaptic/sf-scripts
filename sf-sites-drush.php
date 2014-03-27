#!/usr/bin/env php
<?php

/**
 * @file
 * Runs a drush script on all sites on a docroot pair.
 */

include __DIR__ . '/lib/colors.php';

$script = basename($argv[0]);
if (empty($argv[4])) {
  echo "Usage: $script drush-script sitegroup environment\n";
  exit(1);
}

$script = $argv[1];
$sitegroup = $argv[2];
$environment = $argv[3];
$domain_suffix = $argv[4];
$interval = empty($argv[5]) ? 1 : $argv[5];

$date = date('YmdHi');
$log_dir = '/tmp';
$log_file = "${log_dir}/${script}-${date}.csv";
$drush_script = __DIR__ . "/scripts/${script}.php";
$json_file = "/mnt/gfs/${sitegroup}.${environment}/files-private/sites.json";

if (!file_exists($drush_script)) {
  echo "${red}${drush_script} not found${reset}\n";
  exit(1);
}

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
$domains = array_keys($site_data['sites']);
$sites = array_filter($domains, function($data) use ($domain_suffix) {
  return substr($data, -strlen($domain_suffix)) === $domain_suffix;
});

$root = "/var/www/html/${sitegroup}.${environment}/docroot";
foreach ($sites as $uri) {
  $result = shell_exec("/usr/bin/env drush php-script $drush_script --uri=$uri --root=$root");
  $output = "${sitegroup},${environment},${uri},${result}\n";
  file_put_contents($log_file, $output, FILE_APPEND);
  echo $output;
  sleep($interval);
}

