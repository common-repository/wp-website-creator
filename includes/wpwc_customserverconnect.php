<?php
require_once( '../../../../wp-load.php' );
// load WP environment

$return = array(
	'message'   => 'Saved',
	'ID'        => 1
);

wp_send_json_success( $return );
