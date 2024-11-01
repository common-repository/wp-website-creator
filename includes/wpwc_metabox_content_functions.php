<?php

####### Return a selected state for different fields selected
############
function wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id)
{
  if($wpwc_this_map_customer_server_url == $wpwc_options_id)
  {
    return 'selected';
  }else return '';
}
#############
#############

function get_email_templates($templateid){
  global $post;
	global $wpdb;


  $all_emailtemplates = "SELECT * FROM ".$wpdb->prefix."posts WHERE ".$wpdb->prefix."posts.post_type = 'wpwc_email'";
  $results = $wpdb->get_results( "$all_emailtemplates", OBJECT );
  foreach ( $results as $emailtemplates )
  {
    $wpwc_emailtemplates_id = $emailtemplates->ID;
    $wpwc_emailtemplates_name = $emailtemplates->post_title;
    $options .= '<option '.wpwc_is_selected($wpwc_emailtemplates_id,$templateid).' value="'.$wpwc_emailtemplates_id.'">'.$wpwc_emailtemplates_name.'</option>';
  }
return '<option '.wpwc_is_selected('1',$templateid).' value="1">'. __( "Do not send email", "wp-website-creator" ).'</option>'.$options;
}
## create the table for fields that can be selectedarray
## Get formid form plugin and options to return a complete td
### step 1 find out what form plugin and fields id and create field
function wpwc_get_form_fields(){
	global $post;
	global $wpdb;
  $custom = get_post_custom($post->ID);
  $wpwc_map_source = $custom["wpwc_map_source"][0];
  $emailfieldzaehler ='0';
  $designfieldzaehler ='0';
  $prefixfieldzaehler ='0';


	$formplugin_ex = explode('_',$wpwc_map_source);
	$formplugin = $formplugin_ex[1];
	$formularid = $formplugin_ex[2];
  $fieldcount = '0';

  $servervield_url = 'wpwc_customer_server_url';
  $servervield_username = 'wpwc_customer_server_username';
  $servervield_password = 'wpwc_customer_server_password';
  $servervield_domain = 'wpwc_customer_server_domain';
  $wpwc_secretcode = 'wpwc_secretcode';

  $wpwc_this_map_customer_server_url = $custom[$servervield_url][0];
  $wpwc_this_map_customer_server_username = $custom[$servervield_username][0];
  $wpwc_this_map_customer_server_password = $custom[$servervield_password][0];
  $wpwc_this_map_customer_server_domain = $custom[$servervield_domain][0];
  $wpwc_this_map_secretcode_field = $custom[$wpwc_secretcode][0];

	//Wenn es ein Ninja Formular ist
	if($formplugin=='ninja')
	{
	$all_fields = "SELECT * FROM ".$wpdb->prefix."nf3_fields WHERE ".$wpdb->prefix."nf3_fields.parent_id = '$formularid'";
	$results = $wpdb->get_results( "$all_fields", OBJECT );
	foreach ( $results as $wpwc_options )
		{
			$wpwc_options_id = $wpwc_options->id;
			$wpwc_options_name = $wpwc_options->label;
      $wpwc_options_type = $wpwc_options->type;
      $designselector = 'wpwc_map_ninja_'.$formularid.'_designfield';
      $wpwc_map_designarea = $custom[$designselector][0];
			#$wpwc_this_selected = get_post_meta($post->ID,$val,true);

      //reverscheck required fields
      $revcheck_email = get_post_meta($post->ID,'wpwc_required_email',true);
      if($revcheck_email == $wpwc_options_id){$emailfieldzaehler = $emailfieldzaehler + 1;}
      $revcheck_design = get_post_meta($post->ID,'wpwc_required_design',true);
      if($revcheck_design == $wpwc_options_id){$designfieldzaehler = $designfieldzaehler + 1;}
      $revcheck_prefix = get_post_meta($post->ID,'wpwc_required_prefix',true);
      if($revcheck_prefix == $wpwc_options_id){$prefixfieldzaehler = $prefixfieldzaehler + 1;}

			$fields .= wpwc_get_form_option_fields($wpwc_options_name,$wpwc_options_id,$formplugin,$formularid);

      $serverselection = wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id);

      $wpwc_map_customer_server_url .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      $wpwc_map_customer_server_username .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      $wpwc_map_customer_server_password .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      $wpwc_map_customer_server_domain .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';

      $wpwc_map_secretcode .= '<option '.wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      if(wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id))
      {
      $wpwc_map_secretcode_name = $wpwc_options_name;
      }

      if($wpwc_options_type=='html')
      {
        if($wpwc_map_designarea==$wpwc_options_id){$selecteddesign = ' selected ';}else {$selecteddesign = '';}
        $field_to_show_design .= '<option '.$selecteddesign.' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      }
		}
    if($emailfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_email");}
    if($designfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_design");}

	}

	//wpforms
	//Formular Felder in wpwc mapper anzeigen
	if($formplugin=='wpforms')
	{
	$content_post = get_post($formularid);
  $designselector = 'wpwc_map_wpform_'.$formularid.'_designfield';
  $wpwc_map_designarea = $custom[$designselector][0];
	$content = $content_post->post_content;
	$data = json_decode($content, true);
	#$content = unserialize($content);
	foreach ( $data['fields'] as $key )
		{

			$wpwc_options_id = $key['id'];
			$wpwc_options_name = $key['label'];
      $wpwc_options_type = $key['type'];
			#$wpwc_this_selected = get_post_meta($post->ID,$val,true);

      //reverscheck required fields
      $revcheck_email = get_post_meta($post->ID,'wpwc_required_email',true);
      if($revcheck_email == $wpwc_options_id){$emailfieldzaehler = $emailfieldzaehler + 1;}
      $revcheck_design = get_post_meta($post->ID,'wpwc_required_design',true);
      if($revcheck_design == $wpwc_options_id){$designfieldzaehler = $designfieldzaehler + 1;}
      $revcheck_prefix = get_post_meta($post->ID,'wpwc_required_prefix',true);
      if($revcheck_prefix == $wpwc_options_id){$prefixfieldzaehler = $prefixfieldzaehler + 1;}

			$fields .= wpwc_get_form_option_fields($wpwc_options_name,$wpwc_options_id,$formplugin,$formularid);

      $serverselection = wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id);

      $wpwc_map_customer_server_url .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      $wpwc_map_customer_server_username .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      $wpwc_map_customer_server_password .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      $wpwc_map_customer_server_domain .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';

      $wpwc_map_secretcode .= '<option '.wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      if(wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id))
      {
      $wpwc_map_secretcode_name = $wpwc_options_name;
      }

      if($wpwc_options_type=='html')
      {
        if($wpwc_map_designarea==$wpwc_options_id){$selecteddesign = ' selected ';}else {$selecteddesign = '';}
        $field_to_show_design .= '<option '.$selecteddesign.' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      }
		}

    if($emailfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_email");}
    if($designfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_design");}
	}

	//caldera
	//Formular Felder in wpwc mapper anzeigen
	if($formplugin=='caldera')
	{

    $designselector = 'wpwc_map_caldera_'.$formularid.'_designfield';
    $wpwc_map_designarea = $custom[$designselector][0];
		$all_fields = "SELECT * FROM ".$wpdb->prefix."cf_forms where form_id = '$formularid'";
		$results = $wpdb->get_results( "$all_fields", OBJECT );
		foreach ( $results as $wpwc_form )
		{
			$wpwc_form_config = unserialize($wpwc_form->config);
		}

		foreach ( $wpwc_form_config['fields'] as $key )
		{

			$wpwc_options_id = $key['ID'];
			$wpwc_options_name = $key['label'];
			$wpwc_options_type = $key['type'];

      //reverscheck required fields
      $revcheck_email = get_post_meta($post->ID,'wpwc_required_email',true);
      if($revcheck_email == $wpwc_options_id){$emailfieldzaehler = $emailfieldzaehler + 1;}
      $revcheck_design = get_post_meta($post->ID,'wpwc_required_design',true);
      if($revcheck_design == $wpwc_options_id){$designfieldzaehler = $designfieldzaehler + 1;}
      $revcheck_prefix = get_post_meta($post->ID,'wpwc_required_prefix',true);
      if($revcheck_prefix == $wpwc_options_id){$prefixfieldzaehler = $prefixfieldzaehler + 1;}

			if($wpwc_options_type!='button')
			{
			$fields .= wpwc_get_form_option_fields($wpwc_options_name,$wpwc_options_id,$formplugin,$formularid);
			}


      $serverselection = wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id);

      $wpwc_map_customer_server_url .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      $wpwc_map_customer_server_username .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      $wpwc_map_customer_server_password .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      $wpwc_map_customer_server_domain .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';

      $wpwc_map_secretcode .= '<option '.wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      if(wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id))
      {
      $wpwc_map_secretcode_name = $wpwc_options_name;
      }

      if($wpwc_options_type=='html')
      {
        if($wpwc_map_designarea==$wpwc_options_id){$selecteddesign = ' selected ';}else {$selecteddesign = '';}
        $field_to_show_design .= '<option '.$selecteddesign.' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
      }
		}
    if($emailfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_email");}
    if($designfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_design");}
	}

	//Formidable
	//Formidable Felder in wpwc mapper anzeigen
	if($formplugin=='formidable')
	{
    $designselector = 'wpwc_map_formidable_'.$formularid.'_designfield';
    $wpwc_map_designarea = $custom[$designselector][0];
		$all_fields = "SELECT * FROM ".$wpdb->prefix."frm_fields WHERE ".$wpdb->prefix."frm_fields.form_id = '$formularid'";
		$results = $wpdb->get_results( "$all_fields", OBJECT );
		foreach ( $results as $wpwc_options )
			{

				$wpwc_options_id = $wpwc_options->id;
				$wpwc_options_name = $wpwc_options->name;
        $wpwc_options_type = $wpwc_options->type;
				#$wpwc_this_selected = get_post_meta($post->ID,$val,true);

        //reverscheck required fields
        $revcheck_email = get_post_meta($post->ID,'wpwc_required_email',true);
        if($revcheck_email == $wpwc_options_id){$emailfieldzaehler = $emailfieldzaehler + 1;}
        $revcheck_design = get_post_meta($post->ID,'wpwc_required_design',true);
        if($revcheck_design == $wpwc_options_id){$designfieldzaehler = $designfieldzaehler + 1;}
        $revcheck_prefix = get_post_meta($post->ID,'wpwc_required_prefix',true);
        if($revcheck_prefix == $wpwc_options_id){$prefixfieldzaehler = $prefixfieldzaehler + 1;}

				$fields .= wpwc_get_form_option_fields($wpwc_options_name,$wpwc_options_id,$formplugin,$formularid);

        $serverselection = wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id);

        $wpwc_map_customer_server_url .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        $wpwc_map_customer_server_username .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        $wpwc_map_customer_server_password .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        $wpwc_map_customer_server_domain .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';

        $wpwc_map_secretcode .= '<option '.wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        if(wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id))
        {
        $wpwc_map_secretcode_name = $wpwc_options_name;
        }

        if($wpwc_options_type=='html')
        {
          if($wpwc_map_designarea==$wpwc_options_id){$selecteddesign = ' selected ';}else {$selecteddesign = '';}
          $field_to_show_design .= '<option '.$selecteddesign.' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        }

			}
      if($emailfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_email");}
      if($designfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_design");}

	}


  //gravity
	//gravity Felder in wpwc mapper anzeigen
	if($formplugin=='gravity')
	{
    $designselector = 'wpwc_map_gravity_'.$formularid.'_designfield';
    $wpwc_map_designarea = $custom[$designselector][0];
    #$all_fields = GFAPI::get_forms();
    $form = RGFormsModel::get_form_meta($formularid);
    #$results = RGFormsModel::get_field( $form, $field_id );
    #$results = $all_fields[$formularid]['fields'];
    $fieldcount = '0';
		foreach ( $form[fields] as $wpwc_options )
			{

        $wpwc_options_id = $wpwc_options->id;
				$wpwc_options_name = $wpwc_options->label;
        $wpwc_options_type = $wpwc_options->type;
				#$wpwc_this_selected = get_post_meta($post->ID,$val,true);

        //revers check required fields
        $revcheck_email = get_post_meta($post->ID,'wpwc_required_email',true);
        if($revcheck_email == $wpwc_options_id){$emailfieldzaehler = $emailfieldzaehler + 1;}

        $revcheck_design = get_post_meta($post->ID,'wpwc_required_design',true);
        if($revcheck_design == $wpwc_options_id){$designfieldzaehler = $designfieldzaehler + 1;}

        $revcheck_prefix = get_post_meta($post->ID,'wpwc_required_prefix',true);
        if($revcheck_prefix == $wpwc_options_id){$prefixfieldzaehler = $prefixfieldzaehler + 1;}

				$fields .= wpwc_get_form_option_fields($wpwc_options_name,$wpwc_options_id,$formplugin,$formularid);
        $fieldcount = $fieldcount+1;

        $serverselection = wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).','.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id);

        $wpwc_map_customer_server_url .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_url,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        $wpwc_map_customer_server_username .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_username,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        $wpwc_map_customer_server_password .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_password,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        $wpwc_map_customer_server_domain .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_domain,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';

        $wpwc_map_secretcode .= '<option '.wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id).' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        if(wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id))
        {
        $wpwc_map_secretcode_name = $wpwc_options_name;
        }

        if($wpwc_options_type=='html')
        {
          if($wpwc_map_designarea==$wpwc_options_id){$selecteddesign = ' selected ';}else {$selecteddesign = '';}
          $field_to_show_design .= '<option '.$selecteddesign.' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        }

			}
      if($emailfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_email");}
      if($designfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_design");}

	}



	if($formplugin=='cf7')
	{
    $designselector = 'wpwc_map_cf7_'.$formularid.'_designfield';
    $wpwc_map_designarea = $custom[$designselector][0];
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

				$fields .= wpwc_get_form_option_fields($name,$name,$formplugin,$formularid);

        $serverselection = wpwc_is_selected($wpwc_this_map_customer_server_url,$name).','.wpwc_is_selected($wpwc_this_map_customer_server_username,$name).','.wpwc_is_selected($wpwc_this_map_customer_server_password,$name).','.wpwc_is_selected($wpwc_this_map_customer_server_domain,$name);

        $wpwc_map_customer_server_url .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_url,$name).' value="'.$name.'">'.$name.'</option>';
        $wpwc_map_customer_server_username .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_username,$name).' value="'.$name.'">'.$name.'</option>';
        $wpwc_map_customer_server_password .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_password,$name).' value="'.$name.'">'.$name.'</option>';
        $wpwc_map_customer_server_domain .= '<option '.wpwc_is_selected($wpwc_this_map_customer_server_domain,$name).' value="'.$name.'">'.$name.'</option>';

        $wpwc_map_secretcode .= '<option '.wpwc_is_selected($wpwc_this_map_secretcode_field,$name).' value="'.$name.'">'.$name.'</option>';
        if(wpwc_is_selected($wpwc_this_map_secretcode_field,$wpwc_options_id))
        {
        $wpwc_map_secretcode_name = $name;
        }

        //reverscheck required fields
        $revcheck_email = get_post_meta($post->ID,'wpwc_required_email',true);
        if($revcheck_email == $name){$emailfieldzaehler = $emailfieldzaehler + 1;}
        $revcheck_design = get_post_meta($post->ID,'wpwc_required_design',true);
        if($revcheck_design == $name){$designfieldzaehler = $designfieldzaehler + 1;}
        $revcheck_prefix = get_post_meta($post->ID,'wpwc_required_prefix',true);
        if($revcheck_prefix == $name){$prefixfieldzaehler = $prefixfieldzaehler + 1;}

        if($wpwc_options_type=='html')
        {
          if($wpwc_map_designarea==$wpwc_options_id){$selecteddesign = ' selected ';}else {$selecteddesign = '';}
          $field_to_show_design .= '<option '.$selecteddesign.' value="'.$wpwc_options_id.'">'.$wpwc_options_name.'</option>';
        }
			}

		}
    if($emailfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_email");}
    if($designfieldzaehler < '1'){delete_post_meta($post->ID, "wpwc_required_design");}
	}


  return array('fields' => '<ul class="uk-grid-small uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l" uk-grid>'.$fields.'</ul>','designs' => $field_to_show_design, 'customer_server_url' => $wpwc_map_customer_server_url, 'customer_server_username' => $wpwc_map_customer_server_username, 'customer_server_password' => $wpwc_map_customer_server_password,'customer_server_domain'=>$wpwc_map_customer_server_domain,'wpwc_map_secretcode'=>$wpwc_map_secretcode,'wpwc_map_secretcode_name'=>$wpwc_map_secretcode_name);
	#return $fields;
	#var_dump($matches);
}
##End create fields step 1
##select form plugin and ID


