<?php

######### Add create website action to each form plugins last action (after form was sent)
#########
//ninja


function random_secretkey($length = 18)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars), 0, $length);
	}

	add_action('ninja_forms_after_submission', 'save_wpwc_ninja_mappings');
	function save_wpwc_ninja_mappings($form_data)
	{
			wpwc_send_website_data( 'ninja',$form_data,'0','0','0','0','0','0','0','0','0' );
	}

//wpform lite
	add_action( 'wpforms_process_entry_save',  'save_wpwc_wpforms_mappings', 10, 3 );
	function save_wpwc_wpforms_mappings($fields, $entry, $form_id )
	{
			wpwc_send_website_data( 'wpforms','0',$fields, $entry, $form_id,'0','0','0','0','0','0' );
	}

//caldera
	add_action( 'caldera_forms_submit_complete','save_wpwc_caldera_mappings', 55 );
	function save_wpwc_caldera_mappings($form)
	{
			wpwc_send_website_data( 'caldera','0','0','0','0',$form,'0','0','0','0','0' );
	}

	//formidable
	add_action( 'frm_after_create_entry','save_wpwc_formidable_mappings', 30, 2);
	function save_wpwc_formidable_mappings($entry_id, $form_id)
	{
			wpwc_send_website_data( 'formidable','0','0','0','0','0',$entry_id, $form_id,'0','0','0' );
	}

	//gravity

		add_action( 'gform_after_submission','save_wpwc_gravity_mappings', 10, 2);
		function save_wpwc_gravity_mappings($entry, $form)
		{
				wpwc_send_website_data( 'gravity','0','0','0','0','0','0','0','0',$entry,$form );
		}


//Contact form 7
	add_action('wpcf7_mail_sent','save_wpwc_cf7_mappings', 30, 2);
	function save_wpwc_cf7_mappings($contact_form)
	{
			//Position 2 is cf7 and can be 0
			wpwc_send_website_data( 'cf7','0','0','0','0','0','0', '0',$contact_form,'0','0' );
	}

	function get_formid_related_system_fields($wpwc_mapping_id,$formplugin)
	{
		$plugin_version = '3';
		$postarray = ' #start#wpwc_version##'.$plugin_version.'##wpwc_version#end# #start#formplugin##'.$formplugin.'##formplugin#end#';
		$wpwc_systemfields = get_post_meta($wpwc_mapping_id);
		if($wpwc_systemfields)
		{
			foreach($wpwc_systemfields as $key => $val)
			{
				$checksytemfield = explode('_',$key);
				if($checksytemfield[1] == 's')
				{
					$postarray .= ' #start#'.$key.'##'.$val[0].'##'.$key.'#end# ';
				}
			}
		}
		return $postarray;
	}

############## Send data to wp-website-creator to create websites from
#######For all form plugins
#####################


	add_action( 'woocommerce_order_status_on-hold', 'wpwc_order_onhold_curl', 10, 1);
	add_action( 'woocommerce_order_status_completed', 'wpwc_order_completes_curl', 10, 1);
	add_action( 'woocommerce_order_status_processing', 'wpwc_order_processing_curl', 10, 1);


function wpwc_order_completes_curl( $order_id )
{
	global $wpdb,$woocommerce;
	$order = new WC_Order( $order_id );

	$order_items = $order->get_items();
	foreach ($order_items as $items_key => $items_value)
	{
			$wpwc_payment_stat = $items_value['_wpwc_create_on_payment_stat'];
			$wpwcorderkey = $items_value['_wpwc_website_creation_id'];
	}

	 //If the item has an wpwc order key
	 if($wpwcorderkey != '' && $wpwc_payment_stat == 'wc-completed')
	 {
		 wpwc_set_website_status($wpwcorderkey);
	 }

}//End order complete

function wpwc_order_onhold_curl( $order_id )
{
         global $wpdb,$woocommerce;
         $order = new WC_Order( $order_id );

				 $order_items = $order->get_items();
			 	foreach ($order_items as $items_key => $items_value)
			 	{
			 			$wpwc_payment_stat = $items_value['_wpwc_create_on_payment_stat'];
			 			$wpwcorderkey = $items_value['_wpwc_website_creation_id'];
			 	}

			 	 //If the item has an wpwc order key
			 	 if($wpwcorderkey != '' && $wpwc_payment_stat == 'wc-on-hold')
			 	 {
			 		 wpwc_set_website_status($wpwcorderkey);
			 	 }

}//End on hold

