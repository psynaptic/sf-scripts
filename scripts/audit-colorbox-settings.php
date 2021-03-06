<?php

/**
 * @file
 * Reports various colorbox-related configuration.
 */

$query = 'SELECT status FROM {system} WHERE name = :name';
$colorbox = db_query($query, array(':name' => 'colorbox'))->fetchField();
$output['colorbox'] = $colorbox ? 'enabled' : 'disabled';

$media_gallery = db_query($query, array(':name' => 'media_gallery'))->fetchField();
$output['media_gallery'] = $media_gallery ? 'enabled' : 'disabled';

$output['colorbox_style'] = variable_get('colorbox_style');
$output['colorbox_path'] = variable_get('colorbox_path');
$output['colorbox_inline'] = variable_get('colorbox_inline');
$output['media_gallery_style'] = variable_get('media_gallery_colorbox_stylesheet');

$body_content = db_query('SELECT bundle, entity_id FROM field_data_body WHERE body_value LIKE :value', array(':value' => '%colorbox%'))->fetchField();
$output['body_colorbox'] = $body_content ? 'yes' : 'no';

$theme = themebuilder_compiler_get_current_theme();
$settings = $theme->getThemeSetting();
$output['viewport_enabled'] = $settings['viewport']['enabled'];

echo implode(',', $output);