## create the table for fields that can be selectedarray
## Get formid form plugin and options to return a complete td
### step 2 create options
function wpwc_get_form_option_fields($wpwc_options_name='',$wpwc_options_id='',$formplugin='',$formularid='')
{
	global $post;
	global $wpdb;
  $custom = get_post_custom($post->ID);
  $metacheck = 'wpwc_mapfield_'.$formplugin.'_id_'.$wpwc_options_id;

	$wpwc_fields_required = [
	'Email'  => 'email',
  'Design'   => 'design',
  'Prefix'   => 'prefix'
	];
	$wpwc_fields_personal = [
	'Salutation'  => 'salutation',
	'First name'  => 'first_name',
	'Last name'   => 'last_name',
	'Company'   => 'company',
	'Slogan'  => 'slogan',
  'Brand name'  => 'brand',
	'Phone'   => 'phone',
  'Fax'   => 'fax',
  'Street'   => 'street',
  'Postal code'   => 'code',
  'City'   => 'city',
  'Language'   => 'wplanguage'
	];
  $wpwc_fields_content = [
      'Home page menu name'=> 'home_menu',
      'Welcome headline'=> 'welcome_header',
      'Welcome text'=> 'welcome',
      'Home text additional'=> 'additional_text',
      'Homepage'=> 'homepage',
      'About page menu name'=> 'about_menu',
      'About page headline'=> 'about_page_headline',
      'About page text'=> 'about_page_text',
      'About subheadline 1'=> 'about_subheadline_1',
      'About description 1'=> 'about_description_1',
      'About subheadline 2'=> 'about_subheadline_2',
      'About description 2'=> 'about_description_2',
      'About subheadline 3'=> 'about_subheadline_3',
      'About description 3'=> 'about_description_3',
      'Team page menu name'=> 'team_menu',
      'Team page headline'=> 'team_page_headline',
      'Team page text'=> 'team_page_text',
      'Member 1 name'=> 'member_1_name',
      'Member 1 Information'=> 'member_1_information',
      'Member 1 Image'=> 'member_1_image',
      'Member 2 name'=> 'member_1_name',
      'Member 2 Information'=> 'member_1_information',
      'Member 2 Image'=> 'member_1_image',
      'Member 3 name'=> 'member_1_name',
      'Member 3 Information'=> 'member_1_information',
      'Member 3 Image'=> 'member_1_image',
      'Contact page menu name'=> 'contact_menu',
      'Contact page headline'=> 'contact_page_headline',
      'Contact page text'=> 'contact_page_text',
      'Contact page subheadline'=> 'contact_page_subheadline',
      'Contact page description'=> 'contact_page_description',
      'Support page menu name'=> 'support_menu',
      'Support page headline'=> 'support_page_headline',
      'Support subheadline 1'=> 'support_subheadline_1',
      'Support description 1'=> 'support_description_1',
      'Support headline 2'=> 'support_subheadline_2',
      'Support description 2'=> 'support_description_2',
      'Service page menu name'=> 'service_menu',
      'Service headline'=> 'services_headline',
      'Service description'=> 'services_description',
      'Service 1 name'=> 'service_1_name',
      'Service 1 description'=> 'service_1_description',
      'Service 1 image'=> 'service_1_image',
      'Service 2 name'=> 'service_2_name',
      'Service 2 description'=> 'service_2_description',
      'Service 2 image'=> 'service_2_image',
      'Service 3 name'=> 'service_3_name',
      'Service 3 description'=> 'service_3_description',
      'Service 3 image'=> 'service_3_image',
      'Service 4 name'=> 'service_4_name',
      'Service 4 description'=> 'service_4_description',
      'Service 4 image'=> 'service_4_image',
      'Service 5 name'=> 'service_5_name',
      'Service 5 description'=> 'service_5_description',
      'Service 5 image'=> 'service_5_image',
      'Service 6 name'=> 'service_5_name',
      'Service 6 description'=> 'service_5_description',
      'Service 6 image'=> 'service_5_image',
      'Privacy policy page menu name'=> 'imprint_menu',
      'Privacy policy headline'=> 'imprint_page_headline',
      'Privacy policy text'=> 'imprint'
	];
  $wpwc_social = [
    'Facebook'=> 'facebook_link',
    'Google'=> 'google_link',
    'Pinterest'=> 'pinterest_link',
    'Yelp'=> 'yelp_link',
    'Vimeo'=> 'vimeo_link',
    'Flickr'=> 'flickr_link',
    'Skype'=> 'skype_link',
    'Tumblr'=> 'tumblr_link',
    'Twitter'=> 'twitter_link',
    'LinkedIn'=> 'linkedin_link',
    'Xing'=> 'xing_link',
    'YouTube'=> 'youtube_link',
    'Instagram'=> 'instagram_link',
    'Dribbble'=> 'dribbble_link'
  ];
  $wpwc_fields_custom = [
	'Custom 1'  => 'wpwc_website_custom_1',
	'Custom 2'  => 'wpwc_website_custom_2',
	'Custom 3'   => 'wpwc_website_custom_3',
	'Custom 4'   => 'wpwc_website_custom_4',
	'Custom 5'  => 'wpwc_website_custom_5'
	];

  foreach ( $wpwc_fields_custom as $key => $val )
  {
    if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id,true) == $val)
    {
      $selected = ' selected ';$selectedtd .= '1';
    }else
    {
      $selected = '';$selectedtd .= '0';
    }
      $wpwc_custom_options .= '<option '.$selected.' value="'.$val.'">'.$key.'</option>';
  }

  foreach ( $wpwc_social as $key => $val )
  {
    if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id,true) == $val)
    {
      $selected = ' selected ';$selectedtd .= '1';
    }else
    {
      $selected = '';$selectedtd .= '0';
    }
      $wpwc_social_options .= '<option '.$selected.' value="'.$val.'">'.$key.'</option>';
  }

	foreach ( $wpwc_fields_required as $key => $val )
	{
		if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id,true) == $val)
    {
      $selected = ' selected ';$selectedtd .= '1';
    }else
    {
      $selected = '';$selectedtd .= '0';
    }
			$wpwc_fields_required_options .= '<option '.$selected.' value="'.$val.'">'.$key.'</option>';
	}

	foreach ( $wpwc_fields_personal as $key => $val )
	{
		if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id,true) == $val)
    {
      $selected = ' selected ';$selectedtd .= '1';
    }else
    {
      $selected = '';$selectedtd .= '0';
    }
			$wpwc_fields_personal_options .= '<option '.$selected.' value="'.$val.'">'.$key.'</option>';
	}

  if (is_array($wpwc_fields_system))
  {
	foreach ( $wpwc_fields_system as $key => $val )
	{
		if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id,true) == $val)
    {
      $selected = ' selected ';$selectedtd .= '1';
    }else
    {
      $selected = '';$selectedtd .= '0';
    }
			$wpwc_fields_system_options .= '<option '.$selected.' value="'.$val.'">'.$key.'</option>';
	}
  }

  foreach ( $wpwc_fields_content as $key => $val )
	{
		if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id,true) == $val)
    {
      $selected = ' selected ';$selectedtd .= '1';
    }else
    {
      $selected = '';$selectedtd .= '0';
    }
			$wpwc_fields_content_options .= '<option '.$selected.' value="'.$val.'">'.$key.'</option>';
	}

  foreach ( $wpwc_fields_content as $key => $val )
  {
    if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id,true) == 'design' or get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id,true) == 'prefix')
    {
      $designinfo = '<br><span style="margin:2px;background-color:orange;padding:4px;background-color:orange;font-size:12px;">!Note, This field is automatically hidden.</span>';
    }
    else if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id,true) == 'wplanguage')
    {
      $designinfo = '<br><span style="margin:2px;background-color:orange;padding:4px;background-color:orange;font-size:12px;">Please use <a href="#modal-language" style="color:white;" uk-toggle>this codes</a></span>';
    }
    else{$designinfo = '';}
  }



  foreach ( $wpwc_fields_content as $key => $val )
  {
    if(get_post_meta($post->ID,'wpwc_s_customer_server_domain',true) == $wpwc_options_id
    or get_post_meta($post->ID,'wpwc_s_customer_server_url',true) == $wpwc_options_id
    or get_post_meta($post->ID,'wpwc_s_customer_server_username',true) == $wpwc_options_id
    or get_post_meta($post->ID,'wpwc_s_customer_server_password',true) == $wpwc_options_id)
    {
      $hidefield = '1';
    }
    else{$hidefield = '';}
  }

  if($selectedtd >= '1')
  {
    $selectedtd = 'wpwcpostinfoselected';
  }else
  {
    $selectedtd = 'wpwcpostinfo';
  }
  if($hidefield!='1')
  {
    return '
    <li>
    <div class="'.$selectedtd.'">
    <span wpwcpostinfohline">'.$wpwc_options_name.'</span><br>
    <select class="required" name="wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$wpwc_options_id.'" required">
    <option value="">Please choose</option>
    <optgroup label="Required fields">'.$wpwc_fields_required_options.'</optgroup><optgroup label="Personal fields (not required)">'.$wpwc_fields_personal_options.'</optgroup><optgroup label="Content fields (not required)">'.$wpwc_fields_content_options.'</optgroup><optgroup label="Social fields (not required)">'.$wpwc_social_options.'</optgroup><optgroup label="Custom fields">'.$wpwc_custom_options.'</optgroup>
    </select>'.$designinfo.'
    </div>
    </li>';
  }
}
######End create fields to select td
######

