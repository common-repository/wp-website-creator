<?php

//Get themes and other settings from wp-website-creator.com
add_action('updated_option', 'wpwc_update_option_load_themes', 10, 3);

function wpwc_update_option_load_themes( $option_name, $old_value, $option_value ) {
  if($option_name=='wpcr_themes' or $option_name=='wpcr_id')
  {
    global $wpdb;
    wpwc_get_themes_for_options();
  }

}


?>
