<?php
//Save the mappieng relationship to the new created mapping posttype
add_action('save_post', 'save_wpwc_mappings');

function prepare_wpwc_server_login_url($this_cPanel_url)
{
	$slashcheck = substr($this_cPanel_url, -1);
	if($slashcheck!='/')
	{
		$this_cPanel_url = $this_cPanel_url.'/';
	}
	return $this_cPanel_url;
}


function wpwc_save_if_content($pid,$key='',$var='')
{
	if($var!='')
	{
	update_post_meta($pid,$key,$var);
	}
	if($var=='')
	{
	update_post_meta($pid,$key,'0');
	delete_post_meta($pid,$key,'0');
	}
}
function save_wpwc_mappings()
{

	global $post;
	global $wpdb;

	$tposttype = $post->post_type;

	if($tposttype == 'wpwc_email')
	{
		wpwc_save_if_content($post->ID, "wpwc_email_subject", $_POST['wpwc_email_subject']);
		wpwc_save_if_content($post->ID, "wpwc_sender_email", $_POST['wpwc_sender_email']);
		wpwc_save_if_content($post->ID, "wpwc_sender_name", $_POST['wpwc_sender_name']);
		wpwc_save_if_content($post->ID, "wpwc_admin_info", $_POST['wpwc_admin_info']);
	}

	if($tposttype == 'wpwc_websites')
	{
		wpwc_save_if_content($post->ID, "wpwc_website_domain", $_POST['wpwc_website_domain']);
		wpwc_save_if_content($post->ID, "wpwc_website_login_domain", $_POST['wpwc_website_login_domain']);
		wpwc_save_if_content($post->ID, "wpwc_website_username", $_POST['wpwc_website_username']);
		wpwc_save_if_content($post->ID, "wpwc_website_password", $_POST['wpwc_website_password']);
		wpwc_save_if_content($post->ID, "wpwc_website_user_role", $_POST['wpwc_website_user_role']);
		wpwc_save_if_content($post->ID, "wpwc_website_salutation", $_POST['wpwc_website_salutation']);
		wpwc_save_if_content($post->ID, "wpwc_website_first_name", $_POST['wpwc_website_first_name']);
		wpwc_save_if_content($post->ID, "wpwc_website_last_name", $_POST['wpwc_website_last_name']);
		wpwc_save_if_content($post->ID, "wpwc_website_user_email", $_POST['wpwc_website_user_email']);
		wpwc_save_if_content($post->ID, "wpwc_website_admin_username", $_POST['wpwc_website_admin_username']);
		wpwc_save_if_content($post->ID, "wpwc_website_admin_password", $_POST['wpwc_website_admin_password']);
		wpwc_save_if_content($post->ID, "wpwc_website_account_login_domain", $_POST['wpwc_website_account_login_domain']);
		wpwc_save_if_content($post->ID, "wpwc_website_account_username", $_POST['wpwc_website_account_username']);
		wpwc_save_if_content($post->ID, "wpwc_website_account_password", $_POST['wpwc_website_account_password']);
		wpwc_save_if_content($post->ID, "wpwc_account_ftp_host", $_POST['wpwc_account_ftp_host']);
		wpwc_save_if_content($post->ID, "wpwc_account_ftp_username", $_POST['wpwc_account_ftp_username']);
		wpwc_save_if_content($post->ID, "wpwc_email_template_id", $_POST['wpwc_email_template_id']);
		wpwc_save_if_content($post->ID, "wpwc_account_ftp_password", $_POST['wpwc_account_ftp_password']);
		wpwc_save_if_content($post->ID, "wpwc_support_videotutorials", $_POST['wpwc_support_videotutorials']);
		wpwc_save_if_content($post->ID, "wpwc_account_ftp_password", $_POST['wpwc_account_ftp_password']);
		wpwc_save_if_content($post->ID, "wpwc_website_custom_1", $_POST['wpwc_website_custom_1']);
		wpwc_save_if_content($post->ID, "wpwc_website_custom_2", $_POST['wpwc_website_custom_2']);
		wpwc_save_if_content($post->ID, "wpwc_website_custom_3", $_POST['wpwc_website_custom_3']);
		wpwc_save_if_content($post->ID, "wpwc_website_custom_4", $_POST['wpwc_website_custom_4']);
		wpwc_save_if_content($post->ID, "wpwc_website_custom_5", $_POST['wpwc_website_custom_5']);

	}

	if($tposttype == 'wpwc_mappings')
	{
		$aktiv_map_server = get_post_meta($post->ID,'wpwc_s_map_servers',true);

		if($aktiv_map_server != $_POST['wpwc_s_map_servers'])
		{
			$del_wpwc_s_server_login_url = get_post_meta($post->ID,'wpwc_s_server_login_url',true);
			delete_post_meta($post->ID, "wpwc_s_server_login_url",$del_wpwc_s_server_login_url);

			$del_wpwc_s_server_login_username = get_post_meta($post->ID,'wpwc_s_server_login_username',true);
			delete_post_meta($post->ID, "wpwc_s_server_login_username",$del_wpwc_s_server_login_username);

			$del_wpwc_s_server_login_password = get_post_meta($post->ID,'wpwc_s_server_login_password',true);
			delete_post_meta($post->ID, "wpwc_s_server_login_password",$del_wpwc_s_server_login_password);

			$del_wpwc_s_whm_login_type = get_post_meta($post->ID,'wpwc_s_whm_login_type',true);
			delete_post_meta($post->ID, "wpwc_s_whm_login_type");

			$del_wpwc_s_map_createuser = get_post_meta($post->ID,'wpwc_s_map_createuser',true);
			delete_post_meta($post->ID, "wpwc_s_map_createuser");

			$del_wpwc_s_create_under_plesk_customer = get_post_meta($post->ID,'wpwc_s_create_under_plesk_customer',true);
			delete_post_meta($post->ID, "wpwc_s_create_under_plesk_customer");

			$del_wpwc_s_map_maindomain = get_post_meta($post->ID,'wpwc_s_map_maindomain',true);
			delete_post_meta($post->ID, "wpwc_s_map_maindomain");

			$del_wpwc_s_map_package = get_post_meta($post->ID,'wpwc_s_map_package',true);
			delete_post_meta($post->ID, "wpwc_s_map_package");

			$del_wpwc_s_create_plesk_customer = get_post_meta($post->ID,'wpwc_s_create_plesk_customer',true);
			delete_post_meta($post->ID, "wpwc_s_create_plesk_customer");

			$del_wpwc_map_pakete = get_post_meta($post->ID,'wpwc_map_pakete',true);
			delete_post_meta($post->ID, "wpwc_map_pakete");

			$del_wpwc_map_domains = get_post_meta($post->ID,'wpwc_map_domains',true);
			delete_post_meta($post->ID, "wpwc_map_domains");

			wpwc_save_if_content($post->ID, "wpwc_s_map_servers", $_POST['wpwc_s_map_servers']);

		}

		else{


		wpwc_save_if_content($post->ID, "wpwc_website_protocoll", $_POST['wpwc_website_protocoll']);

		wpwc_save_if_content($post->ID, "wpwc_s_map_createuser", $_POST['wpwc_s_map_createuser']);
		wpwc_save_if_content($post->ID, "wpwc_s_map_userrole_intern", $_POST['wpwc_s_map_userrole_intern']);

		wpwc_save_if_content($post->ID, "wpwc_s_create_under_plesk_customer", $_POST['wpwc_s_create_under_plesk_customer']);

		wpwc_save_if_content($post->ID, "wpwc_use_designid", $_POST['wpwc_use_designid']);

		wpwc_save_if_content($post->ID, "wpwc_use_designarea", $_POST['wpwc_use_designarea']);

		wpwc_save_if_content($post->ID, "wpwc_s_create_plesk_customer", $_POST['wpwc_s_create_plesk_customer']);

		wpwc_save_if_content($post->ID, "wpwc_secretcode", $_POST['wpwc_secretcode']);

		wpwc_save_if_content($post->ID, "wpwc_s_map_createwebsite", $_POST['wpwc_s_map_createwebsite']);

		wpwc_save_if_content($post->ID, "wpwc_s_map_userrole", $_POST['wpwc_s_map_userrole']);

		wpwc_save_if_content($post->ID, "wpwc_s_map_domaintype", $_POST['wpwc_s_map_domaintype']);


		if($_POST['wpwc_s_map_domainextensions']!='')
		{
		$wpwc_s_map_domainextensions_explo = explode(',',$_POST['wpwc_s_map_domainextensions']);
		foreach ($wpwc_s_map_domainextensions_explo as $key => $val)
			{
				  $domainbeginn = substr($val, 0,1);
					if($domainbeginn != '.' && $val != ''){$val = '.'.$val;}

					$domain_for_meta_key = substr($val, 1);

					if($val)
					{
					$domainextensions_korr .= $val.',';
					if($_POST['d_price_'.$domain_for_meta_key] > '1')
					{
						wpwc_save_if_content($post->ID,'d_price_'.$domain_for_meta_key,$_POST['d_price_'.$domain_for_meta_key]);
					}
					}
			}
		$domainextensions_korr = substr($domainextensions_korr,0,-1);
		wpwc_save_if_content($post->ID, "wpwc_s_map_domainextensions", $domainextensions_korr);
		}


		if($_POST['wpwc_s_map_servers']!='wpwcservers' && $aktiv_map_server != $_POST['wpwc_s_map_servers']){
		$wpwc_hosting_maindomain = call_wpwcservers_get_maindomain();
		wpwc_save_if_content($post->ID, "wpwc_s_map_maindomain", $wpwc_hosting_maindomain);
		}

		if($_POST['wpwc_s_map_maindomain']!=''){
			wpwc_save_if_content($post->ID, "wpwc_s_map_maindomain", $_POST['wpwc_s_map_maindomain']);
		}

		if($_POST['wpwc_s_map_package']!=''){
			wpwc_save_if_content($post->ID, "wpwc_s_map_package", $_POST['wpwc_s_map_package']);
		}


	//Start server settings
	if($_POST['wpwc_s_server_login_url'] && $_POST['wpwc_s_server_login_username'] && $_POST['wpwc_s_server_login_password'] && $_POST['wpwc_s_map_servers'])
	{

		$this_cPanel_username_set = get_post_meta($post->ID,'wpwc_s_server_login_username',true);
		$this_cPanel_password_set = get_post_meta($post->ID,'wpwc_s_server_login_password',true);
		$this_cPanel_url_set = get_post_meta($post->ID,'wpwc_s_server_login_url',true);
		$wpwc_s_whm_login_type_set = get_post_meta($post->ID,'wpwc_s_whm_login_type',true);

		wpwc_save_if_content($post->ID, "wpwc_s_server_login_username", $_POST['wpwc_s_server_login_username']);
		wpwc_save_if_content($post->ID, "wpwc_s_server_login_password", $_POST['wpwc_s_server_login_password']);
		$this_cPanel_url = $_POST['wpwc_s_server_login_url'];

		$this_cPanel_url = prepare_wpwc_server_login_url($this_cPanel_url);
		wpwc_save_if_content($post->ID, "wpwc_s_server_login_url", $this_cPanel_url);
		wpwc_save_if_content($post->ID, "wpwc_s_whm_login_type", $_POST['wpwc_s_whm_login_type']);


	  $this_cPanel_username = $_POST['wpwc_s_server_login_username'];
	  $this_cPanel_password = $_POST['wpwc_s_server_login_password'];
	  $wpwc_map_servers = $_POST['wpwc_s_map_servers'];
	  $wpwc_s_whm_login_type = $_POST['wpwc_s_whm_login_type'];


		//call plesk only if settings changes
		if($this_cPanel_username_set != $_POST['wpwc_s_server_login_username'] or $this_cPanel_password_set != $_POST['wpwc_s_server_login_password'] or $this_cPanel_url_set != $_POST['wpwc_s_server_login_url'] or $wpwc_s_whm_login_type_set != $_POST['wpwc_s_whm_login_type'])
		{

		if($_POST['wpwc_s_map_servers']=='plesk')
		{
			wpwc_call_plesk($this_cPanel_url,$this_cPanel_username,$this_cPanel_password,$post->ID,'1','0','1');
		}//End plesk

		if($_POST['wpwc_s_map_servers']=='whm' or $_POST['wpwc_s_map_servers']=='cpanel' && ($aktiv_map_server == $_POST['wpwc_s_map_servers']))
		{
		//We check if we can connect
		if($_POST['wpwc_s_map_servers'] == 'cpanel')
		{
			wpwc_call_cpanel($this_cPanel_url,$this_cPanel_username,$this_cPanel_password,$post->ID,'0','0');
		}//Ende wenn cPanel

		if($_POST['wpwc_s_map_servers'] == 'whm')
		{
			wpwc_call_whm($this_cPanel_url,$this_cPanel_username,$this_cPanel_password,$post->ID,$wpwc_s_whm_login_type,'0');
		}//End whm

		}
	  }//End if post filds for whm
	}//End in WHM or cpanel
		//Ende Server


  wpwc_save_if_content($post->ID, "wpwc_map_source", $_POST["wpwc_map_source"]);

  $custom = get_post_custom($post->ID);
	$wpwc_map_source = $_POST["wpwc_map_source"];

	$formplugin_ex = explode('_',$wpwc_map_source);
	$formplugin = $formplugin_ex[1];
	$formularid = $formplugin_ex[2];

  $servervield_url = 'wpwc_map_'.$formplugin.'_customer_server_url';
  $servervield_username = 'wpwc_map_'.$formplugin.'_customer_server_username';
  $servervield_password = 'wpwc_map_'.$formplugin.'_customer_server_password';

  $plugindesignfield = 'wpwc_map_'.$formplugin.'_'.$formularid.'_designfield';

	if($formplugin=='ninja')
	{
	$all_fields = "SELECT * FROM ".$wpdb->prefix."nf3_fields WHERE ".$wpdb->prefix."nf3_fields.parent_id = '$formularid'";
	$results = $wpdb->get_results( "$all_fields", OBJECT );

	foreach ( $results as $wpwc_options )
		{
			$wpwc_options_id = $wpwc_options->id;
			wpwc_save_if_content($post->ID, 'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id, $_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id]);

			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'email')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_email',$wpwc_options_id);
			}
			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'design')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_design',$wpwc_options_id);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_design_field','nf-field-'.$wpwc_options_id);
			}
			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'prefix')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_prefix',$wpwc_options_id);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field','nf-field-'.$wpwc_options_id);
			}


		}
	}

	if($formplugin=='wpforms')
	{
		$content_post = get_post($formularid);
		$content = $content_post->post_content;
		$data = json_decode($content, true);
		#$content = unserialize($content);
		foreach ( $data['fields'] as $key )
			{
				$wpwc_options_id = $key['id'];
				wpwc_save_if_content($post->ID, 'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id, $_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id]);

				if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'email')
				{
					wpwc_save_if_content($post->ID,'wpwc_required_email',$wpwc_options_id);
				}
				if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'design')
				{
					wpwc_save_if_content($post->ID,'wpwc_required_design',$wpwc_options_id);
					wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_design_field','wpforms-'.$formularid.'-field_'.$wpwc_options_id);
				}
				if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'prefix')
				{
					wpwc_save_if_content($post->ID,'wpwc_required_prefix',$wpwc_options_id);
					wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field','wpforms-'.$formularid.'-field_'.$wpwc_options_id);
				}
			}
	}

	if($formplugin=='caldera')
	{
		$all_fields = "SELECT * FROM ".$wpdb->prefix."cf_forms where form_id = '$formularid'";
		$results = $wpdb->get_results( "$all_fields", OBJECT );
		foreach ( $results as $wpwc_form )
		{
			$wpwc_form_config = unserialize($wpwc_form->config);
		}

		foreach ( $wpwc_form_config['fields'] as $key )
		{
			$wpwc_options_id = $key['ID'];
			wpwc_save_if_content($post->ID, 'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id, $_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id]);

			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'email')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_email',$wpwc_options_id);
			}
			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'design')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_design',$wpwc_options_id);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_design_field',$wpwc_options_id);
			}
			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'prefix')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_prefix',$wpwc_options_id);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field',$wpwc_options_id);
			}

		}
	}

	if($formplugin=='formidable')
	{
	$all_fields = "SELECT * FROM ".$wpdb->prefix."frm_fields WHERE ".$wpdb->prefix."frm_fields.form_id = '$formularid'";
	$results = $wpdb->get_results( "$all_fields", OBJECT );

	foreach ( $results as $wpwc_options )
		{
			$wpwc_options_id = $wpwc_options->id;
			$wpwc_field_key = $wpwc_options->field_key;
			wpwc_save_if_content($post->ID, 'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id, $_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id]);

			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'email')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_email',$wpwc_options_id);
			}
			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'design')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_design',$wpwc_options_id);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_design_field','field_'.$wpwc_field_key);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_design_field_id',$wpwc_options_id);
			}
			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'prefix')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_prefix',$wpwc_options_id);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field','field_'.$wpwc_field_key);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field_id',$wpwc_options_id);
			}
		}

	}


	if($formplugin=='gravity')
	{
	$all_fields = GFAPI::get_form($formularid);
	$results = $all_fields['fields'];

	#update_post_meta($post->ID,'wpwc_gravity_check',$results);

	foreach ( $results as $wpwc_options )
		{
			$wpwc_options_id = $wpwc_options->id;
			wpwc_save_if_content($post->ID, 'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id, $_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id]);

			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'email')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_email',$wpwc_options_id);
			}
			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'design')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_design',$wpwc_options_id);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_design_field_id',$wpwc_options_id);
			}
			if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id] == 'prefix')
			{
				wpwc_save_if_content($post->ID,'wpwc_required_prefix',$wpwc_options_id);
				wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field_id',$wpwc_options_id);

			}
			#$allfields .= 'wpwc_'.$formplugin.' - '.$formularid.'_prefix_field_id - '.$wpwc_options_id.'<br>';
		}


	}


	if($formplugin=='cf7')
	{
	$results = get_post_meta($formularid,'_form',true);
	$re = '/(?<=\[)([^\]]+)/';
	preg_match_all($re, $results, $matches, PREG_SET_ORDER, 0);
	#$matches unserialize($matches);
	foreach ( $matches as $key )
	{
		$seperator = explode(' ',$key[0]);
		$name=$seperator[1];
		$type=$seperator[0];
		$type = str_replace('*','',$type);
		if($type!='submit')
		{
			wpwc_save_if_content($post->ID, 'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$name, $_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$name]);
		}

		if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$name] == 'email')
		{
			wpwc_save_if_content($post->ID,'wpwc_required_email',$name);
		}
		if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$name] == 'design')
		{
			wpwc_save_if_content($post->ID,'wpwc_required_design',$name);
			wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_design_field',$name);
		}
		if($_POST['wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$name] == 'prefix')
		{
			wpwc_save_if_content($post->ID,'wpwc_required_prefix',$name);
			wpwc_save_if_content($post->ID,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field',$name);
		}
	}
	}


	wpwc_save_if_content($post->ID, "wpwc_map_woo_state", $_POST["wpwc_map_woo_state"]);
	wpwc_save_if_content($post->ID, "wpwc_woo_product", $_POST["wpwc_woo_product"]);


  if(!$_POST["wpwc_map_design_per_row"] == '')
  {
  wpwc_save_if_content($post->ID, "wpwc_map_design_per_row", $_POST["wpwc_map_design_per_row"]);
	}else wpwc_save_if_content($post->ID, "wpwc_map_design_per_row", '3');


	if($_POST["wpwc_customer_server_url"])
	{
	wpwc_save_if_content($post->ID, "wpwc_customer_server_url", $_POST["wpwc_customer_server_url"]);
	}
	if($_POST["wpwc_customer_server_username"])
	{
	wpwc_save_if_content($post->ID, "wpwc_customer_server_username", $_POST["wpwc_customer_server_username"]);
	}
	if($_POST["wpwc_customer_server_password"])
	{
	wpwc_save_if_content($post->ID, "wpwc_customer_server_password", $_POST["wpwc_customer_server_password"]);
	}
	if($_POST["wpwc_customer_server_domain"])
	{
	wpwc_save_if_content($post->ID, "wpwc_customer_server_domain", $_POST["wpwc_customer_server_domain"]);
	}

	if($aktiv_map_server != $_POST['wpwc_s_map_servers'])
	{
		delete_post_meta($post->ID, "wpwc_customer_server_url");
		delete_post_meta($post->ID, "wpwc_customer_server_username");
		delete_post_meta($post->ID, "wpwc_customer_server_password");
		delete_post_meta($post->ID, "wpwc_customer_server_domain");
	}

  wpwc_save_if_content($post->ID, "wpwc_map_demotext", $_POST["wpwc_map_demotext"]);
	wpwc_save_if_content($post->ID, "wpwc_map_choosetext", $_POST["wpwc_map_choosetext"]);
	wpwc_save_if_content($post->ID, "wpwc_map_scrollid", $_POST["wpwc_map_scrollid"]);
  wpwc_save_if_content($post->ID, "wpwcdesignshadow", $_POST["wpwcdesignshadow"]);
	wpwc_save_if_content($post->ID, "wpwcdesignscroll", $_POST["wpwcdesignscroll"]);
	wpwc_save_if_content($post->ID, "wpwcbuttonclass", $_POST["wpwcbuttonclass"]);
	wpwc_save_if_content($post->ID, "wpwcchoosebuttonclass", $_POST["wpwcchoosebuttonclass"]);
	wpwc_save_if_content($post->ID, "wpwc_map_selectiontext", $_POST["wpwc_map_selectiontext"]);
  wpwc_save_if_content($post->ID, "wpwc_map_imagesize", $_POST["wpwc_map_imagesize"]);
	wpwc_save_if_content($post->ID, "wpwc_map_categories", $_POST["wpwc_map_categories"]);
	wpwc_save_if_content($post->ID, "wpwc_s_map_emailtemplate", $_POST["wpwc_s_map_emailtemplate"]);
	wpwc_save_if_content($post->ID, "wpwc_s_map_language", $_POST["wpwc_s_map_language"]);



  wpwc_save_if_content($post->ID, $plugindesignfield, $_POST[$plugindesignfield]);


	if($_POST['wpwc_map_design_names'])
	{
	//Designnamen speichern
	foreach($_POST['wpwc_map_design_names'] as $designid)
		{
			if($designid!='')
			{

				$designname = $_POST['wpwc_map_design_name_'.$designid];
				wpwc_save_if_content($post->ID, "wpwc_map_design_name_".$designid, $designname);

			}
			else{delete_post_meta($post->ID, "wpwc_map_design_name_".$designid);}
		}
	}

	if($_POST['wpwc_map_design_categories'])
	{
	//Designnamen speichern
	foreach($_POST['wpwc_map_design_categories'] as $designid)
		{
			if($designid!='')
			{

				$designcat = $_POST['wpwc_map_design_categories_'.$designid];
				wpwc_save_if_content($post->ID, "wpwc_map_design_categories_".$designid, $designcat);

			}
			else{delete_post_meta($post->ID, "wpwc_map_design_categories_".$designid);}
		}
	}

	$showcat = get_post_meta($post->ID,'wpwc_map_categories',true);

	if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==1){$designcolcss = 'wpwc_designcol_1';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==2){$designcolcss = 'wpwc_designcol_2';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==3){$designcolcss = 'wpwc_designcol_3';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==4){$designcolcss = 'wpwc_designcol_4';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==5){$designcolcss = 'wpwc_designcol_5';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==6){$designcolcss = 'wpwc_designcol_6';}
	else{$designcolcss = 'wpwc_designcol_3';}

	if($showcat=='yes')
	{
	if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==1){$designcolcss = 'uk-child-width-1-1@m';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==2){$designcolcss = 'uk-child-width-1-2@m';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==3){$designcolcss = 'uk-child-width-1-3@m';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==4){$designcolcss = 'uk-child-width-1-4@m';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==5){$designcolcss = 'uk-child-width-1-5@m';}
	else if(get_post_meta($post->ID, "wpwc_map_design_per_row",true)==6){$designcolcss = 'uk-child-width-1-6@m';}
	else{$designcolcss = 'uk-child-width-1-3@m';}
	}


	$imagesize = get_post_meta($post->ID, "wpwc_map_imagesize",true);
	$demotext = get_post_meta($post->ID, "wpwc_map_demotext",true);
	if($demotext==''){$demotext='DEMO';}
	$choosetext = get_post_meta($post->ID, "wpwc_map_choosetext",true);
	if($choosetext==''){$choosetext='Choose';}

	$designfield = get_post_meta($post->ID,'wpwc_required_design',true);
	if($formplugin=='caldera')
	{
		$designfield = $designfield.'_';
	}


	$plugindesignfield = 'wpwc_map_'.$formplugin.'_'.$formularid.'_designfield';
	$selecteddesignfield = get_post_meta($post->ID,$plugindesignfield,true);


	$wpwcdesignshadow_css = get_post_meta($post->ID, "wpwcdesignshadow",true);
	if($wpwcdesignshadow_css!='0')
	{
		$wpwcdesignshadow_css = ' wpwcdesignshadow ';
	}else $wpwcdesignshadow_css =' ';

	$wpwcbox_css = get_post_meta($post->ID, "wpwcboxcss",true);
	if($wpwcbox_css>'0')
	{
		$wpwcbox_css = ' '.$wpwcbox_css.' ';
	}else {$wpwcbox_css=' ';}

	$wpwcbuttonclass_css = get_post_meta($post->ID, "wpwcbuttonclass",true);
	if($wpwcbuttonclass_css>'0')
	{
		$wpwcbuttonclass_css = ' '.$wpwcbuttonclass_css.' ';
	}else {$wpwcbuttonclass_css=' wpwcdemobutton ';}

	$wpwcchoosebuttonclass_css = get_post_meta($post->ID, "wpwcchoosebuttonclass",true);
	if($wpwcchoosebuttonclass_css>'0')
	{
		$wpwcchoosebuttonclass_css = ' '.$wpwcchoosebuttonclass_css.' ';
	}else {$wpwcchoosebuttonclass_css=' wpwcdesignchoosebutton ';}


	if($selecteddesignfield=='modal')
	{
		$chooseaction = ' uk-toggle="target: #wpwcdesignchooser" ';
	}
	else if($selecteddesignfield=='slide')
	{
		$chooseaction = ' uk-toggle="target: .toggle-animation-queued; animation: uk-animation-fade; queued: true; duration: 300" ';
	}
	else {$chooseaction='';}


	if($_POST['wpwc_map_design_names'])
	{
	$array_design_2 = array();
	$categories = '';
	//Designnamen speichern
	foreach($_POST['wpwc_map_design_names'] as $designid)
		{

      $designvalues = get_post_meta($post->ID,'wpwc_map_'.$formularid.'_design_values_'.$designid,true);
      foreach($designvalues as $key3=>$val3)
      {
        $image = $val3[$imagesize];
        $id = $val3['id'];
        $demourl = $val3['design_demourl'];

				$plugindesignfield = 'wpwc_map_'.$formplugin.'_'.$formularid.'_designfield';
				$selecteddesignfield = get_post_meta($post->ID,$plugindesignfield,true);

        $designname = get_post_meta($post->ID,'wpwc_map_design_name_'.$id,true);
				$categorie = get_post_meta($post->ID,'wpwc_map_design_categories_'.$id,true);
				$scrollid = get_post_meta($post->ID,'wpwc_map_scrollid',true);
				$wpwcdesignscroll = get_post_meta($post->ID,'wpwcdesignscroll',true);
				$categorietag = str_replace(' ','-',$categorie);
				$categorietag = str_replace('.','',$categorietag);
				$categorietag = strtolower($categorietag);

				if(($selecteddesignfield == 'stacked' or $selecteddesignfield == 'reverse' or $selecteddesignfield == 'layers' or $selecteddesignfield == 'left' or $selecteddesignfield == 'right') && $wpwcdesignscroll == '1')
 	     {
 	       $choose_link = '<a href="#'.$scrollid.'" uk-scroll><button id="des_'.$id.'" '.$chooseaction.' class="'.$wpwcchoosebuttonclass_css.'">'.$choosetext.'</button></a>';
 	     }
 	     else
 	     {
 	       $choose_link = '<a href="#'.$scrollid.'" uk-scroll><button id="des_'.$id.'" '.$chooseaction.' class="'.$wpwcchoosebuttonclass_css.'">'.$choosetext.'</button></a>';
 	     }

        $selected = get_post_meta($post->ID,'wpwc_map_'.$formularid.'_designs_'.$id,true);
				if($showcat!='yes')
				{
				$design.='
		    <div class="'.$designcolcss.$wpwcdesignshadow_css.$wpwcbox_css.'">
		      <img src="'.$image.'" alt="'.$designname.'" style="width:100%">
		      <div style="text-align:center;">
						<a target="_blank" href="'.$demourl.'" class="'.$wpwcbuttonclass_css.'">'.$demotext.'</a>
					</div>
					<div style="text-align:center;">
						'.$choose_link.'
					</div>
		    </div>
				';
			}if($showcat=='yes')
				{
				$design .= '
				<li class="tag-'.$categorietag.'">
				<div class="uk-card uk-card-default uk-card-body">
					<img src="'.$image.'" alt="'.$designname.'" style="width:100%">
					<div style="text-align:center;">
						<a target="_blank" href="'.$demourl.'" class="'.$wpwcbuttonclass_css.'">'.$demotext.'</a>
					</div>
					<div style="text-align:center;">
						'.$choose_link.'
					</div>
				</div>
        </li>';
				if(!preg_match("/$categorie/", $categories))
					{
						$categories .= '<li uk-filter-control=".tag-'.$categorietag.'"><a href="#">'.$categorie.'</a></li>';
					}
				}

				if($formplugin=='cf7')
				{
					$design.='
					<script>
						jQuery(document).ready(function($){
	  					jQuery("#des_'.$id.'").click(function(){
	    					jQuery(\'input[name="'.$designfield.'"]\').val("'.$id.'").trigger( "change" );
								jQuery( "#wpwc_cpanel_form" ).show("slow" );
	  					});
							});
					</script>';
				}
				else if($formplugin=='ninja')
				{
					$design.='<script>
						jQuery(document).ready(function($){
	  					jQuery("#des_'.$id.'").click(function(){
								jQuery( "#nf-field-'.$designfield.'" ).val( '.$id.' ).trigger( "change" );
								jQuery( "#wpwc_cpanel_form" ).show("slow" );
	  					});
							});
					</script>
					';

				}
				else if($formplugin=='gravity')
				{
					$design.='<script>
						jQuery(document).ready(function($){
	  					jQuery("#des_'.$id.'").click(function(){
	    					jQuery( "#input_'.$formularid.'_'.$designfield.'").val("'.$id.'").trigger( "change" );
								jQuery( "#wpwc_cpanel_form" ).show("slow" );
	  					});
							});
					</script>
					';

				}
				else if($formplugin=='formidable')
				{
					$design.='<script>
						jQuery(document).ready(function($){
	  					jQuery("#des_'.$id.'").click(function(){
	    					jQuery(\'input[name="item_meta['.$designfield.']"]\').val("'.$id.'").trigger( "change" );
								jQuery( "#wpwc_cpanel_form" ).show("slow" );
	  					});
							});
					</script>
					';

				}
				else if($formplugin=='wpforms')
				{
					$design.='<script>
						jQuery(document).ready(function($){
	  					jQuery("#des_'.$id.'").click(function(){
	    					jQuery("#wpforms-'.$formularid.'-field_'.$designfield.'").val("'.$id.'").trigger( "change" );
								jQuery( "#wpwc_cpanel_form" ).show("slow" );
	  					});
							});
					</script>';

				}
				else
				{
					$design.='<script>
						jQuery(document).ready(function($){
							var calderafoid_1 = jQuery(".'.$formularid.'").attr("data-instance");
	  					jQuery("#des_'.$id.'").click(function(){
	    					jQuery("#'.$designfield.'" + calderafoid_1).val("'.$id.'").trigger( "change" );
								jQuery( "#wpwc_cpanel_form" ).show("slow" );

	  					});
							});
					</script>';
				}



				$arrayd_2 = array('id'=>$id,'pos'=>'0');
				array_push($array_design_2,$arrayd_2);

				wpwc_save_if_content($post->ID, "wpwc_map_".$formularid."_designs_".$desid, '1');
				wpwc_save_if_content($post->ID, "wpwc_map_".$formularid."_design_values_".$desid, $array_design);
      }


    }
		wpwc_save_if_content($post->ID, "wpwc_map_all_designs_".$formularid, $array_design_2);
	}


    $designarea = '<div class="wpwc_row">'.$design.'</div>';

		if($showcat=='yes')
		{

		$designarea = '
		<div class="wpwc_row">
			<div uk-filter="target: .js-filter">
		    <ul class="uk-subnav uk-subnav-pill">
				<li class="uk-active" uk-filter-control><a href="#">All</a></li>
		        '.$categories.'
		    </ul>
		    <ul class="js-filter '.$designcolcss.' uk-text-center" uk-grid>
		        '.$design.'
		    </ul>
			</div>
		</div>';
		}

		if(get_post_meta($post->ID,'wpwc_use_designarea',true) != 'no')
		{
    wpwc_save_if_content($post->ID, "wpwc_map_designgrid", $designarea);
		}
		if(get_post_meta($post->ID,'wpwc_use_designarea',true) == 'no')
		{
    wpwc_save_if_content($post->ID, "wpwc_map_designgrid", '');
		}
		wpwc_save_if_content($post->ID, "wpwc_admin_update_info", '0');
	}//Ende if server url dont have changed
}//End if post type is wpwc mapping

}//End






?>
