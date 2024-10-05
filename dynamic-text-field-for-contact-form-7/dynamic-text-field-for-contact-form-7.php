<?php
/**
* Plugin Name: Dynamic text field for contact form 7
* Description: This plugin allows Dynamic text and field for contact form 7
* Version: 1.0
* Copyright: 2023
* Text Domain: dynamic-text-field-for-contact-form-7
* Domain Path: /languages 
*/


// define for base name
define('DYFCF7_BASE_NAME', plugin_basename( __FILE__ ));


// define for plugin file
define('DYFCF7_plugin_file', __FILE__);


// define for plugin dir path
define('DYFCF7_PLUGIN_DIR',plugins_url('', __FILE__));


 /* all Included file  */
include_once('main/backend/DYFCF7_frontend.php');

include_once('main/resources/DYFCF7-installation-require.php');

include_once('main/resources/DYFCF7-language.php');

function DYFCF7_support_and_rating_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
    if ($plugin_file_name !== plugin_basename(__FILE__)) {
      return $links_array;
    }

    $links_array[] = '<a href="https://www.plugin999.com/support/">'. __('Support', 'dynamic-text-field-for-contact-form-7') .'</a>';
    $links_array[] = '<a href="https://wordpress.org/support/plugin/dynamic-text-field-for-contact-form-7/reviews/?filter=5">'. __('Rate the plugin ★★★★★', 'dynamic-text-field-for-contact-form-7') .'</a>';

    return $links_array;

}
add_filter( 'plugin_row_meta', 'DYFCF7_support_and_rating_links', 10, 4 );