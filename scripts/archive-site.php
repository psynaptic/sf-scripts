<?php

$sitegroup = '';
$env = '';
$sites = array(
);
$backup_location = '/tmp/dumps';
$data = date('YmdHi');

// Create the backup directory.
create_dir($backup_location);

foreach ($sites as $uri) {
  $db_name = vget('gardens_db_name');

  // Archive the themes and files to the backup directory.
  $files_dir = "/var/www/html/${sitegroup}.${env}/docroot/sites/g/files/${db_name}";
  foreach (array('themes', 'files') as $type) {
    create_archive("${files_dir}/${type}", "${dump_dir}/${uri}.${date}.${type}.gz");
  }

  // Dump the database to the backup directory.
  dump_database($uri, "@${sitegroup}.${env}" "${backup_location}/${uri}.${date}.sql.gz");
}

function create_dir($dirname) {
  if (!file_exists($dirname)) {
    if (!mkdir($dirname)) {
      return drush_log("Could not create directory $dirname", 'error');
    }
  }
}

function vget($name) {
  $value = variable_get($name);
  if (is_null($value)) {
    return drush_log("Could not find variable $name", 'error');
  }
}

function create_archive($source, $target) {
  execute_command("tar czf $target -C $source .");
}

function dump_database($uri, $drush_alias, $target) {
  execute_command("drush5 $drush_alias -l $uri sql-dump | gzip -9 > $target");
}

function execute_command($command) {
  drush_print("Executing: $command");
  $return = shell_exec($command);
  if (is_null($return)) {
    return drush_log('Command execution failed. Processing has been aborted.', 'error');
  }
  return $return;
}

