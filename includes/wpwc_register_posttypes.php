<?php
//Add a new posttype for mapping all kind of form and other sources
function register_wpwc_mapping_posttype() {

	$labels = array(
		'name'               => 'WP Installer',
		'singular_name'      => 'WP Installer',
		'add_new'            => 'Add New Mapping',
		'add_new_item'       => 'Add New Mapping',
		'edit_item'          => 'Edit Mapping',
		'new_item'           => 'New Mapping',
		'all_items'          => 'All Mappings',
		'view_item'          => 'View Mappings',
		'search_items'       => 'Search Mappings',
		'not_found'          => 'No Mappings found',
		'not_found_in_trash' => 'No Mappings found in Trash',
		'parent_item_colon'  => 'WPWCreator',
		'menu_name'          => 'WPWCreator'
	);

	$args = array(
		'labels'             => $labels,
    'menu_icon'   => 'dashicons-admin-site',
		'public'             => false,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'wpwc_mappings' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title')
	);

	register_post_type( 'wpwc_mappings', $args );

}
add_action( 'init', 'register_wpwc_mapping_posttype' );


//Add a new posttype for new created websites
function register_wpwc_website_posttype() {

	$labels = array(
		'name'               => 'WPWC Websites',
		'singular_name'      => 'WPWC Website',
		'add_new'            => 'Add New Website',
		'add_new_item'       => 'Add New Website',
		'edit_item'          => 'Edit Website',
		'new_item'           => 'New Website',
		'all_items'          => 'All Websites',
		'view_item'          => 'View Websites',
		'search_items'       => 'Search Websites',
		'not_found'          => 'No Websites found',
		'not_found_in_trash' => 'No Websites found in Trash',
		'parent_item_colon'  => 'WPWCreator',
		'menu_name'          => 'WPWC Websites'
	);

	$args = array(
		'labels'             => $labels,
    'menu_icon'   => 'dashicons-desktop',
		'public'             => true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_rest' => true,
		'show_in_menu'       => 'edit.php?post_type=wpwc_mappings',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'wpwc_websites' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title','thumbnail')
	);

	register_post_type( 'wpwc_websites', $args );

}
add_action( 'init', 'register_wpwc_website_posttype' );



//Add a new posttype for new created websites
function register_wpwc_email_posttype() {

	$labels = array(
		'name'               => 'WPWC email template',
		'singular_name'      => 'WPWC email template',
		'add_new'            => 'Add New email template',
		'add_new_item'       => 'Add New email template',
		'edit_item'          => 'Edit email template',
		'new_item'           => 'New email template',
		'all_items'          => 'All email templates',
		'view_item'          => 'View email template',
		'search_items'       => 'Search email templates',
		'not_found'          => 'No email template found',
		'not_found_in_trash' => 'No email template found in Trash',
		'parent_item_colon'  => 'WPWC email template',
		'menu_name'          => 'WPWC email template'
	);

	$args = array(
		'labels'             => $labels,
    'menu_icon'   => 'dashicons-email-alt',
		'public'             => false,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => 'edit.php?post_type=wpwc_mappings',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'wpwc_email' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title','editor')
	);

	register_post_type( 'wpwc_email', $args );

}
add_action( 'init', 'register_wpwc_email_posttype' );


//Add a rest api endpoint to send new website data
add_action( 'rest_api_init', function () {
  register_rest_route( 'wp-website-creator/v2', '/wpwcwebsite', array(
    'methods'  => 'POST',
    'callback' => 'wpwc_create_website_post',

  ) );
} );

