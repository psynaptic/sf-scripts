#!/usr/bin/env drush
<?php

/**
 * @file
 * Returns the value of system.status for a given module.
 */

$media_gallery = db_query('SELECT status FROM {system} WHERE name = :name', array(':name' => 'media_gallery'))->fetchField();
$colorbox = db_query('SELECT status FROM {system} WHERE name = :name', array(':name' => 'colorbox'))->fetchField();

echo "$media_gallery,$colorbox";
