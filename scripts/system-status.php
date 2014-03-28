#!/usr/bin/env drush
<?php

/**
 * @file
 * Returns the value of system.status for a given module.
 */

$name = drush_shift();
$status = db_query('SELECT status FROM {system} WHERE name = :name', array(':name' => $name))->fetchField();

echo $status;
