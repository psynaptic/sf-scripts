#!/usr/bin/env drush
<?php

/**
 * @file
 * Returns the value of system.status for a given module.
 */

// @todo Figure out how to pass arguments to the script.
#$name = drush_shift();
$name = 'gardens';

$status = db_query('SELECT status FROM {system} WHERE name = :name', array(':name' => $name))->fetchField();
echo $status;

