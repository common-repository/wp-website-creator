<?php
require_once( '../../../../wp-load.php' );
$wpexists = '';
$selectdomain = '';
// load WP environment
##$test = $params['wpwc_cpanel_login_url'];
$jquery_wpwc_s_map_servers = $_POST['jquery_wpwc_s_map_servers'];

$wpwc_my_domain = $_POST['wpwc_my_domain'];


//Das Prefix entspricht einem Domain prefix
$checkdomainvalid = wpwc_is_valid_domain($_POST['wpwc_cpanel_prefix'].$_POST['tld_to_check']);
wp_mail('sandner@cronema.com','lll',$_POST['wpwc_cpanel_prefix'].$_POST['tld_to_check']);
if ($checkdomainvalid=='1')
{
	$checkdomain =  $_POST['wpwc_cpanel_prefix'].$_POST['tld_to_check'];
	$tldomainexists = wpwc_call_tld($checkdomain);

			$return = array(
				'domainexists'   => $tldomainexists,
				'val'   => '1',
				'prefix'   => $checkdomain
			);

}//Das Prefix entspricht ## NICHT ## einem Domain prefix
else{
	$return = array(
	'val'   => '3',
	);
}

wp_send_json_success( $return );


?>