function wpwc_admin_add_notices() {
  global $post;
	global $wpdb;
  $check_duplicate_form = '0';
  $custom = get_post_custom($post->ID);
  $wpwc_map_source = $custom["wpwc_map_source"][0];
  $wpwc_map_sources = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'wpwc_map_source' AND  meta_value = '".$wpwc_map_source."'");
  foreach($wpwc_map_sources as $key => $val)
  {
    $check_duplicate_form = $check_duplicate_form+1;
  }

    if($wpwc_map_source)
    {
      if($check_duplicate_form > '1')
      {?>
        <div class="error notice">
          <p>There are two mappings related to the same form. You must delete one. Otherwise the website will not be created!</p>
        </div>
        <?php
      }
    }

    if (get_post_meta($post->ID,'wpwc_admin_update_info',true) =='1')
    {?>
      <div class="error notice">
        <p>Themes imported, please update this mapping</p>
      </div><?php
    }
}
add_action( 'admin_notices', 'wpwc_admin_add_notices' );




############## Get all fields of a selected form from different form plugins
#####################
#####################
function wpwc_get_forms($formplugin=''){
	global $post;
	global $wpdb;
  $custom = get_post_custom($post->ID);
  $wpwc_map_source = $custom["wpwc_map_source"][0];

  ##caldera_form  fields
	if($formplugin == 'wpwc_caldera' )
	{
		$all_forms = "SELECT * FROM ".$wpdb->prefix."cf_forms where type ='primary'";
		$results = $wpdb->get_results( "$all_forms", OBJECT );
		foreach ( $results as $wpwc_form )
		{
		  $wpwc_form_id = $wpwc_form->form_id;
			$wpwc_form_config = unserialize($wpwc_form->config);
			#$wpwc_form_name = $wpwc_form_config;
			if($wpwc_form_id == str_replace($formplugin.'_','',$wpwc_map_source))
			{
				$selected = ' selected ';
			}
			else
			{
				$selected = '';
			};
			$options .= '<option '.$selected.' value="'.$formplugin.'_'.$wpwc_form_id.'">'.$wpwc_form_config['name'].'</option>';
		}
		return '<optgroup label="Caldera Forms">'.$options.'</optgroup>';
	}
  ##caldera forms fields end

  ##formidable fields
	if($formplugin == 'wpwc_formidable' )
	{
		$all_forms = "SELECT * FROM ".$wpdb->prefix."frm_forms";
		$results = $wpdb->get_results( "$all_forms", OBJECT );
		foreach ( $results as $wpwc_form )
		{
		  $wpwc_form_id = $wpwc_form->id;
			$wpwc_form_name = $wpwc_form->name;
			if($wpwc_form_id == str_replace($formplugin.'_','',$wpwc_map_source))
			{
				$selected = ' selected ';
			}
			else
			{
				$selected = '';
			};
			$options .= '<option '.$selected.' value="'.$formplugin.'_'.$wpwc_form_id.'">'.$wpwc_form_name.'</option>';
		}
		return '<optgroup label="Formidable">'.$options.'</optgroup>';
	}
  ##formidable fields end

  ##gravity fields

  if($formplugin == 'wpwc_gravity' )
  {
    $all_forms = "SELECT * FROM ".$wpdb->prefix."gf_form";
    $results = $wpdb->get_results( "$all_forms", OBJECT );
    foreach ( $results as $wpwc_form )
    {
      $wpwc_form_id = $wpwc_form->id;
      $wpwc_form_name = $wpwc_form->title;
      if($wpwc_form_id == str_replace($formplugin.'_','',$wpwc_map_source))
      {
        $selected = ' selected ';
      }
      else
      {
        $selected = '';
      };
      $options .= '<option '.$selected.' value="'.$formplugin.'_'.$wpwc_form_id.'">'.$wpwc_form_name.'</option>';
    }
    return '<optgroup label="gravity">'.$options.'</optgroup>';
  }

  ##gravity fields end


  ##ninja start fields
	if($formplugin == 'wpwc_ninja' )
	{
		$all_forms = "SELECT * FROM ".$wpdb->prefix."nf3_forms";
		$results = $wpdb->get_results( "$all_forms", OBJECT );
		foreach ( $results as $wpwc_form )
		{
		  $wpwc_form_id = $wpwc_form->id;
			$wpwc_form_name = $wpwc_form->title;
			if($wpwc_form_id == str_replace($formplugin.'_','',$wpwc_map_source))
			{
				$selected = ' selected ';
			}
			else
			{
				$selected = '';
			};
			$options .= '<option '.$selected.' value="'.$formplugin.'_'.$wpwc_form_id.'">'.$wpwc_form_name.'</option>';
		}
		return '<optgroup label="Ninja Forms">'.$options.'</optgroup>';
	}
  ##ninja end fields

  ##wpforms  fields
	if($formplugin == 'wpwc_wpforms' )
	{
		$all_forms = "SELECT * FROM ".$wpdb->prefix."posts WHERE ".$wpdb->prefix."posts.post_type = 'wpforms'";
		$results = $wpdb->get_results( "$all_forms", OBJECT );
		foreach ( $results as $wpwc_form )
		{
		  $wpwc_form_id = $wpwc_form->ID;
			$wpwc_form_name = $wpwc_form->post_title;
			if($wpwc_form_id == str_replace($formplugin.'_','',$wpwc_map_source))
			{
				$selected = ' selected ';
			}
			else
			{
				$selected = '';
			};
			$options .= '<option '.$selected.' value="'.$formplugin.'_'.$wpwc_form_id.'">'.$wpwc_form_name.'</option>';
		}
		return '<optgroup label="WPForms">'.$options.'</optgroup>';
	}
  ##end wpforms fields

  ##cf7 fields
	if($formplugin == 'wpwc_cf7' )
	{
		$all_forms = "SELECT * FROM ".$wpdb->prefix."posts WHERE ".$wpdb->prefix."posts.post_type = 'wpcf7_contact_form'";
		$results = $wpdb->get_results( "$all_forms", OBJECT );
		foreach ( $results as $wpwc_form )
		{
		  $wpwc_form_id = $wpwc_form->ID;
			$wpwc_form_name = $wpwc_form->post_title;
			if($wpwc_form_id == str_replace($formplugin.'_','',$wpwc_map_source))
			{
				$selected = ' selected ';
			}
			else
			{
				$selected = '';
			};
			$options .= '<option '.$selected.' value="'.$formplugin.'_'.$wpwc_form_id.'">'.$wpwc_form_name.'</option>';
		}
		return '<optgroup label="Contact Form 7">'.$options.'</optgroup>';
	}
  ##cf7 fields end

}

