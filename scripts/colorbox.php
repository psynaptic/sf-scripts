<?php

/**
 * @file
 * Reports various colorbox-related configuration.
 */

$query = 'SELECT status FROM {system} WHERE name = :name';
$colorbox = db_query($query, array(':name' => 'colorbox'))->fetchField();
$media_gallery = db_query($query, array(':name' => 'media_gallery'))->fetchField();
$output['colorbox_state'] = $colorbox ? 'enabled' : 'disabled';
$output['media_gallery_state'] = $media_gallery ? 'enabled' : 'disabled';
$output['colorbox_style'] = variable_get('colorbox_style');
$output['media_gallery_style'] = variable_get('media_gallery_colorbox_stylesheet');
echo implode(',', $output);

