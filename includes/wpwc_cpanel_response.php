<?php
require_once( '../../../../wp-load.php' );
$wpexists = '';
$selectdomain = '';
// load WP environment
##$test = $params['wpwc_cpanel_login_url'];

#wp_mail( 'sandner@cronema.com', 'ninja','1<br>'.$_POST['wpwc_cpanel_login_url'].'<br>'.$_POST['wpwc_cpanel_login_username'].'<br>'.$_POST['wpwc_cpanel_login_password']);

$this_cPanel_url = prepare_wpwc_server_login_url($_POST['wpwc_cpanel_login_url']);

	$cserverdomains = wpwc_call_cpanel($this_cPanel_url,$_POST['wpwc_cpanel_login_username'],$_POST['wpwc_cpanel_login_password'],'','1','0');
	$serverdomains_explo = explode('#',$cserverdomains);
	foreach($serverdomains_explo as $serverdomain)
	{
		if($serverdomain!='')
		{
		$serverdomainoptions .= '<option value="'.$serverdomain.'">'.$serverdomain.'</option>';
		}
	}
		if($serverdomainoptions)
		{
		if($_POST['formplugin']=='ninja'){$selectcss = 'ninja-forms-field nf-element';}
		if($_POST['formplugin']=='caldera'){$selectcss = 'form-control';}

		$selectdomain = '<select class="'.$selectcss.'" name="wpwc_cpanel_login_domain">'.$serverdomainoptions.'</select>';
		}
		#echo $test;


$return = array(
	'select'   => $selectdomain,
	'url'   => $this_cPanel_url,
	'username'   => $_POST['wpwc_cpanel_login_username'],
	'password'   => $_POST['wpwc_cpanel_login_password'],
	'buttontext'   => 'Ok use this domain',
	'ID'        => 1
);

wp_send_json_success( $return );


?>