function get_selected_themes($designs,$membership,$themegroup)
{
  $array_design = array();
  global $post;
  global $wpdb;

  foreach ($designs as $key=>$val)
  {

    $selected = get_post_meta($post->ID,'wpwc_map_designs_'.$id,true);

    $id = $val['id'];
    $design_medium = $val['medium'];
    $design_small = $val['small'];
    $design_large = $val['large'];
    $demourl = $val['design_demourl'];
    #echo $design_medium;

    $designname = get_post_meta($post->ID,'wpwc_map_designname_'.$id,true);

      $designdata .= '<li><div style="text-align:center;" class="uk-card uk-card-default uk-card-body">';

      $designdata .= '<img class="wpwcdesignimage" src="'.$design_medium.'">';
      if(($themegroup == 'freethemes') or ($membership != 'Free'))
      {
      $designdata .= '<label>Designname</label><br><input name="wpwc_map_design_names_['.$id.']" class="wpwcdesigninput" value="'.$designname.'">';
      }
      $designdata .= '<br><a target="_blank" href="'.$demourl.'" style="color:white;" class="wpwcdesigndemobutton">DEMO</a><br>';
      if(($themegroup == 'freethemes') or ($membership != 'Free'))
      {
      $designdata .= '
      <select class="designselector" name="wpwc_map_designs[]">
       <option value="no">No</option>
       <option '.$selected.' value="'.$id.'">Yes</option>
      </select>';
      }
      else{$designdata .= '<br><a target="_blank" href="https://wp-website-creator.com" class="wpwcupgradebutton">Upgrade</a><br>';}

      $designdata .= '</div></li>';

    }//foreach ende
    return $designdata;
}


