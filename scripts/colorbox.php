<?php

/**
 * @file
 * Reports various colorbox-related configuration.
 */

$query = 'SELECT status FROM {system} WHERE name = :name';
$colorbox = db_query($query, array(':name' => 'colorbox'))->fetchField();
$media_gallery = db_query($query, array(':name' => 'media_gallery'))->fetchField();
$body_content = db_query('SELECT bundle, entity_id FROM field_data_body WHERE body_value LIKE :value', array(':value' => '%colorbox%'))->fetchField();

$output['colorbox_state'] = $colorbox ? 'enabled' : 'disabled';
$output['media_gallery_state'] = $media_gallery ? 'enabled' : 'disabled';
$output['colorbox_style'] = variable_get('colorbox_style');
$output['media_gallery_style'] = variable_get('media_gallery_colorbox_stylesheet');
$output['body_colorbox'] = $body_content ? 'yes' : 'no';

echo implode(',', $output);