function wpwc_create_website_post($request)
{
global $wpdb;
$wpwc_website_secretcode = $request->get_param( 'wpwc_website_secretcode' );

$wpwc_website_domain = $request->get_param( 'wpwc_website_domain' );
$wpwc_website_login_domain = $request->get_param( 'wpwc_website_login_domain' );
$wpwc_website_user_email = $request->get_param( 'wpwc_website_user_email' );
$wpwc_website_username = $request->get_param( 'wpwc_website_username' );
$wpwc_website_password = $request->get_param( 'wpwc_website_password' );
$wpwc_website_user_role = $request->get_param( 'wpwc_website_user_role' );

$wpwc_email_error_message = $request->get_param( 'wpwc_email_error_message' );

$wpwc_website_admin_username = $request->get_param( 'wpwc_website_admin_username' );
$wpwc_website_admin_password = $request->get_param( 'wpwc_website_admin_password' );

$wpwc_website_account_login_domain = $request->get_param( 'wpwc_website_account_login_domain' );
$wpwc_website_account_username = $request->get_param( 'wpwc_website_account_username' );
$wpwc_website_account_password = $request->get_param( 'wpwc_website_account_password' );

$wpwc_website_salutation = $request->get_param( 'wpwc_website_salutation' );
$wpwc_website_first_name = $request->get_param( 'wpwc_website_first_name' );
$wpwc_website_last_name = $request->get_param( 'wpwc_website_last_name' );

$wpwc_account_ftp_host = $request->get_param( 'wpwc_account_ftp_host' );
$wpwc_account_ftp_username = $request->get_param( 'wpwc_account_ftp_username' );
$wpwc_account_ftp_password = $request->get_param( 'wpwc_account_ftp_password' );

$wpwc_support_videotutorials = $request->get_param( 'wpwc_support_videotutorials' );
$wpwc_support_paymentpage = $request->get_param( 'wpwc_support_paymentpage' );

$wpwc_email_template_id = $request->get_param( 'wpwc_email_template_id' );

$wpwc_website_custom_1 = $request->get_param( 'wpwc_website_custom_1' );
$wpwc_website_custom_2 = $request->get_param( 'wpwc_website_custom_2' );
$wpwc_website_custom_3 = $request->get_param( 'wpwc_website_custom_3' );
$wpwc_website_custom_4 = $request->get_param( 'wpwc_website_custom_4' );
$wpwc_website_custom_5 = $request->get_param( 'wpwc_website_custom_5' );

$designid = $request->get_param( 'wpwc_designid' );


if(!$wpwc_website_secretcode){exit;}

$wpwc_website_secretcode_all = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'wpwc_website_secretcode' AND  meta_value = '".$wpwc_website_secretcode."'");

if($wpwc_website_secretcode_all)
{
	foreach ( $wpwc_website_secretcode_all as $wpwc_website_secretcode_id )
	{
		$website_post_id = $wpwc_website_secretcode_id->post_id;
	}
}else{exit;}

if($website_post_id > '1')
{
#wp_mail('sandner@cronema.com','t3',$wpwc_website_secretcode.'<br>'.$wpwc_website_domain.'<br>'.$wpwc_website_login_domain.'<br>'.$wpwc_website_username.'<br>id: '.$website_post_id);
update_post_meta($website_post_id,'wpwc_website_domain',$wpwc_website_domain);
update_post_meta($website_post_id,'wpwc_website_login_domain',$wpwc_website_login_domain);
update_post_meta($website_post_id,'wpwc_website_username',$wpwc_website_username);
update_post_meta($website_post_id,'wpwc_website_password',$wpwc_website_password);
update_post_meta($website_post_id,'wpwc_website_user_role',$wpwc_website_user_role);
update_post_meta($website_post_id,'wpwc_website_user_email',$wpwc_website_user_email);

update_post_meta($website_post_id,'wpwc_website_admin_username',$wpwc_website_admin_username);
update_post_meta($website_post_id,'wpwc_website_admin_password',$wpwc_website_admin_password);

update_post_meta($website_post_id,'wpwc_website_account_login_domain',$wpwc_website_account_login_domain);
update_post_meta($website_post_id,'wpwc_website_account_username',$wpwc_website_account_username);
update_post_meta($website_post_id,'wpwc_website_account_password',$wpwc_website_account_password);

update_post_meta($website_post_id,'wpwc_website_salutation',$wpwc_website_salutation);
update_post_meta($website_post_id,'wpwc_website_first_name',$wpwc_website_first_name);
update_post_meta($website_post_id,'wpwc_website_last_name',$wpwc_website_last_name);

update_post_meta($website_post_id,'wpwc_account_ftp_host',$wpwc_account_ftp_host);
update_post_meta($website_post_id,'wpwc_account_ftp_username',$wpwc_account_ftp_username);
update_post_meta($website_post_id,'wpwc_account_ftp_password',$wpwc_account_ftp_password);

update_post_meta($website_post_id,'wpwc_support_videotutorials',$wpwc_support_videotutorials);
update_post_meta($website_post_id,'wpwc_support_paymentpage',$wpwc_support_paymentpage);

update_post_meta($website_post_id,'wpwc_email_template_id',$wpwc_email_template_id);

update_post_meta($website_post_id,'wpwc_email_error_message',$wpwc_email_error_message);

update_post_meta($website_post_id,'wpwc_website_custom_1',$wpwc_website_custom_1);
update_post_meta($website_post_id,'wpwc_website_custom_2',$wpwc_website_custom_2);
update_post_meta($website_post_id,'wpwc_website_custom_3',$wpwc_website_custom_3);
update_post_meta($website_post_id,'wpwc_website_custom_4',$wpwc_website_custom_4);
update_post_meta($website_post_id,'wpwc_website_custom_5',$wpwc_website_custom_5);

update_post_meta($website_post_id,'wpwc_website_designid',$designid);

if (function_exists('wpwc_edit_active_campaign'))
{
	wpwc_edit_active_campaign($website_post_id);
}


if($wpwc_email_template_id!='1')
{
if($wpwc_email_template_id=='')
{
	$wpwc_email_template_id = get_option('wpcr_emailtemplate');
}

$wpwc_email_data = get_wpwc_credentials_email($wpwc_email_template_id,$website_post_id);

$emailsubject = $wpwc_email_data['emailsubject'];
$emailsender =  $wpwc_email_data['emailsender'];
$emailsendername =  $wpwc_email_data['emailsendername'];

$email_content =  $wpwc_email_data['email_content'];
$email_content_admin =  $wpwc_email_data['email_content_admin'];

$headers .= 'Content-Type: text/html; charset=UTF-8;';
$headers .= 'From: '.$emailsendername.' <'.$emailsender.'>';

#$headers = "From: $emailsender";
#$headers .= "Return-Path: $emailsender";
#$headers .= "MIME-Version: 1.0";
#$headers .= "Content-Type: text/html; charset=UTF-8";

//Ger the main email to send credentials to admin
$options_wpcr_id = get_option('wpcr_id');
if($options_wpcr_id)
{
			foreach($options_wpcr_id as $key => $val)
			{
					if($key == 'wpcr_main_wordpress_admin_email')
					{
						$adminemail = $val;
					}
			}
}


wp_mail($adminemail,$emailsubject,$email_content.$email_content_admin,$headers);
wp_mail($wpwc_website_user_email,$emailsubject,$email_content,$headers);

$tpost = get_post( $website_post_id );
$ptitle = $tpost->post_title;
$post_title = str_replace('in creation progress','finished',$ptitle);
$my_args = array(
            'ID'           => $website_post_id,
            'post_title'   => $post_title,
						'post_status'   => 'publish',
        );
wp_update_post( $my_args );

}//Ende nur senden wenn Template gewählt

}
}


?>