function wpwc_get_selected_themes()
{
  global $post;
  global $wpdb;

  $custom = get_post_custom($post->ID);
  $wpwc_map_source = $custom["wpwc_map_source"][0];

	$formplugin_ex = explode('_',$wpwc_map_source);
	$formplugin = $formplugin_ex[1];
	$formularid = $formplugin_ex[2];
  $fieldcount = '0';

  $designsarray = get_post_meta($post->ID,'wpwc_map_all_designs_'.$formularid);
  #$designsarray = unserialize($designsarray);
  #echo $designsarray;
  #$designsarray = substr($designsarray, 0, -1);


  foreach($designsarray as $key=>$val)
  {
    foreach($val as $key2=>$val2)
    {
      $designid = $val2['id'];

      $designvalues = get_post_meta($post->ID,'wpwc_map_'.$formularid.'_design_values_'.$designid,true);
      foreach($designvalues as $key3=>$val3)
      {
        $id = $val3['id'];
        $image = $val3['medium'];
        $id = $val3['id'];
        $demourl = $val3['design_demourl'];
        $designname = get_post_meta($post->ID,'wpwc_map_design_name_'.$id,true);
        $designcategorie = get_post_meta($post->ID,'wpwc_map_design_categories_'.$id,true);
        $selected = get_post_meta($post->ID,'wpwc_map_'.$formularid.'_designs_'.$id,true);
        $design .= '
        <li>
        <div style="text-align:center;" class="uk-card uk-card-default uk-card-body">
        <img class="wpwcdesignimage" src="'.$image.'">
        <label>Designname</label>
        <br><input name="wpwc_map_design_name_'.$id.'" class="wpwcdesigninput" value="'.$designname.'"><br>
        <label>Categorie</label>
        <br><input name="wpwc_map_design_categories_'.$id.'" class="wpwcdesigninput" value="'.$designcategorie.'">
        <br><a target="_blank" href="'.$demourl.'" style="color:white;" class="wpwcdesigndemobutton">DEMO</a>
        <input name="wpwc_map_design_names[]" type="hidden" value="'.$id.'">
        <input name="wpwc_map_design_categories[]" type="hidden" value="'.$id.'">
        <br>
        </div></li>';
      }
    }
  }
  if($designsarray)
  {
  return '<ul class="uk-grid-small uk-child-width-1-2@s uk-child-width-1-2@m uk-child-width-1-3@l uk-text-center" uk-sortable="handle: .uk-card" uk-grid>'.$design.'</ul>';
  }
  else
  {
  return '';
  }
}


