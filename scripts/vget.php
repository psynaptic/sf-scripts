#!/usr/bin/env drush
<?php

/**
 * @file
 * Returns the value of a given configuration variable.
 */

// @todo Figure out how to pass this as an argument.
$name = 'colorbox_style';

$value = variable_get($name);
echo $value;