function wpwc_order_processing_curl( $order_id )
{
         global $wpdb,$woocommerce;
         $order = new WC_Order( $order_id );

				 $order_items = $order->get_items();
			 	foreach ($order_items as $items_key => $items_value)
			 	{
			 			$wpwc_payment_stat = $items_value['_wpwc_create_on_payment_stat'];
			 			$wpwcorderkey = $items_value['_wpwc_website_creation_id'];
			 	}

			 	 //If the item has an wpwc order key
			 	 if($wpwcorderkey != '' && $wpwc_payment_stat == 'wc-processing')
			 	 {
			 		 wpwc_set_website_status($wpwcorderkey);
			 	 }

}//End on hold

function wpwc_set_website_status($wpwcorderkey)
{
	//Get the access from database
	$wpwc_credentials = get_wpwc_creadentials();
	$wpcr_username = $wpwc_credentials["wpcr_username"];
	$wpcr_password = $wpwc_credentials["wpcr_password"];
	$wpcr_id = $wpwc_credentials["wpcr_id"];


	//Create a installation post on wp-website-creator.de
	$url='https://wp-website-creator.com/wp-json/wp/v2/wpwc_creator_in/'.$wpwcorderkey;
	$args = array(
			'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( $wpcr_username . ':' . $wpcr_password )
			 ),
			'body' => array(
			'excerpt' => 'newversion_4',
			)
		);
	 $wpwc_response = wp_remote_post( $url, $args );
}//End wpwc_set_website_status


if (!function_exists('get_post_id_by_meta_key_and_value'))
{
	function get_post_id_by_meta_key_and_value($key, $value)
	{
		global $wpdb;
		$meta = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key='".$wpdb->escape($key)."' AND meta_value='".$wpdb->escape($value)."'");
		if (is_array($meta) && !empty($meta) && isset($meta[0]))
		{
			$meta = $meta[0];
		}
		if (is_object($meta))
		{
			return $meta->post_id;
		}
		else
		{
			return false;
		}
	}
}


function wpwc_install_website_now($wpwcorderkey)
{
	$secretpostid = get_post_id_by_meta_key_and_value('wpwc_website_secretcode', $wpwcorderkey);
	$newwebsiteid = get_post_meta($secretpostid,'wpwc_website_id',true);
	if($newwebsiteid>'0')
	{
	//Get the access from database
	$wpwc_credentials = get_wpwc_creadentials();
	$wpcr_username = $wpwc_credentials["wpcr_username"];
	$wpcr_password = $wpwc_credentials["wpcr_password"];
	$wpcr_id = $wpwc_credentials["wpcr_id"];


	//Create a installation post on wp-website-creator.de
	$url='https://wp-website-creator.com/wp-json/wp/v2/wpwc_creator_in/'.$newwebsiteid;
	$args = array(
			'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( $wpcr_username . ':' . $wpcr_password )
			 ),
			'body' => array(
			'excerpt' => 'newversion_4',
			)
		);
	 $wpwc_response = wp_remote_post( $url, $args );
 }
 #delete_post_meta($secretpostid,'wpwc_website_id',$newwebsiteid);
 update_post_meta($post_ID,'website_created','1');
}//End wpwc_set_website_status