######Get the relates mapping post id and informations if a woocommerce product is related
#############
#############
function get_formid_related_mapping($formid)
{
	global $wpdb;
	$all_mappings = "SELECT * FROM ".$wpdb->prefix."posts left join ".$wpdb->prefix."postmeta on ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id WHERE ".$wpdb->prefix."posts.post_status = 'publish' and post_type = 'wpwc_mappings' and  meta_key = 'wpwc_map_source' and meta_value  = '".$formid."' order by ID LIMIT 1";
	$results_mapping = $wpdb->get_results( "$all_mappings", OBJECT );
	foreach ( $results_mapping as $key )
	{
		$wpwc_mapping_id = $key->ID;
	}
	$wpwc_map_woo_state = get_post_meta($wpwc_mapping_id,'wpwc_map_woo_state',true);
  $wpwc_s_map_domaintype = get_post_meta($wpwc_mapping_id,'wpwc_s_map_domaintype',true);
  $wpwc_s_map_domainextensions = get_post_meta($wpwc_mapping_id,'wpwc_s_map_domainextensions',true);
	$wpwc_woo_product = get_post_meta($wpwc_mapping_id,'wpwc_woo_product',true);
	return array('wpwc_mapping_id' => $wpwc_mapping_id,'wpwc_map_woo_state' => $wpwc_map_woo_state,'wpwc_woo_product' => $wpwc_woo_product,'wpwc_s_map_domaintype' => $wpwc_s_map_domaintype,'wpwc_s_map_domainextensions' => $wpwc_s_map_domainextensions);
	#return $wpwc_mapping_id;
}

