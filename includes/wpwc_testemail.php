<?php
require_once( '../../../../wp-load.php' );
global $wpdb;
// load WP environment
##$test = $params['wpwc_cpanel_login_url'];
$postid = $_POST['id'];
$test = $_POST['test'];


if($test!='1')
{
$wpwc_email_template_id = get_post_meta($postid,'wpwc_email_template_id',true);
$wpwc_email_data = get_wpwc_credentials_email($wpwc_email_template_id,$postid);

$emailsubject = $wpwc_email_data['emailsubject'];
$emailsender =  $wpwc_email_data['emailsender'];
$emailsendername =  $wpwc_email_data['emailsendername'];
$email_content =  $wpwc_email_data['email_content'];
}
if($test=='1')
{
	$emailsubject = get_post_meta($postid,'wpwc_email_subject',true);
	$emailsender =  get_post_meta($postid,'wpwc_sender_email',true);
	$emailsendername =  get_post_meta($postid,'wpwc_sender_name',true);

	$post_content = get_post($postid);
  $email_content = $post_content->post_content;

	$email_content = apply_filters('the_content', $email_content);
  $email_content = str_replace(']]>', ']]&gt;', $email_content);

	$email_content = str_replace('#website_domain#','https://example.com', $email_content);
	$email_content = str_replace('#website_login_domain#','https://example.com/wp-admin', $email_content);
	$email_content = str_replace('#website_username#','WP Username', $email_content);
	$email_content = str_replace('#website_password#','WP Password', $email_content);
	$email_content = str_replace('#website_user_email#','WP Email', $email_content);
	$email_content = str_replace('#website_user_role#','WP Role', $email_content);

	$email_content = str_replace('#website_admin_username#','Admin Username', $email_content);
	$email_content = str_replace('#website_admin_passord#','Admin Password', $email_content);

	$email_content = str_replace('#account_login_domain#','Server account login domain', $email_content);
	$email_content = str_replace('#account_username#','Server account login username', $email_content);
	$email_content = str_replace('#account_password#','Server account login password', $email_content);
	$email_content = str_replace('#account_ftp_host#','FTP host', $email_content);
	$email_content = str_replace('#account_ftp_username#','FTP username', $email_content);
	$email_content = str_replace('#account_ftp_password#','FTP password', $email_content);

	$email_content = str_replace('#website_salutation#','Mr.', $email_content);
	$email_content = str_replace('#website_first_name#','John', $email_content);
	$email_content = str_replace('#website_last_name#','Doe', $email_content);


	$email_content = str_replace('#support_videotutorials#','https://videotutorials.example.com', $email_content);
	$email_content = str_replace('#support_paymentpage#','https://payment.example.com', $email_content);
}


$to = $_POST['email'];
$subject = $emailsubject;
$body = $email_content;
$headers[] = 'Content-Type: text/html; charset=UTF-8';
$headers[] = 'From: '.$emailsendername.' <'.$emailsender.'>';

$wpwcemail = wp_mail($to,$subject,$body,$headers);

$return = array(
	'sent'   => $wpwcemail
);

wp_send_json_success( $return );


?>
