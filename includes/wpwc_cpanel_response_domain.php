<?php
require_once( '../../../../wp-load.php' );
$wpexists = '';
$selectdomain = '';
// load WP environment
##$test = $params['wpwc_cpanel_login_url'];

$domaintocheck = strtolower($_POST['wpwc_cpanel_login_domain']);

$wpexists = wpwc_call_docroot($_POST['wpwc_cpanel_login_url'],$_POST['wpwc_cpanel_login_username'],$_POST['wpwc_cpanel_login_password'],$domaintocheck);


$return = array(
	'wpexists' => $wpexists,
	'cpdomain' => $_POST['wpwc_cpanel_login_domain'],
	'url' => $_POST['wpwc_cpanel_login_url'],
	'ID'        => 1
);

wp_send_json_success( $return );


?>