function get_formid_related_required_fields($formid)
{
	global $wpdb;
	$all_mappings = "SELECT * FROM ".$wpdb->prefix."posts left join ".$wpdb->prefix."postmeta on ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id WHERE ".$wpdb->prefix."posts.post_status = 'publish' and post_type = 'wpwc_mappings' and  meta_key = 'wpwc_map_source' and meta_value  = '".$formid."' order by ID LIMIT 1";
	$results_mapping = $wpdb->get_results( "$all_mappings", OBJECT );
	foreach ( $results_mapping as $key )
	{
		$wpwc_mapping_id = $key->ID;
	}
	$wpwc_map_woo_state = get_post_meta($wpwc_mapping_id,'wpwc_map_woo_state',true);
	$wpwc_woo_product = get_post_meta($wpwc_mapping_id,'wpwc_woo_product',true);
	return array('wpwc_mapping_id' => $wpwc_mapping_id,'wpwc_map_woo_state' => $wpwc_map_woo_state,'wpwc_woo_product' => $wpwc_woo_product);
	#return $wpwc_mapping_id;
}


#############
#############

function get_allowed_designs($designs,$membership,$themegroup)
{


  global $post;
  global $wpdb;

  $custom = get_post_custom($post->ID);
  $wpwc_map_source = $custom["wpwc_map_source"][0];

	$formplugin_ex = explode('_',$wpwc_map_source);
	$formplugin = $formplugin_ex[1];
	$formularid = $formplugin_ex[2];
  $fieldcount = '0';


  #var_dump($designsexplo);
  if($designs)
  {

    $themes = explode('##theme##',$designs);
    #var_dump($designdata).'<br><br>';
    foreach($themes as $theme)
    {
    $themedata = explode('##',$theme);



        $id = $themedata[0];
        $manufacturer = $themedata[1];
        $design_large = $themedata[2];
        $design_small = $themedata[3];
        $design_medium = $themedata[4];
        $demourl = $themedata[5];
        $themefree = $themedata[6];



    $selected = get_post_meta($post->ID,'wpwc_map_'.$formularid.'_designs_'.$id,true);
    $title = '<b>ID:</b>'.$id;
    if($design_medium)
    {
    if($manufacturer == 'beaver'){$desccolor='#e7fad2';$title .= '<br><b>Theme:</b> Beaver Builder<br><b>Editor:</b> Beaver Builder Pro<br><b>Add On:</b> UABB';}

    if($manufacturer == 'free'){$desccolor='#faebd2';$title .= '<br><b>Theme:</b> Astra Free<br><b>Editor:</b> Beaver Builder Free<br><b>Add On:</b> UABB Free';}
    if($manufacturer == 'astra'){$desccolor='#d2ebfa';$title .= '<br><b>Theme:</b> Astra Pro<br><b>Editor:</b> Beaver Builder Free<br><b>Add On:</b> UABB Pro';}
    if($manufacturer == 'astra-free'){$desccolor='#e0d2fa';$title .= '<br><b>Theme:</b> Astra Free<br><b>Editor:</b> Beaver Builder Free<br><b>Add On:</b> UABB Pro';}

    if($manufacturer == 'free-e'){$desccolor='#e0d2fa';$title .= '<br><b>Theme:</b> Astra Free<br><b>Editor:</b> Elmentor Free<br><b>Add On:</b> UAE Free';}
    if($manufacturer == 'astra-e'){$desccolor='#e0d2fa';$title .= '<br><b>Theme:</b> Astra Pro<br><b>Editor:</b> Elmentor Free<br><b>Add On:</b> UAE Pro';}
    if($manufacturer == 'astra-free-e'){$desccolor='#e0d2fa';$title .= '<br><b>Theme:</b> Astra Free<br><b>Editor:</b> Elementor Free<br><b>Add On:</b> UAE Pro';}
    #echo $design_medium;

    if($selected=='1'){$selected = 'selected';$selectedno = '';}else{$selected = '';$selectedno = 'selected';}


      $designdata .= '<li><div style="text-align:center;" class="uk-card uk-card-default uk-card-body">';

      $designdata .= '<div style="background-color:'.$desccolor.';margin-bottom:4px;">'.$title.'</div><img class="wpwcdesignimage" src="'.$design_medium.'">';

      $designdata .= '<br><a target="_blank" href="'.$demourl.'" style="color:white;" class="wpwcdesigndemobutton">DEMO</a><br>';

      if($membership =='Big Plan' or $membership == 'Agency' or $themefree == '1')
      {
      $designdata .= '
      <select class="designselector" name="wpwc_map_design_'.$id.'">
       <option '.$selectedno.' value="no">No</option>
       <option '.$selected.' value="yes">Yes</option>
      </select>
      <input type="hidden" name="wpwc_map_designs[]" value="'.$id.'"></input>
      <input type="hidden" name="wpwc_design_small_'.$id.'" value="'.$design_small.'"></input>
      <input type="hidden" name="wpwc_design_medium_'.$id.'" value="'.$design_medium.'"></input>
      <input type="hidden" name="wpwc_design_large_'.$id.'" value="'.$design_large.'"></input>
      <input type="hidden" name="wpwc_design_demourl_'.$id.'" value="'.$demourl.'"></input>
      ';
      }
      else{$designdata .= '<br><a target="_blank" href="https://wp-website-creator.com" class="wpwcupgradebutton">Upgrade</a><br>';}

      $designdata .= '</div></li>';
    }


    }//foreach ende
    if($designdata!=''){$theme_h2 = '<h2>'.$themegroup.'</h2>';}else{$theme_h2 = '';}

    if($designdata)
    {
    return '<div>'.$theme_h2.'</div><ul class="uk-grid-small uk-child-width-1-2 uk-child-width-1-6@s"  uk-grid>'.$designdata.'</ul>';
    }
  }//if ende
  #return $designdata;

}

?>