function wpwc_send_website_data( $formplugin,$ninja_form_data,$wpforms_fields,$wpforms_entry,$wpforms_id,$caldera_form,$formidable_entry,$formidable_id,$cf7,$gravity_entry,$gravity_form)
{
		global $wpdb;
		#wp_mail( 'sandner@cronema.com', 'formidable',$formplugin.'<br><br>'.$formidable_id);
    ######start cf7 plugin
		if($formplugin == 'cf7')
		{

			$formid_wpwc = 'wpwc_'.$formplugin.'_'.$cf7->id();
			$thismapping = get_formid_related_mapping($formid_wpwc);
			$wpwc_mapping_id = $thismapping['wpwc_mapping_id'];
			$wpwc_map_woo_state = $thismapping['wpwc_map_woo_state'];
			$wpwc_woo_product = $thismapping['wpwc_woo_product'];

			$domaintype = $thismapping["wpwc_s_map_domaintype"];
			$wpwc_s_map_domainextensions = $thismapping["wpwc_s_map_domainextensions"];

			$subdomain_id = get_post_meta($wpwc_mapping_id,'wpwc_required_prefix',true);
			$payment_status = get_post_meta($wpwc_mapping_id,'wpwc_map_woo_state',true);
			$wpwc_website_protocoll = get_post_meta($wpwc_mapping_id,'wpwc_website_protocoll',true);

			$wpwc_s_map_createuser = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createuser',true);
			$wpwc_s_map_userrole_intern = get_post_meta($wpwc_mapping_id,'wpwc_s_map_userrole_intern',true);

			$wpwc_s_map_createwebsite = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createwebsite',true);

			if($wpwc_mapping_id!='')
			{

				$wpwc_customer_server_url = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_url',true);
				$wpwc_customer_server_username = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_username',true);
				$wpwc_customer_server_password = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_password',true);
				$wpwc_customer_server_domain = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_domain',true);
				$secretcode_field = get_post_meta($wpwc_mapping_id,'wpwc_secretcode',true);

			//If the form is related to wwbsitecreator
			$wpwc_create_a_website = '1';
			$submission = WPCF7_Submission::get_instance();
    	if ($submission)
			{
        $posted_data = $submission->get_posted_data();
				foreach ($posted_data as $key => $value)
				{
					$wpwc_mapping_key = get_post_meta($wpwc_mapping_id,'wpwc_mapfield_'.$formplugin.'_id_'.$cf7->id().'_fid_'.$key,true);

					if($key==$secretcode_field && $wpwc_s_map_createwebsite!='immediately')
					{
					$secretcode = $value;
					}

					if($wpwc_mapping_key!='')
					{
					$postarray .= ' #start#'.$wpwc_mapping_key.'##'.$value.'##'.$wpwc_mapping_key.'#end# ';
					if($wpwc_mapping_key == 'email'){$customeremail = $value;}
					#$mappingarray .= $wpwc_mapping_key;
					}
					if($key==$wpwc_customer_server_url)
					{
					$postarray .= ' #start#wpwc_customer_server_url##'.$value.'##wpwc_customer_server_url#end# ';
					}
					if($key==$wpwc_customer_server_username)
					{
					$postarray .= ' #start#wpwc_customer_server_username##'.$value.'##wpwc_customer_server_username#end# ';
					}
					if($key==$wpwc_customer_server_password)
					{
					$postarray .= ' #start#wpwc_customer_server_password##'.$value.'##wpwc_customer_server_password#end# ';
					}
					if($key==$wpwc_customer_server_domain)
					{
					$postarray .= ' #start#wpwc_customer_server_domain##'.$value.'##wpwc_customer_server_domain#end# ';
					}
					if($key==$subdomain_id)
					{
						$subdomain = $value;
					}

				}
    	}
			$postarray .= get_formid_related_system_fields($wpwc_mapping_id,$formplugin);
			}
		}
    ##End cf7 Pluging fields

		##start formidable fields
		if($formplugin == 'formidable')
		{

			$formid_wpwc = 'wpwc_'.$formplugin.'_'.$formidable_id;
			$thismapping = get_formid_related_mapping($formid_wpwc);
			$wpwc_mapping_id = $thismapping['wpwc_mapping_id'];
			$wpwc_map_woo_state = $thismapping['wpwc_map_woo_state'];
			$wpwc_woo_product = $thismapping['wpwc_woo_product'];

			$subdomain_id = get_post_meta($wpwc_mapping_id,'wpwc_required_prefix',true);
			$payment_status = get_post_meta($wpwc_mapping_id,'wpwc_map_woo_state',true);
			$wpwc_website_protocoll = get_post_meta($wpwc_mapping_id,'wpwc_website_protocoll',true);

			$wpwc_s_map_createuser = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createuser',true);
			$wpwc_s_map_userrole_intern = get_post_meta($wpwc_mapping_id,'wpwc_s_map_userrole_intern',true);

			$domaintype = $thismapping["wpwc_s_map_domaintype"];
			$wpwc_s_map_domainextensions = $thismapping["wpwc_s_map_domainextensions"];

			$wpwc_s_map_createwebsite = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createwebsite',true);

			if($wpwc_mapping_id!='')
			{
				$wpwc_customer_server_url = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_url',true);
				$wpwc_customer_server_username = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_username',true);
				$wpwc_customer_server_password = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_password',true);
				$wpwc_customer_server_domain = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_domain',true);
				$secretcode_field = get_post_meta($wpwc_mapping_id,'wpwc_secretcode',true);

				//If the form is related to wwbsitecreator
				$wpwc_create_a_website = '1';
			foreach ($_POST['item_meta'] as $key => $value)
			{
				$wpwc_mapping_key = get_post_meta($wpwc_mapping_id,'wpwc_mapfield_'.$formplugin.'_id_'.$formidable_id.'_fid_'.$key,true);

				if($key==$secretcode_field && $wpwc_s_map_createwebsite!='immediately')
				{
				$secretcode = $value;
				}

				if($wpwc_mapping_key!='')
				{
				$postarray .= '#start#'.$wpwc_mapping_key.'##'.$value.'##'.$wpwc_mapping_key.'#end# ';
				if($wpwc_mapping_key == 'email'){$customeremail = $value;}
				}

				if($key==$wpwc_customer_server_url)
				{
				$postarray .= ' #start#wpwc_customer_server_url##'.$value.'##wpwc_customer_server_url#end# ';
				}
				if($key==$wpwc_customer_server_username)
				{
				$postarray .= ' #start#wpwc_customer_server_username##'.$value.'##wpwc_customer_server_username#end# ';
				}
				if($key==$wpwc_customer_server_password)
				{
				$postarray .= ' #start#wpwc_customer_server_password##'.$value.'##wpwc_customer_server_password#end# ';
				}
				if($key==$wpwc_customer_server_domain)
				{
				$postarray .= ' #start#wpwc_customer_server_domain##'.$value.'##wpwc_customer_server_domain#end# ';
				}
				if($key==$subdomain_id)
				{
					$subdomain = $value;
				}

			}
			$postarray .= get_formid_related_system_fields($wpwc_mapping_id,$formplugin);
			}
		}
    ##End formidable fields

		##start wpforms fields
		if($formplugin == 'wpforms')
		{

			$formid_wpwc = 'wpwc_'.$formplugin.'_'.$wpforms_id;
			$thismapping = get_formid_related_mapping($formid_wpwc);
			$wpwc_mapping_id = $thismapping['wpwc_mapping_id'];
			$wpwc_map_woo_state = $thismapping['wpwc_map_woo_state'];
			$wpwc_woo_product = $thismapping['wpwc_woo_product'];

			$domaintype = $thismapping["wpwc_s_map_domaintype"];
			$wpwc_s_map_domainextensions = $thismapping["wpwc_s_map_domainextensions"];

			$subdomain_id = get_post_meta($wpwc_mapping_id,'wpwc_required_prefix',true);
			$payment_status = get_post_meta($wpwc_mapping_id,'wpwc_map_woo_state',true);
			$wpwc_website_protocoll = get_post_meta($wpwc_mapping_id,'wpwc_website_protocoll',true);

			$wpwc_s_map_createuser = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createuser',true);
			$wpwc_s_map_userrole_intern = get_post_meta($wpwc_mapping_id,'wpwc_s_map_userrole_intern',true);

			$wpwc_s_map_createwebsite = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createwebsite',true);

			if($wpwc_mapping_id!='')
			{
				$wpwc_customer_server_url = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_url',true);
				$wpwc_customer_server_username = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_username',true);
				$wpwc_customer_server_password = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_password',true);
				$wpwc_customer_server_domain = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_domain',true);
				$secretcode_field = get_post_meta($wpwc_mapping_id,'wpwc_secretcode',true);

				//If the form is related to wwbsitecreator
				$wpwc_create_a_website = '1';
			$data = $wpforms_fields;
			foreach ($data as $key => $d)
			{

				$wpwc_mapping_key = get_post_meta($wpwc_mapping_id,'wpwc_mapfield_'.$formplugin.'_id_'.$wpforms_id.'_fid_'.$key,true);

				if($key==$secretcode_field && $wpwc_s_map_createwebsite!='immediately')
				{
				$secretcode = $d['value'];
				}

				if($wpwc_mapping_key!='')
				{
					$postarray .= '#start#'.$wpwc_mapping_key.'##'.$d['value'].'##'.$wpwc_mapping_key.'#end# ';
					if($wpwc_mapping_key == 'email'){$customeremail = $d['value'];}
				}

				if($key==$wpwc_customer_server_url)
				{
				$postarray .= ' #start#wpwc_customer_server_url##'.$d['value'].'##wpwc_customer_server_url#end# ';
				}
				if($key==$wpwc_customer_server_username)
				{
				$postarray .= ' #start#wpwc_customer_server_username##'.$d['value'].'##wpwc_customer_server_username#end# ';
				}
				if($key==$wpwc_customer_server_password)
				{
				$postarray .= ' #start#wpwc_customer_server_password##'.$d['value'].'##wpwc_customer_server_password#end# ';
				}
				if($key==$wpwc_customer_server_domain)
				{
				$postarray .= ' #start#wpwc_customer_server_domain##'.$d['value'].'##wpwc_customer_server_domain#end# ';
				}
				if($key==$subdomain_id)
				{
					$subdomain = $d['value'];
				}

			}
			$postarray .= get_formid_related_system_fields($wpwc_mapping_id,$formplugin);
			}
    }
		##ende wpforms fields

		##Start Ninja fields
		if($formplugin == 'ninja')
		{

			$form_id = $ninja_form_data[ 'form_id' ];
			$formid_wpwc = 'wpwc_'.$formplugin.'_'.$form_id;
			$thismapping = get_formid_related_mapping($formid_wpwc);
			$wpwc_mapping_id = $thismapping['wpwc_mapping_id'];
			$wpwc_map_woo_state = $thismapping['wpwc_map_woo_state'];
			$wpwc_woo_product = $thismapping['wpwc_woo_product'];

			$domaintype = $thismapping["wpwc_s_map_domaintype"];
			$wpwc_s_map_domainextensions = $thismapping["wpwc_s_map_domainextensions"];

			$subdomain_id = get_post_meta($wpwc_mapping_id,'wpwc_required_prefix',true);
			$payment_status = get_post_meta($wpwc_mapping_id,'wpwc_map_woo_state',true);
			$wpwc_website_protocoll = get_post_meta($wpwc_mapping_id,'wpwc_website_protocoll',true);

			$wpwc_s_map_createuser = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createuser',true);
			$wpwc_s_map_userrole_intern = get_post_meta($wpwc_mapping_id,'wpwc_s_map_userrole_intern',true);

			$wpwc_s_map_createwebsite = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createwebsite',true);

			#wp_mail( 'sandner@cronema.com', 'ninja',$wpwc_mapping_id.'<br><br>'.$formid_wpwc.'<br><br>'.$wpwc_customer_server_url.'<br><br>'.$wpwc_customer_server_username.'<br><br>'.$wpwc_customer_server_password.'<br><br>'.$wpwc_customer_server_domain);

			if($wpwc_mapping_id!='')
			{
				$wpwc_customer_server_url = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_url',true);
				$wpwc_customer_server_username = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_username',true);
				$wpwc_customer_server_password = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_password',true);
				$wpwc_customer_server_domain = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_domain',true);
				$secretcode_field = get_post_meta($wpwc_mapping_id,'wpwc_secretcode',true);

				//If the form is related to wwbsitecreator
				$wpwc_create_a_website = '1';
			  //loop all fields
        foreach( $ninja_form_data[ 'fields' ] as $field )
				{
					$wpwc_mapping_key = get_post_meta($wpwc_mapping_id,'wpwc_mapfield_'.$formplugin.'_id_'.$form_id.'_fid_'.$field['id'],true);

					if($field['id']==$secretcode_field && $wpwc_s_map_createwebsite!='immediately')
					{
					$secretcode = $field['value'];
					}

					if($wpwc_mapping_key!='')
					{
						$postarray .= '#start#'.$wpwc_mapping_key.'##'.$field['value'].'##'.$wpwc_mapping_key.'#end# ';
						if($wpwc_mapping_key == 'email'){$customeremail = $field['value'];}
					}

					if($field['id']==$wpwc_customer_server_url)
					{
					$postarray .= ' #start#wpwc_customer_server_url##'.$field['value'].'##wpwc_customer_server_url#end# ';
					}
					if($field['id']==$wpwc_customer_server_username)
					{
					$postarray .= ' #start#wpwc_customer_server_username##'.$field['value'].'##wpwc_customer_server_username#end# ';
					}
					if($field['id']==$wpwc_customer_server_password)
					{
					$postarray .= ' #start#wpwc_customer_server_password##'.$field['value'].'##wpwc_customer_server_password#end# ';
					}
					if($field['id']==$wpwc_customer_server_domain)
					{
					$postarray .= ' #start#wpwc_customer_server_domain##'.$field['value'].'##wpwc_customer_server_domain#end# ';
					}
					if($field['id']==$subdomain_id)
					{
						$subdomain = $field['value'];
					}

				}
				$postarray .= get_formid_related_system_fields($wpwc_mapping_id,$formplugin);
			}//End wpwc_mapping_id
		}
		##End Ninja fields

		##Start gravity fields
		if($formplugin == 'gravity')
		{
			#wp_mail( 'sandner@cronema.com', 'gravity',$wpwc_mapping_id.'<br><br>'.$formid_wpwc);

			$form_id = $gravity_form[ 'id' ];
			$formid_wpwc = 'wpwc_'.$formplugin.'_'.$form_id;
			$thismapping = get_formid_related_mapping($formid_wpwc);
			$wpwc_mapping_id = $thismapping['wpwc_mapping_id'];
			$wpwc_map_woo_state = $thismapping['wpwc_map_woo_state'];
			$wpwc_woo_product = $thismapping['wpwc_woo_product'];

			$domaintype = $thismapping["wpwc_s_map_domaintype"];
			$wpwc_s_map_domainextensions = $thismapping["wpwc_s_map_domainextensions"];

			$subdomain_id = get_post_meta($wpwc_mapping_id,'wpwc_required_prefix',true);
			$payment_status = get_post_meta($wpwc_mapping_id,'wpwc_map_woo_state',true);
			$wpwc_website_protocoll = get_post_meta($wpwc_mapping_id,'wpwc_website_protocoll',true);

			$wpwc_s_map_createuser = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createuser',true);
			$wpwc_s_map_userrole_intern = get_post_meta($wpwc_mapping_id,'wpwc_s_map_userrole_intern',true);

			$wpwc_s_map_createwebsite = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createwebsite',true);


			if($wpwc_mapping_id!='')
			{

				$wpwc_customer_server_url = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_url',true);
				$wpwc_customer_server_username = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_username',true);
				$wpwc_customer_server_password = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_password',true);
				$wpwc_customer_server_domain = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_domain',true);
				$secretcode_field = get_post_meta($wpwc_mapping_id,'wpwc_secretcode',true);

				//If the form is related to wwbsitecreator
				$wpwc_create_a_website = '1';
			  //loop all fields

				foreach ( $gravity_form['fields'] as $field )
				{
        	$inputs = $field->get_entry_inputs();
        	if ( is_array( $inputs ) )
					{
            foreach ( $inputs as $input )
						{
                $value = rgar( $gravity_entry, (string) $input['id'] );
								$wpwc_mapping_key = get_post_meta($wpwc_mapping_id,'wpwc_mapfield_'.$formplugin.'_id_'.$form_id.'_fid_'.$input['id'],true);


								if($input['id']==$secretcode_field && $wpwc_s_map_createwebsite!='immediately')
								{
								$secretcode = $value;
								}

								if($wpwc_mapping_key!='')
								{
									$postarray .= '#start#'.$wpwc_mapping_key.'##'.$value.'##'.$wpwc_mapping_key.'#end# ';
									if($wpwc_mapping_key == 'email'){$customeremail = $value;}
								}
                // do something with the value
								if($input['id']==$wpwc_customer_server_url)
								{
								$postarray .= ' #start#wpwc_customer_server_url##'.$value.'##wpwc_customer_server_url#end# ';
								}
								if($input['id']==$wpwc_customer_server_username)
								{
								$postarray .= ' #start#wpwc_customer_server_username##'.$value.'##wpwc_customer_server_username#end# ';
								}
								if($input['id']==$wpwc_customer_server_password)
								{
								$postarray .= ' #start#wpwc_customer_server_password##'.$value.'##wpwc_customer_server_password#end# ';
								}
								if($input['id']==$wpwc_customer_server_domain)
								{
								$postarray .= ' #start#wpwc_customer_server_domain##'.$value.'##wpwc_customer_server_domain#end# ';
								}
								if($input['id']==$subdomain_id)
								{
									$subdomain = $value;
								}

            }
        	} else
					{
            $value = rgar( $gravity_entry, (string) $field->id );
						$wpwc_mapping_key = get_post_meta($wpwc_mapping_id,'wpwc_mapfield_'.$formplugin.'_id_'.$form_id.'_fid_'.$field->id,true);

						if($field->id==$secretcode_field && $wpwc_s_map_createwebsite!='immediately')
						{
						$secretcode = $value;
						}

						if($wpwc_mapping_key!='')
						{
							$postarray .= '#start#'.$wpwc_mapping_key.'##'.$value.'##'.$wpwc_mapping_key.'#end# ';
							if($wpwc_mapping_key == 'email'){$customeremail = $value;}
						}

						if($field->id==$wpwc_customer_server_url)
						{
						$postarray .= ' #start#wpwc_customer_server_url##'.$value.'##wpwc_customer_server_url#end# ';
						}
						if($field->id==$wpwc_customer_server_username)
						{
						$postarray .= ' #start#wpwc_customer_server_username##'.$value.'##wpwc_customer_server_username#end# ';
						}
						if($field->id==$wpwc_customer_server_password)
						{
						$postarray .= ' #start#wpwc_customer_server_password##'.$value.'##wpwc_customer_server_password#end# ';
						}
						if($field->id==$wpwc_customer_server_domain)
						{
						$postarray .= ' #start#wpwc_customer_server_domain##'.$value.'##wpwc_customer_server_domain#end# ';
						}
						if($field->id==$subdomain_id)
						{
							$subdomain = $value;
						}

            // do something with the value
        	}
    		}//End For each inputs

				$postarray .= get_formid_related_system_fields($wpwc_mapping_id,$formplugin);
			}//End wpwc_mapping_id
		}
		##End gravity fields

		##Start Caldera forms
		if($formplugin == 'caldera')
		{
					#wp_mail( 'sandner@cronema.com', 'caldera',$wpwc_mapping_id.'<br><br>'.$formid_wpwc);
					$form_id = $caldera_form[ 'ID' ];
					$formid_wpwc = 'wpwc_'.$formplugin.'_'.$form_id;
					$thismapping = get_formid_related_mapping($formid_wpwc);
					$wpwc_mapping_id = $thismapping['wpwc_mapping_id'];
					$wpwc_map_woo_state = $thismapping['wpwc_map_woo_state'];
					$wpwc_woo_product = $thismapping['wpwc_woo_product'];

					$domaintype = $thismapping["wpwc_s_map_domaintype"];
					$wpwc_s_map_domainextensions = $thismapping["wpwc_s_map_domainextensions"];

					$subdomain_id = get_post_meta($wpwc_mapping_id,'wpwc_required_prefix',true);
					$payment_status = get_post_meta($wpwc_mapping_id,'wpwc_map_woo_state',true);
					$wpwc_website_protocoll = get_post_meta($wpwc_mapping_id,'wpwc_website_protocoll',true);

					$wpwc_s_map_createuser = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createuser',true);
					$wpwc_s_map_userrole_intern = get_post_meta($wpwc_mapping_id,'wpwc_s_map_userrole_intern',true);

					$wpwc_s_map_createwebsite = get_post_meta($wpwc_mapping_id,'wpwc_s_map_createwebsite',true);

					if($wpwc_mapping_id!='')
					{

						$wpwc_customer_server_url = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_url',true);
						$wpwc_customer_server_username = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_username',true);
						$wpwc_customer_server_password = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_password',true);
						$wpwc_customer_server_domain = get_post_meta($wpwc_mapping_id,'wpwc_customer_server_domain',true);
						$secretcode_field = get_post_meta($wpwc_mapping_id,'wpwc_secretcode',true);

						//If the form is related to wwbsitecreator
						$wpwc_create_a_website = '1';
					  //loop all fields
						foreach( $caldera_form[ 'fields' ] as $field_id => $field)
						{
							#$data[ $field['slug'] ] = Caldera_Forms::get_field_data( $field_id, $form );
							$value = Caldera_Forms::get_field_data( $field_id, $form );

							if($field_id==$secretcode_field && $wpwc_s_map_createwebsite!='immediately')
							{
							$secretcode = $value;
							}

							$wpwc_mapping_key = get_post_meta($wpwc_mapping_id,'wpwc_mapfield_'.$formplugin.'_id_'.$form_id.'_fid_'.$field_id,true);
							if($wpwc_mapping_key!='')
							{
								$postarray .= '#start#'.$wpwc_mapping_key.'##'.$value.'##'.$wpwc_mapping_key.'#end# ';
								if($wpwc_mapping_key == 'email'){$customeremail = $value;}
							}

							if($field_id==$wpwc_customer_server_url)
							{
							$postarray .= ' #start#wpwc_customer_server_url##'.$value.'##wpwc_customer_server_url#end# ';
							}
							if($field_id==$wpwc_customer_server_username)
							{
							$postarray .= ' #start#wpwc_customer_server_username##'.$value.'##wpwc_customer_server_username#end# ';
							}
							if($field_id==$wpwc_customer_server_password)
							{
							$postarray .= ' #start#wpwc_customer_server_password##'.$value.'##wpwc_customer_server_password#end# ';
							}
							if($field_id==$wpwc_customer_server_domain)
							{
							$postarray .= ' #start#wpwc_customer_server_domain##'.$value.'##wpwc_customer_server_domain#end# ';
							}
							if($field_id==$subdomain_id)
							{
								$subdomain = $value;
							}

						}
						$postarray .= get_formid_related_system_fields($wpwc_mapping_id,$formplugin);
					}
		}

		##End Caldera forms



		//Sending all wpcr option settings
		$options_wpcr_id = get_option('wpcr_id');
		if($options_wpcr_id)
		{
				  foreach($options_wpcr_id as $key => $val)
				  {
							if($key == 'wpcr_editor')
							{
								$editor = $val;
							}
							if($key!='wpcr_password')
							{
				    	$postarray .= '#start#'.$key.'##'.$val.'##'.$key.'#end# ';
							}
				  }
			}

			$options_wpcr_support = get_option('wpcr_support');
			if($options_wpcr_support)
			{
					  foreach($options_wpcr_support as $key => $val)
					  {
					    $postarray .= '#start#'.$key.'##'.$val.'##'.$key.'#end# ';
					  }
				}



					$options_wpcr_id = get_option('wpcr_beaver');
					if($options_wpcr_id)
					{
						foreach($options_wpcr_id as $key => $val)
						{
							$postarray .= '#start#'.$key.'##'.$val.'##'.$key.'#end# ';
						}
					}


					$options_wpcr_id = get_option('wpcr_uabb');
					if($options_wpcr_id)
					{
						foreach($options_wpcr_id as $key => $val)
						{
							$postarray .= '#start#'.$key.'##'.$val.'##'.$key.'#end# ';
						}
					}


					$options_wpcr_id = get_option('wpcr_uae');
					if($options_wpcr_id)
					{
						foreach($options_wpcr_id as $key => $val)
						{
							$postarray .= '#start#'.$key.'##'.$val.'##'.$key.'#end# ';
						}
					}



		##if one of the sent form related to websitecreator
		if($wpwc_create_a_website=='1')
		{
              $wpwc_credentials = get_wpwc_creadentials();
              $wpcr_username = $wpwc_credentials["wpcr_username"];
              $wpcr_password = $wpwc_credentials["wpcr_password"];
              $wpcr_id = $wpwc_credentials["wpcr_id"];


		            $wpcreatorbildid = '1';
								$extension_ex = explode('.',$subdomain);
								$extension = $extension_ex[1];
								$extension_product_id = get_post_meta($wpwc_mapping_id,'d_price_'.$extension,true);

		              if($wpwc_woo_product>'1' or $extension_product_id>'1')
		                {
		                  $wooexcerpt = 'woocommerce_v4';
		                }
									else if($secretcode!='')
			               {
			                 $wooexcerpt = 'payment_v4';
			               }
		              else
		                {
		                  $wooexcerpt = 'newversion_4';
		                }


								  if($secretcode=='')
										{
											$secretcode = random_secretkey(28);
										}

		           $postarray .= ' #start#wpcr_url##'.get_site_url().'##wpcr_url#end# #start#secretcode##'.$secretcode.'##secretcode#end# #start#payment_status##'.$payment_status.'##payment_status#end# #start#wpwc_website_protocoll##'.$wpwc_website_protocoll.'##wpwc_website_protocoll#end#';

							 //Add the permalink methot to the dasaset because rest rout have different targets to send backthe websites data
							 $structure = get_option( 'permalink_structure' );
							 if($structure==''){$plink ='1';}else{$plink='2';}



							 $postarray .= ' #start#permalink##'.$plink.'##permalink#end# ';

					     //Create a installation post on wp-website-creator.de
					     $url='https://wp-website-creator.com/wp-json/wp/v2/wpwc_creator_in';
					     $args = array(
						       'headers' => array(
							     'Authorization' => 'Basic ' . base64_encode( $wpcr_username . ':' . $wpcr_password )
						        ),
							     'body' => array(
								    $sendarray = 'title' => $wpcr_username.' - Domain - '.$subdomain.' - '.get_site_url(),
								   'excerpt' => $wooexcerpt,
								   'content' => $postarray
							     )
					       );
					      $wpwc_response = wp_remote_post( $url, $args );

								if ( !is_wp_error($wpwc_response) ) {
					      $data = json_decode($wpwc_response['body'], TRUE);
								$newid = $data['id'];
								}


					##Data was sent to wpwc
          ####if woocommerce product is relatet lets redirect to it
          ######



					if($wpwc_woo_product>='1')
					{
  					global $woocommerce;
						$woocommerce->cart->add_to_cart($wpwc_woo_product);
          }

					if($extension_product_id>='1')
					{
  					global $woocommerce;
						$woocommerce->cart->add_to_cart($extension_product_id);
          }


					if($wpwc_woo_product >= '1' or $extension_product_id >= '1')
					{
						WC()->session->set( '_wpwc_website_creation_id', $newid);
						WC()->session->set( '_wpwc_create_on_payment_stat', $wpwc_map_woo_state);
					}


          ###End redirect to cart if woocommerce product is selected
          ########
					$new_website = array
					(
						'post_name'    => 'Website for '.$customeremail,
						'post_title'    => 'Website for '.$customeremail. 'Website is in creation progress',
						'post_content'  => 'Website is in creation process',
						'post_status'   => 'draft',
						'post_type'   => 'wpwc_websites'
					);

					$insert_new_website = wp_insert_post($new_website);
					update_post_meta($insert_new_website,'wpwc_website_secretcode',$secretcode);
					update_post_meta($insert_new_website,'wpwc_website_id',$newid);
					update_post_meta($insert_new_website,'wpwc_website_user_email',$customeremail);
					update_post_meta($insert_new_website,'website_created','1');

					if (function_exists('wpwc_edit_active_campaign'))
					{
						wpwc_edit_active_campaign($insert_new_website);
					}

					if($wpwc_s_map_createuser == 'yes' && $customeremail!='')
					{
						if (email_exists($customeremail) == false)
						{
								$random_password = wp_generate_password(12);
								$user_id = wp_create_user( $customeremail, $random_password, $customeremail );
								#$u = new WP_User( $user_id );
								#$u->set_role("$wpwc_s_map_userrole_intern");
						}
					}

		##End if websitecreation = 1
	}
}
##Ende function send websitedata
?>
