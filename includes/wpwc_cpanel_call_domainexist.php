<?php
require_once( '../../../../wp-load.php' );
$wpexists = '';
$selectdomain = '';
// load WP environment
##$test = $params['wpwc_cpanel_login_url'];
$jquery_wpwc_s_map_servers = $_POST['jquery_wpwc_s_map_servers'];

$domaintocheckprefix = strtolower($_POST['wpwc_cpanel_prefix']);

$checkdomainvalid = wpwc_is_valid_domain($domaintocheckprefix);

if ($checkdomainvalid=='1')
{

if(!preg_match("/test/i", $domaintocheckprefix))
{
	$wpwc_mappingid = $_POST['wpwc_mappingid'];
	$wpwc_s_map_servers = get_post_meta($wpwc_mappingid,'wpwc_s_map_servers',true);
	$wpwc_s_whm_login_type = get_post_meta($wpwc_mappingid,'wpwc_s_whm_login_type',true);
	$wpwc_s_server_login_url = get_post_meta($wpwc_mappingid,'wpwc_s_server_login_url',true);
	$wpwc_s_server_login_username = get_post_meta($wpwc_mappingid,'wpwc_s_server_login_username',true);
	$wpwc_s_server_login_password = get_post_meta($wpwc_mappingid,'wpwc_s_server_login_password',true);

	$wpwc_s_server_login_url = prepare_wpwc_server_login_url($wpwc_s_server_login_url);

	$checkdomain = $domaintocheckprefix.'.'.$_POST['wpwc_cpanel_maindomain'];

		if($jquery_wpwc_s_map_servers == 'plesk')
		{

			$cdomainexists = wpwc_call_plesk($wpwc_s_server_login_url,$wpwc_s_server_login_username,$wpwc_s_server_login_password,'0','0',$checkdomain,'0');

			$return = array(
				'domainexists'   => $cdomainexists,
				'val'   => '1',
				'prefix'   => $_POST['wpwc_cpanel_prefix']
			);
		}


		if($jquery_wpwc_s_map_servers == 'cpanel' or $jquery_wpwc_s_map_servers == 'whm' or $jquery_wpwc_s_map_servers=='wpwcservers')
		{
			if($jquery_wpwc_s_map_servers=='cpanel')
			{
				$cdomainexists = wpwc_call_cpanel($wpwc_s_server_login_url,$wpwc_s_server_login_username,$wpwc_s_server_login_password,'','0',$checkdomain);
			}
			if($jquery_wpwc_s_map_servers=='whm')
			{
				$cdomainexists = wpwc_call_whm($wpwc_s_server_login_url,$wpwc_s_server_login_username,$wpwc_s_server_login_password,'0',$wpwc_s_whm_login_type,$checkdomain);
			}
			if($jquery_wpwc_s_map_servers=='wpwcservers')
			{
				$cdomainexists = call_wpwcservers_get_subdomain($checkdomain);
			}


			$return = array(
				'domainexists'   => $cdomainexists,
				'val'   => '1',
				'prefix'   => $_POST['wpwc_cpanel_prefix']
			);
		}


	}
	else{
		$return = array(
		'val'   => '2',
		);
	}


}
else{
	$return = array(
	'val'   => '3',
	);
}
wp_send_json_success( $return );


?>
