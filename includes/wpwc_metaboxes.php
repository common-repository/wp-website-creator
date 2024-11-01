<?php
//Register the meta boxes for the new wpwc mapping posttype
function admin_init_wpwc_map()
{
  add_meta_box("wpwc-map-shortcode", "Shortcode", "wpwc_shortcode", "wpwc_mappings", "side", "default");
  add_meta_box("wpwc-map-meta", "Select a source form", "wpwc_map", "wpwc_mappings", "side", "default");
  add_meta_box("wpwc-map-other-settings", "Other settings", "wpwc_settings", "wpwc_mappings", "side", "default");
  add_meta_box("wpwc-map-cpanel", "Where to install the websites", "wpwc_cpanel", "wpwc_mappings", "side", "default");
  add_meta_box("wpwc-map-user", "Create a user", "wpwc_user", "wpwc_mappings", "side", "default");
	add_meta_box("wpwc_formfields", "Fields mapping", "wpwc_fields_mapping", "wpwc_mappings", "normal", "default");
  add_meta_box("wpwc_designarea", "Design area", "wpwc_design_area", "wpwc_mappings", "normal", "default");


  //websites user data
  add_meta_box("wpwc_websitedata", "Website user data", "wpwc_websites_data", "wpwc_websites", "normal", "low");

  //websites user data
  add_meta_box("wpwc_websiteemailagain", "Send Email", "wpwc_websites_emailsend", "wpwc_websites", "side", "default");

  //websites user data
  add_meta_box("wpwc_websitepersonaldata", "Website personal data", "wpwc_websites_personaldata", "wpwc_websites", "normal", "low");


  //websites admin data
  add_meta_box("wpwc_websiteadmindata", "Website admin data", "wpwc_websites_admin_data", "wpwc_websites", "normal", "low");
  //websites admin data
  add_meta_box("wpwc_website_account_data", "Website account data", "wpwc_websites_account_data", "wpwc_websites", "normal", "low");
  //websites admin data
  add_meta_box("wpwc_website_support_data", "Website support data", "wpwc_websites_support_data", "wpwc_websites", "normal", "low");
  //websites admin data
  add_meta_box("wpwc_website_secret_data", "Website system data", "wpwc_websites_secret_data", "wpwc_websites", "normal", "low");

  //websites user data
  add_meta_box("wpwc_websitecustomdata", "Website custom data", "wpwc_websites_customdata", "wpwc_websites", "normal", "low");

  //email templates side placeholders
  add_meta_box("wpwc_emails_sender", "Email Sender/Subject", "wpwc_email_sender", "wpwc_email", "side", "default");

  //email templates side placeholders
  add_meta_box("wpwc_email_placeholders", "Email placeholders", "wpwc_email_placeholders", "wpwc_email", "side", "default");

  //email templates side placeholders
  add_meta_box("wpwc_email_adminarea", "This part is only visible in the email that will be sent do you", "wpwc_email_admin", "wpwc_email", "normal", "default");



}
add_action("admin_init", "admin_init_wpwc_map");


function return_signupinfo()
{
return '<div class="wpwcpostalert_info">'.__( 'Please sign up on <a target="_blank" href="https://wp-website-creator.com">wp-website-creator.com</a> and tip in your credentials on <a href ="'.get_admin_url().'options-general.php?page=wp_website_creator_settings">Options page</a>', "wp-website-creator" ).'</div>';
}


function wpwc_user()
{
  global $post;
  $custom = get_post_custom($post->ID);
  $check = get_the_title($post->ID);
  global $wp_roles;

  $wpwc_map_source = $custom["wpwc_map_source"][0];

  $wpwc_s_map_createuser = get_post_meta($post->ID,'wpwc_s_map_createuser',true);
  $wpwc_s_map_userrole_intern = get_post_meta($post->ID,'wpwc_s_map_userrole_intern',true);
?>

  <div class="wpwcpostalert">Create a user from a form dataset on this website
    <select required  style="min-width:100%;" onchange="this.form.submit()" name="wpwc_s_map_createuser">
      <option <?php if($wpwc_s_map_createuser=='no'){echo ' selected ';}?> value="no">No</option>
      <option <?php if($wpwc_s_map_createuser=='yes'){echo ' selected ';}?> value="yes">Yes</option>
    </select>
  </div>

<?php
if($wpwc_s_map_createuser == 'yes')
{
  foreach ( $wp_roles->roles as $key=>$value ):
  if($wpwc_s_map_userrole_intern==$key){$selected = ' selected ';}else{$selected = '';}
  $options .= '<option '.$selected.' value="'.$key.'">'.$value['name'].'</option>';
  endforeach;
  ?>
  <div class="wpwcpostalert">Select a role for the new user
    <select required  style="min-width:100%;" name="wpwc_s_map_userrole_intern">
      <?php echo $options;?>
    </select>
  </div>
  <?php
}
}

function wpwc_settings()
{
  global $post;
  $custom = get_post_custom($post->ID);
  $check = get_the_title($post->ID);

  $wpwc_map_source = $custom["wpwc_map_source"][0];

  if($wpwc_map_source != '')
  {
    $wpwc_map_source_css = 'wpwcpostalert';
    $wpwc_selected_form = '<b>'.__( "Selected form:", "wp-website-creator" ).' '.$formplugin.' '.$formularid.'<br></b>';
  }else
  {
    $wpwc_map_source_css='wpwcpostalert_error';
  }

  $wpwc_s_map_language = get_post_meta($post->ID,'wpwc_s_map_language',true);
  $wpwc_s_map_emailtemplate = get_post_meta($post->ID,'wpwc_s_map_emailtemplate',true);
  $wpwc_s_map_userrole = get_post_meta($post->ID,'wpwc_s_map_userrole',true);
  $wpwc_s_map_createwebsite = get_post_meta($post->ID,'wpwc_s_map_createwebsite',true);
  $wpwc_woo_product = get_post_meta($post->ID,'wpwc_woo_product',true);
  $wpwc_secretcode = get_post_meta($post->ID,'wpwc_secretcode',true);
  $wpwc_website_protocoll = get_post_meta($post->ID,'wpwc_website_protocoll',true);
  $wpwc_use_designarea = get_post_meta($post->ID,'wpwc_use_designarea',true);
  $wpwc_use_designid = get_post_meta($post->ID,'wpwc_use_designid',true);

  if($wpwc_use_designid != '')
  {
    $wpwc_use_designid_css = 'wpwcpostalert';
  }else
  {
    $wpwc_use_designid_css='wpwcpostalert_error';
  }

  if($wpwc_s_map_createwebsite=='function')
  {
    $thismapping = wpwc_get_form_fields();
    $fields_secretcode = $thismapping['wpwc_map_secretcode'];
    $fieldname_secretcode = $thismapping['wpwc_map_secretcode_name'];
  }


  if($wpwc_s_map_createwebsite=='function' && $wpwc_secretcode =='')
  {
    $secretkey_error = '<div class="wpwcpostalert_error">'.__( "Please create a text field in your form and map it with this <b>Secretkey</b> Field.", "wp-website-creator" ).'</div>';
  }

  if($wpwc_s_map_createwebsite=='function' && $wpwc_secretcode !='')
  {
    $secretkey_error = '<div class="wpwcpostalert_info_klein">'.__( "To install websites that were sent with this form you need to call the function wpwc_install_website_now(<b>secretcode</b>) where the secretcode is the content of this field.", "wp-website-creator" ).'</div>';
  }


  if($check!='' && $check!='Auto Draft')

  {
  ?>

  <?php if($wpwc_use_designarea=='no'):?>
  <div class="<?php echo $wpwc_use_designid_css;?>"><?php echo __( "Design ID", "wp-website-creator" );?><br>
    <?php echo __( "If you don't use a design selection you have to specify the design id here", "wp-website-creator" );?>
    <input style="min-width:100%;" required name="wpwc_use_designid" value="<?php echo $wpwc_use_designid;?>"><br>
    <a href="#modal-designimport" style="color:blue;" uk-toggle="">Show IDs</a>
  </div>
  <?php endif;?>

  <?php if($wpwc_s_map_userrole!=''):?>

  <div class="wpwcpostalert">
    <?php echo __( "Include the design area", "wp-website-creator" );?>
    <?php if($wpwc_use_designarea!='no' && $wpwc_use_designarea!='yes'){echo '<div class="wpwcpostalert_error">'.__( "Please decide if you want to insert the design selection or if you want to install a specific design with this form.", "wp-website-creator" ).'</div>';}?>
    <select onchange="this.form.submit()" style="min-width:100%;" name="wpwc_use_designarea">
          <option value=""><?php echo __( "Please choose", "wp-website-creator" );?></option>
          <option <?php echo wpwc_is_selected($wpwc_use_designarea,'yes');?> value="yes">Yes</option>
          <option <?php echo wpwc_is_selected($wpwc_use_designarea,'no');?> value="no">No</option>
    </select>
  </div>

  <?php endif;?>


  <?php if($wpwc_s_map_language!=''):?>

  <div class="<?php echo $wpwc_map_source_css;?>">
    <?php if($wpwc_s_map_userrole==''){echo '<div class="wpwcpostalert_error">'.__( "Please select the user role that your customer should have on the new website", "wp-website-creator" ).'</div>';}?>
    <?php echo __( "What user role should a customer who has created a website receive?", "wp-website-creator" );?>
    <select style="min-width:100%;" onchange="this.form.submit()" required name="wpwc_s_map_userrole">
      <option value=""><?php echo __( "Please select a role", "wp-website-creator" );?></option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_userrole,'editor');?> value="editor">Editor</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_userrole,'author');?> value="author">Author</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_userrole,'administrator');?> value="administrator">Administrator</option>
    </select>
  </div>

  <?php endif;?>

  <?php if($wpwc_s_map_emailtemplate >= '1'):?>

  <div class="<?php echo $wpwc_map_source_css;?>">
    <?php if($wpwc_s_map_language==''){echo '<div class="wpwcpostalert_error">'.__( "Please select the language in which the website should be installed.", "wp-website-creator" ).'</div>';}?>
    <?php echo __( "Select the language you want to install this website in.", "wp-website-creator" );?>
    <select style="min-width:100%;" onchange="this.form.submit()" required name="wpwc_s_map_language">
      <option value=""><?php echo __( "Please select a language", "wp-website-creator" );?></option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'en_EN');?> value="en_EN">English</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'ru_RU');?> value="ru_RU">Русский</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'de_DE');?> value="de_DE">Deutsch</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'es_ES');?> value="es_ES">Español</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'fr_FR');?> value="fr_FR">Français</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'ja');?> value="ja">Japanese</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'it_IT');?> value="it_IT">Italiano</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'nl_NL');?> value="nl_NL">Nederlands</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'pl_PL');?> value="pl_PL">Polish</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'pt_PT');?> value="pt_PT">Português</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'sv_SE');?> value="sv_SE">Swedish</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'tr_TR');?> value="tr_TR">Turkish</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'zh_CN');?> value="zh_CN">Chinese</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'cs_CZ');?> value="cs_CZ">Czech</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'he_IL');?> value="he_IL">Hebrew</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'hi');?> value="hi">Indian</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'in');?> value="in">Indonesian</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'el');?> value="el">Greek</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'ar');?> value="ar">Arabisch</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'af');?> value="af">Afrikaans</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_language,'ko_KR');?> value="ko_KR">Korean</option>
    </select>
  </div>

  <?php endif;?>



  <?php if($wpwc_map_source != ''):?>

  <div class="<?php echo $wpwc_map_source_css;?>">
    <?php echo __( "Select a email template for the credentials email.", "wp-website-creator" );?>
    <?php if($wpwc_s_map_emailtemplate == ''){ echo '<div class="wpwcpostalert_error">'.__( "Please choose the email template you want your customers to receive after a website is installed. You can create a template under <b>All Email Temlates</b>.", "wp-website-creator" ).'</div>';}?>
    <select style="min-width:100%;" required onchange="this.form.submit()" name="wpwc_s_map_emailtemplate">
      <option value=""><?php echo __( "Select a email template", "wp-website-creator" );?></option>
      <?php
      echo get_email_templates($wpwc_s_map_emailtemplate);
      ?>
    </select>
  </div>

  <?php endif;?>


  <?php if($wpwc_use_designarea=='yes' or $wpwc_use_designarea=='no'):?>

  <div class="wpwcpostalert"><?php echo __( "If you run a wildcard certificate or auto create Let's encript certificates you should choose https:", "wp-website-creator" );?>
    <select style="min-width:100%;" required name="wpwc_website_protocoll">
          <option value=""><?php echo __( "Please choose", "wp-website-creator" );?></option>
          <option <?php echo wpwc_is_selected($wpwc_website_protocoll,'http');?> value="http">http:</option>
          <option <?php echo wpwc_is_selected($wpwc_website_protocoll,'https');?> value="https">https:</option>
    </select>
  </div>




  <?php if($wpwc_woo_product<='1'):?>
  <div class="<?php echo $wpwc_map_source_css;?>"><?php echo __( "when should the website be installed?", "wp-website-creator" );?>
    <select onchange="this.form.submit()" style="min-width:100%;" name="wpwc_s_map_createwebsite">
      <option <?php echo wpwc_is_selected($wpwc_s_map_createwebsite,'immediately');?> value="immediately">Immediately</option>
      <option <?php echo wpwc_is_selected($wpwc_s_map_createwebsite,'function');?> value="function">When function is called</option>
    </select>
    <?php if($wpwc_s_map_createwebsite=='function'):?>
    <label><?php echo __( "Secret key field", "wp-website-creator" );?></label>
    <select style="min-width:100%;" required onchange="this.form.submit()" name="wpwc_secretcode">
      <option value=""><?php echo __( "Please select a field", "wp-website-creator" );?></option>
      <?php echo $fields_secretcode;?>
    </select>
    <?php endif;?>
    <?php echo $secretkey_error;?>
  </div>
  <?php endif;?>

  <?php endif;?>

<?php }
}


##metabox content to select a form to work on and create websites
function wpwc_map()
{
  global $post;
  $custom = get_post_custom($post->ID);
  $wpwc_map_source = $custom["wpwc_map_source"][0];
  $formplugin_ex = explode('_',$wpwc_map_source);
	$formplugin = $formplugin_ex[1];
	$formularid = $formplugin_ex[2];

  if($wpwc_map_source != '')
  {
    $wpwc_map_source_css = 'wpwcpostalert';
    $wpwc_selected_form = '<b>'.__( "Selected form:", "wp-website-creator" ).' '.$formplugin.' '.$formularid.'<br></b>';
  }else
  {
    $wpwc_map_source_css='wpwcpostalert_error';
  }

  $check = get_the_title($post->ID);
  if ( is_plugin_active( 'ninja-forms/ninja-forms.php' ) or is_plugin_active( 'formidable/formidable.php' ) or is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) or is_plugin_active( 'caldera-forms/caldera-forms.php' ) or is_plugin_active( 'caldera-forms/caldera-core.php' ) or is_plugin_active( 'gravityforms/gravityforms.php' ) or function_exists( 'wpforms' ))
  {?>
    <div style="text-align:center;">
      <a href="#modal-vid" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full"><?php echo __( "Video tutorial", "wp-website-creator" );?></button></a><br><br>
    </div>
  <?php
  if($check!='' && $check!='Auto Draft')
  {
  ?>
	<div class="<?php echo $wpwc_map_source_css;?>"><?php echo __( "Select the form from which a website should be created", "wp-website-creator" );?>
		<select style="min-width:100%;" required onchange="this.form.submit()" name="wpwc_map_source">
			<option value=""><?php echo __( "Please select a form", "wp-website-creator" );?></option><?php
			if ( is_plugin_active( 'ninja-forms/ninja-forms.php' ) ) {echo wpwc_get_forms('wpwc_ninja');}
			if ( is_plugin_active( 'formidable/formidable.php' ) ) {echo wpwc_get_forms('wpwc_formidable');}
			if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {echo wpwc_get_forms('wpwc_cf7');}
			if ( is_plugin_active( 'caldera-forms/caldera-forms.php' ) or is_plugin_active( 'caldera-forms/caldera-core.php' )) {echo wpwc_get_forms('wpwc_caldera');}
			if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) {echo wpwc_get_forms('wpwc_gravity');}
			if ( function_exists( 'wpforms' ) ) {echo wpwc_get_forms('wpwc_wpforms');}
			?>
		</select>
  </div>
  <?php
  }else
  {
    $display = '<div class="wpwcpostalert_error">'.__( "1. Please tip in a title and save this mapping", "wp-website-creator" ).'</div>';
  }

}else
{
  $display = '<div class="wpwcpostalert_info">'.__( "You need one of these form plugins", "wp-website-creator" ).'
  <br><a target="_blank" href="https://wordpress.org/plugins/ninja-forms/">Ninja Forms Free</a>
  <br><a target="_blank" href="https://wordpress.org/plugins/wpforms-lite/">WPForms Lite</a>
  <br><a target="_blank" href="https://wordpress.org/plugins/formidable/">Formidable Free</a>
  <br><a target="_blank" href="https://wordpress.org/plugins/caldera-forms/">Caldera Free</a>
  <br><a target="_blank" href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a>
  <!--<br><a target="_blank" href="https://www.gravityforms.com/">Gravity</a>-->
  </div>';
}

echo $display;
}

function get_reselect_required_field($formplugin,$formularid,$requiredfield,$field)
{
  global $wpdb;
  if($formplugin=='ninja')
  {

      $research_prefixfield = "SELECT id FROM ".$wpdb->prefix."nf3_fields WHERE ".$wpdb->prefix."nf3_fields.parent_id = '".$formularid."' and ".$wpdb->prefix."nf3_fields.id ='".$field."'";
      $re_prefixfield = $wpdb->get_results( "$research_prefixfield", OBJECT );
      foreach ( $re_prefixfield as $key=>$val )
        {
          $re_prefix = $val;
        }
      return $re_prefix;
  }

  if($formplugin=='wpforms')
  {
    $content_post = get_post($formularid);
		$content = $content_post->post_content;
		$data = json_decode($content, true);
		#$content = unserialize($content);
		foreach ( $data['fields'] as $key )
			{
        if($key['id'] == $field)
        {
          $re_prefix = $key['id'];
        }
      }
      return $re_prefix;
  }

  if($formplugin =='formidable')
  {
    $research_prefixfield = "SELECT id FROM ".$wpdb->prefix."frm_fields WHERE ".$wpdb->prefix."frm_fields.form_id = '".$formularid."' and ".$wpdb->prefix."frm_fields.id = '".$field."'";

  	$re_prefixfield = $wpdb->get_results( "$research_prefixfield", OBJECT );

  	foreach ( $re_prefixfield as $key=>$val )
    {
      $re_prefix = $val;
    }
  return $re_prefix;
  }

  if($formplugin == 'caldera')
  {
    $all_fields = "SELECT * FROM ".$wpdb->prefix."cf_forms where form_id = '$formularid'";
		$results = $wpdb->get_results( "$all_fields", OBJECT );
		foreach ( $results as $wpwc_form )
		{
			$wpwc_form_config = unserialize($wpwc_form->config);
		}

		foreach ( $wpwc_form_config['fields'] as $key )
		{
      if($key['ID'] == $field)
      {
        $re_prefix = $key['ID'];
      }
    }
    return $re_prefix;
  }

  if($formplugin == 'cf7')
  {
    $results = get_post_meta($formularid,'_form',true);
    $re = '/(?<=\[)([^\]]+)/';
    preg_match_all($re, $results, $matches, PREG_SET_ORDER, 0);
    #$matches unserialize($matches);
    foreach ( $matches as $key )
    {
      $seperator = explode(' ',$key[0]);
      $name=$seperator[1];
      if($name==$field)
      {
        $re_prefix = '1';
      }
    }
    return $re_prefix;
  }

  if($formplugin == 'gravity')
  {
    $all_fields = GFAPI::get_form($formularid);
  	$results = $all_fields['fields'];

  	foreach ( $results as $wpwc_options )
  		{
  			if($wpwc_options->id == $field)
        {
          $re_prefix = $field;
        }
      }
    return $re_prefix;
  }

}

//The content for the shortcode box
function wpwc_shortcode()
{
  global $post;
  global $wpdb;
  $custom = get_post_custom($post->ID);
  $check = get_the_title($post->ID);
  $wpwc_map_source = $custom["wpwc_map_source"][0];

  $formplugin_ex = explode('_',$wpwc_map_source);
	$formplugin = $formplugin_ex[1];
	$formularid = $formplugin_ex[2];

  $wpwc_s_map_servers = get_post_meta($post->ID,'wpwc_s_map_servers',true);
  $emailokid = get_post_meta($post->ID,'wpwc_required_email',true);
  $designokid = get_post_meta($post->ID,'wpwc_required_design',true);
  $prefixokid = get_post_meta($post->ID,'wpwc_required_prefix',true);



  $check_map_emailtemplate = get_post_meta($post->ID,'wpwc_s_map_emailtemplate',true);
  $check_map_language = get_post_meta($post->ID,'wpwc_s_map_language',true);


  if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$emailokid,true) == 'email' && (get_reselect_required_field($formplugin,$formularid,'email',$emailokid)>='1'))
  {$emailok = 'ok';}
  if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$designokid,true) ==  'design' && (get_reselect_required_field($formplugin,$formularid,'design',$designokid)>='1'))
  {$designok = 'ok';}
  if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$prefixokid,true) ==  'prefix' && (get_reselect_required_field($formplugin,$formularid,'prefix',$prefixokid)>='1'))
  {$prefixok = 'ok';}

  $wpwc_map_source = $custom["wpwc_map_source"][0];

  if(($wpwc_map_source != 'Please select a source' && $wpwc_map_source != '') && $check )
  {
    if($wpwc_s_map_servers != 'customerservers')
    {
     if($emailok == 'ok' && $designok == 'ok' && $prefixok == 'ok' && $check_map_language!= '')
     {
	   $formplugin_ex = explode('_',$wpwc_map_source);
	   $formplugin = $formplugin_ex[1];
	   $formularid = $formplugin_ex[2];
     echo '<div class="wpwcpostalert_info">Copy and paste this shortcode where you would like to see the form</div>
     <div style="margin-bottom:12px;">[wpwc_form id="wpwc_'.$formplugin.'_'.$formularid.'"]</div>';
     }
     else
     {

     }
    }

    if($wpwc_s_map_servers == 'customerservers')
    {
     if($emailok == 'ok' && $designok == 'ok' && $check_map_language!= '')
     {
	   $formplugin_ex = explode('_',$wpwc_map_source);
	   $formplugin = $formplugin_ex[1];
	   $formularid = $formplugin_ex[2];
     echo '<div style="margin-bottom:12px;">[wpwc_form id="wpwc_'.$formplugin.'_'.$formularid.'"]</div>';
     }
     else
     {
      if($emailok!='ok'){echo '<div class="wpwcpostalert_error">3. Please create a email field in your form and mapp it with the required <b>EMAIL</b> option</div>';}
      if($designok!='ok'){echo '<div class="wpwcpostalert_error"><b>!!Please note!!</b><br>4. Please create a design field (simple text field) in your form and mapp it with the required <b>DESIGN</b> option.</div>';}
      if($check_map_language==''){echo '<div class="wpwcpostalert_error">Please select the language in which the website should be installed.</div>';}
     }
    }

  }
}

//email template placeholders
function wpwc_email_admin()
{
  global $post;
  echo '
  <div>
  <textarea rows="10" style="min-width:100%;" name="wpwc_admin_info">'.get_post_meta($post->ID, "wpwc_admin_info",true).'</textarea>
  </div>';
}

//email template placeholders
function wpwc_email_sender()
{
  global $post;
  echo '
  <div style="text-align:center;">
    <a href="#modal-emailtemplate" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">Video tutorial</button></a><br><br>
  </div>
  <div>
  <label>Sender name</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_sender_name",true).'" name="wpwc_sender_name">
  </div>

  <div>
  <label>Sender email</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_sender_email",true).'" name="wpwc_sender_email">
  </div>

  <div>
  <label>Subject</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_email_subject",true).'" name="wpwc_email_subject">
  </div>


    <div style="text-align:center;"><br>
    <label>Test email</label>
    <a href="#modal-testemail" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">Send test email</button></a>
    </div>
    ';
}

//email template placeholders
function wpwc_email_placeholders()
{
  global $post;

  echo '
  <div class="wpwcpostalert_info">Only use placeholders for fields you use in your form.<br><b>For example</b><br>It doesn\'t make sense to use a first name placeholder if you don\'t have a first name field in your form.</div>
  <div class="wpwcpostalert">
  <label>Use this placeholders in your email content</label><br>
  #website_salutation#<br>
  #website_first_name#<br>
  #website_last_name#<br><br>
  #website_domain#<br>
  #website_login_domain#<br>
  #website_username#<br>
  #website_password#<br>
  #website_user_email#<br>
  #website_user_role#<br><br>
  #website_admin_username#<br>
  #website_admin_password#<br><br>
  #account_login_domain#<br>
  #account_username#<br>
  #account_password#<br>
  #account_ftp_host#<br>
  #account_ftp_username#<br>
  #account_ftp_password#<br><br>
  #support_videotutorials#<br>
  #support_paymentpage#<br><br>
  #website_custom_1#<br>
  #website_custom_2#<br>
  #website_custom_3#<br>
  #website_custom_4#<br>
  #website_custom_5#<br>
  </div>

  ';
}


function wpwc_websites_emailsend()
{
  global $post;
  $custom = get_post_custom($post->ID);
  $wpwc_email_template_id = $custom["wpwc_email_template_id"][0];
  ?>
  <div class="wpwcpostalert_info">Select a email template for the credentials email.
    <select onchange="this.form.submit()" style="min-width:100%;" name="wpwc_email_template_id">
      <option value="">Please select</option>
      <?php
      echo get_email_templates($wpwc_email_template_id);
      ?>
    </select>
  </div>
  <div style="text-align:center;"><br>
  <label>Resend Email</label>
  <a href="#modal-resendmail" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">Resend</button></a>
  </div>

  <?php
}

//Website posttype
function wpwc_websites_data()
{
  global $post;
  $websitefields = '
  <div>
  <label>WordPress domain</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_domain",true).'" name="wpwc_website_domain">
  </div>

  <div>
  <label>WordPress login domain</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_login_domain",true).'" name="wpwc_website_login_domain">
  </div>

  <div>
  <label>WordPress username</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_username",true).'" name="wpwc_website_username">
  </div>

  <div>
  <label>WordPress password</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_password",true).'" name="wpwc_website_password">
  </div>

  <div>
  <label>WordPress user role</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_user_role",true).'" name="wpwc_website_user_role">
  </div>';
  echo $websitefields;
}

//Website posttype
function wpwc_websites_personaldata()
{
  global $post;
  $websitefields = '
  <div>
  <label>Salutation</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_salutation",true).'" name="wpwc_website_salutation">
  </div>

  <div>
  <label>First name</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_first_name",true).'" name="wpwc_website_first_name">
  </div>

  <div>
  <label>Last name</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_last_name",true).'" name="wpwc_website_last_name">
  </div>

  <div>
  <label>Email</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_user_email",true).'" name="wpwc_website_user_email">
  </div>
';
  echo $websitefields;
}

//Website posttype
function wpwc_websites_customdata()
{
  global $post;
  $websitefields = '
  <div>
  <label>Custom 1</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_custom_1",true).'" name="wpwc_website_custom_1">
  </div>

  <div>
  <label>Custom 2</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_custom_2",true).'" name="wpwc_website_custom_2">
  </div>

  <div>
  <label>Custom 3</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_custom_3",true).'" name="wpwc_website_custom_3">
  </div>

  <div>
  <label>Custom 4</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_custom_4",true).'" name="wpwc_website_custom_4">
  </div>

  <div>
  <label>Custom 5</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_custom_5",true).'" name="wpwc_website_custom_5">
  </div>
';
  echo $websitefields;
}

function wpwc_websites_admin_data()
{
  global $post;
  $websitefields = '
  <div>
  <label>Admin username</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_admin_username",true).'" name="wpwc_website_admin_username">
  </div>

  <div>
  <label>Admin password</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_admin_password",true).'" name="wpwc_website_admin_password">
  </div>
  ';
  echo $websitefields;
}
//Website posttype
function wpwc_websites_account_data()
{
  global $post;
  $websitefields = '
  <div>
  <label>Account login domain</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_account_login_domain",true).'" name="wpwc_website_account_login_domain">
  </div>

  <div>
  <label>Account username</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_account_username",true).'" name="wpwc_website_account_username">
  </div>

  <div>
  <label>Account password</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_website_account_password",true).'" name="wpwc_website_account_password">
  </div>

  <div>
  <label>FTP host</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_account_ftp_host",true).'" name="wpwc_account_ftp_host">
  </div>

  <div>
  <label>FTP user</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_account_ftp_username",true).'" name="wpwc_account_ftp_username">
  </div>

  <div>
  <label>FTP password</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_account_ftp_password",true).'" name="wpwc_account_ftp_password">
  </div>
  ';
  echo $websitefields;
}
//Website posttype
function wpwc_websites_secret_data()
{
  global $post;
  $websitefields = '
  <div class="wpwcpostalert_info">
  <label>Secret key for website creation</label>
  '.get_post_meta($post->ID, "wpwc_website_secretcode",true).'<br>
  '.__( "If you have chosen 'Do not install immediately' you can start the installation of this website by calling the function <br>wpwc_install_website_now(wpwc_website_secretcode).<br>
  Where the wpwc_website_secretcode is this secret key<br>The secret key is stored in your related 'Secret key field'", "wp-website-creator" ).'
  </div>

  <div>
  <label>Error message</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_email_error_message",true).'" name="wpwc_email_error_message">
  </div>


';
  echo $websitefields;
}

//Website posttype
function wpwc_websites_support_data()
{
  global $post;
  $websitefields = '
  <div>
  <label>URL to your videotutorials if needed</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_support_videotutorials",true).'" name="wpwc_support_videotutorials">
  </div>

  <div>
  <label>URL to your payment page if needed</label>
  <input style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_support_paymentpage",true).'" name="wpwc_support_paymentpage">
  </div>


';
  echo $websitefields;
}

##create the area where user can select a installation methos
#####
function wpwc_cpanel()
{
  global $post;
  $custom = get_post_custom($post->ID);
  $wpwc_map_source = $custom["wpwc_map_source"][0];
  $check = get_the_title($post->ID);
  if(($wpwc_map_source != 'Please select a source' && $wpwc_map_source != '') && $check)
  {
	$formplugin_ex = explode('_',$wpwc_map_source);
	$formplugin = $formplugin_ex[1];
	$formularid = $formplugin_ex[2];

  $memberships = wpwc_get_themes();
  $membership = $memberships['membership'];

  $domaintype = $custom["wpwc_s_map_domaintype"][0];

  $wpwc_s_map_domainextensions = $custom["wpwc_s_map_domainextensions"][0];

  //GET Where install the forms wpwc server agencyserver or customer server
  $wpwc_map_servers = $custom["wpwc_s_map_servers"][0];

  //GET the url username and password of a agency server
  $wpwc_map_server = $custom["wpwc_map_server"][0];
  #$wpwc_map_server_username = $custom["wpwc_map_username"][0];
  #$wpwc_map_server_password = $custom["wpwc_map_password"][0];

  if($wpwc_map_servers=='wpwcservers')
  {
    $wpwc_map_servers_info='<div class="wpwcpostalert_info"><span style="font-size:20px;"><b>!!please note!!</b></span>
    <br>ON WPWC SERVERS<br>You can install websites fully automatically on our server.
    <br><br>We store your pages for <br><b>7 days (Free membership)<br>14 days (Agency membership)<br>30 days (Big plan membership).</b><br> So you have enough time to migrate your pages to your server with a migration plugin like <a href="https://snapcreek.com/">duplicator</a> or <a href="https://shareasale.com/r.cfm?b=948660&u=1558297&m=68863&urllink=&afftrack=">Backup Buddy</a>.
    <br><br>Each website is created on a subdomain on one of our TLDs. For example john-doe.wprouter.com
    <br></div>';
  }

  if(get_post_meta($post->ID, "wpwc_s_whm_login_type",true) == 'password'){$passwordselect = ' selected ';$passwordselected ='1';}
  if(get_post_meta($post->ID, "wpwc_s_whm_login_type",true) == 'token'){$tokenselect = ' selected ';$tokenselected ='1';}
  if(get_post_meta($post->ID, "wpwc_s_server_error",true) != ''){$servererror = '<div style="padding:5px;background-color:red;color:white;">'.get_post_meta($post->ID, "wpwc_s_server_error",true).'</div>';}
  if((get_post_meta($post->ID, "wpwc_s_server_error",true) != ''  or get_post_meta($post->ID, "wpwc_s_server_login_url",true) == '') && $wpwc_map_servers=='whm'){$servererrorurlinfo = '<div class="wpwcpostalert_error_dark">Please check your login url. WHM servers usually use a URL with 2087 port e.g. https://example.com:2087</div>';}
  if((get_post_meta($post->ID, "wpwc_s_server_error",true) != '' or get_post_meta($post->ID, "wpwc_s_server_login_url",true) == '') && $wpwc_map_servers=='cpanel'){$servererrorurlinfo = '<div class="wpwcpostalert_error_dark">Please check your login url. cPanel hostings usually use a URL with 2083 port e.g. https://example.com:2083</div>';}
  if(get_post_meta($post->ID, "wpwc_s_server_error",true) != '' && $wpwc_map_servers=='plesk'){$servererrorurlinfo = '<div class="wpwcpostalert_error_dark">Please check your login url. plesk hostings usually use a URL with 8443 port e.g. https://example.com:8443</div>';}
  if((get_post_meta($post->ID, "wpwc_s_server_error",true) != '' && get_post_meta($post->ID, "wpwc_s_whm_login_type",true) == 'token'))
  {
    $servererrortokeninfo = '<div class="wpwcpostalert_error_dark">Make sure you have entered the correct password method. If you specify token you must first create a token in your WHM account.</div>';
  }

  if(!get_post_meta($post->ID, "wpwc_s_server_error",true)
  && get_post_meta($post->ID, "wpwc_s_server_login_url",true) != ''
  && get_post_meta($post->ID, "wpwc_s_server_login_username",true) != ''
  && get_post_meta($post->ID, "wpwc_s_map_maindomain",true) == ''
  && get_post_meta($post->ID, "wpwc_s_map_package",true) == ''
  && get_post_meta($post->ID, "wpwc_s_server_login_password",true) != '')
  {
    $servererror_domain_package = '<div style="padding:5px;background-color:red;color:white;">Two more required settings</div>';
  }

  if( (get_post_meta($post->ID, "wpwc_s_whm_login_type",true) != 'password'  and get_post_meta($post->ID, "wpwc_s_whm_login_type",true) != 'token') or get_post_meta($post->ID, "wpwc_s_whm_login_type",true) == ''){$logintype_css='wpwcpostalert_error';}else{$logintype_css='wpwcpostalert';}

  if($wpwc_map_servers=='whm')
  {
    $chooselogintype =
    $servererrortokeninfo.'<div class="'.$logintype_css.'">
    <label>Login type</label>
    <select style="min-width:100%;" required name="wpwc_s_whm_login_type">
      <option value="">'.__( "Please choose", "wp-website-creator" ).'</option>
      <option '.$passwordselect.' value="password">Username & Password</option>
      <option '.$tokenselect.' value="token">Username & Token</option>
    </select>
    </div>';
    $token = '/token';
  }
  if(  ($wpwc_map_servers=='whm' or $wpwc_map_servers=='cpanel' or $wpwc_map_servers=='plesk') && ( ($domaintype != '' && $membership == 'Big Plan') or ($membership != 'Big Plan') )  )
  {
    if(get_post_meta($post->ID, "wpwc_s_server_login_url",true) ==''){$login_url_css = 'wpwcpostalert_error';}else{$login_url_css = 'wpwcpostalert';}
    if(get_post_meta($post->ID, "wpwc_s_server_login_username",true) ==''){$login_username_css = 'wpwcpostalert_error';}else{$login_username_css = 'wpwcpostalert';}
    if(get_post_meta($post->ID, "wpwc_s_server_login_password",true) ==''){$login_password_css = 'wpwcpostalert_error';}else{$login_password_css = 'wpwcpostalert';}
    $wpwc_map_servers_info = $servererror
    .$servererrorurlinfo.'
    <div class="'.$login_url_css.'">

    <label>Server login URL</label>
  	<input required style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_s_server_login_url",true).'" name="wpwc_s_server_login_url">
    </div>
    <div class="'.$login_username_css.'">
    <label>Server username</label>
  	<input required style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_s_server_login_username",true).'" name="wpwc_s_server_login_username">
    </div>
    <div class="'.$login_password_css.'">
    <label>Server password'.$token.'</label>
  	<input required style="min-width:100%;" type="text" value="'.get_post_meta($post->ID, "wpwc_s_server_login_password",true).'" name="wpwc_s_server_login_password">
    </div>'.$chooselogintype;

  if(get_post_meta($post->ID, "wpwc_map_domains",true) != '' && !get_post_meta($post->ID, "wpwc_s_server_error",true))
  {

    $cserverdomains = get_post_meta($post->ID, "wpwc_map_domains",true);
    $cserverpackages = get_post_meta($post->ID, "wpwc_map_pakete",true);
    $cservercustomers = get_post_meta($post->ID, "wpwc_map_customers",true);
    $pleskuserid = get_post_meta($post->ID, "wpwc_s_create_under_plesk_customer",true);


    $cservercustomers_explo = explode('-cend-',$cservercustomers);
    if(is_array($cservercustomers_explo))
    {
    foreach($cservercustomers_explo as $cservercustomer)
      {
        $pleskownerid_explode = explode('-csplit-',$cservercustomer);
        $pleskownerid = $pleskownerid_explode[1];
        $pleskownerusername = $pleskownerid_explode[0];
        if($pleskuserid == $pleskownerid){$selected = ' selected ';}else{$selected = '';}
        if($pleskownerid>'1')
        {
        $pleskowneroptions .= '<option '.$selected.' value="'.$pleskownerid.'">'.$pleskownerusername.'</option>';
        }
      }
    }else {
      $pleskownerid_explode = explode('-csplit-',$cservercustomer);
      $pleskownerid = $pleskownerid_explode[1];
      $pleskownerusername = $pleskownerid_explode[0];
      if($pleskuserid == $pleskownerid){$selected = ' selected ';}else{$selected = '';}
      if($pleskownerid>'1')
      {
      $pleskowneroptions = '<option '.$selected.' value="'.$pleskownerid.'">'.$pleskownerusername.'</option>';
      }
    }

      $serverdomains_explo = explode('#',$cserverdomains);
      foreach($serverdomains_explo as $serverdomain)
      {
        if(get_post_meta($post->ID, "wpwc_s_map_maindomain",true) == $serverdomain){$selected = ' selected ';$domainselected='1';}else{$selected = '';}
        $serverdomainoptions .= '<option '.$selected.' value="'.$serverdomain.'">'.$serverdomain.'</option>';
      }

      $serverpackages_explo = explode('#',$cserverpackages);
      foreach($serverpackages_explo as $serverpackage)
      {
        if($serverpackage!='')
        {
        if(get_post_meta($post->ID, "wpwc_s_map_package",true) == $serverpackage){$selected = ' selected ';$serverpackageselected='1';}else{$selected = '';}
        $serviceplanoptions .= '<option '.$selected.' value="'.$serverpackage.'">'.$serverpackage.'</option>';
      }
      }

      if($wpwc_map_servers=='whm' or $wpwc_map_servers=='plesk')
      {
        if($serverpackageselected!='1' or get_post_meta($post->ID, "wpwc_s_map_package",true) == ''){$servicepackage_css = 'wpwcpostalert_error';}else{$servicepackage_css = 'wpwcpostalert';}
        $packagesselector = '
      <div class="'.$servicepackage_css.'">Select the service plan you want to use for this website.
  		<select style="min-width:100%;" required name="wpwc_s_map_package">
  			<option value="">'.__( "Please choose", "wp-website-creator" ).'</option>
  			'.$serviceplanoptions.'
  		</select>
      </div>';
      }

      if($wpwc_map_servers=='plesk')
      {
        if(get_post_meta($post->ID,'wpwc_s_create_plesk_customer',true)=='1'){$select0 = '';$select1 = ' selected ';}
        else{$select0 = ' selected ';$select1 = '';}
        $create_plesk_customer = '
      <div class="wpwcpostalert">Create a plesk customer Account?
  		<select onchange="this.form.submit()" style="min-width:100%;" required name="wpwc_s_create_plesk_customer">
  			<option value="">'.__( "Please choose", "wp-website-creator" ).'</option>
  			<option '.$select0.' value="no">No</option>
        <option '.$select1.' value="1">Yes</option>
  		</select>
      </div>';
      }

      if($wpwc_map_servers=='plesk' && get_post_meta($post->ID,'wpwc_s_create_plesk_customer',true)!='1')
      {
        if(get_post_meta($post->ID, "wpwc_s_create_under_plesk_customer",true) ==''){$plesk_customer_css = 'wpwcpostalert_error';}else{$plesk_customer_css = 'wpwcpostalert';}
        $create_under_plesk_customer = '
      <div class="'.$plesk_customer_css.'">Select the account under which the page should be installed!
      <select style="min-width:100%;" name="wpwc_s_create_under_plesk_customer">
        <option value="">please choose</option>
        '.$pleskowneroptions.'
      </select>
      </div>';
      }

      if($domainselected !='1' or get_post_meta($post->ID, "wpwc_s_map_maindomain",true) == ''){$domainselected_css = 'wpwcpostalert_error';}else{$domainselected_css = 'wpwcpostalert';}

      if( $domaintype == 'sub' )
      {
      $wpwc_map_servers_info .= $servererror_domain_package.'
      <div class="'.$domainselected_css.'">Select the main domain (TLD) to be used for subdomain creation. The websites will then be installed on a newly created subdomain.
  		<select style="min-width:100%;"  required name="wpwc_s_map_maindomain">
  			<option value ="">'.__( "Please choose", "wp-website-creator" ).'</option>
  			'.$serverdomainoptions.'
      </select>
      </div>
        ';
      }

      $wpwc_map_servers_info .= $packagesselector.$create_plesk_customer.$create_under_plesk_customer;


  }
  }

  if($wpwc_map_servers=='customerservers')
  {
    $thismapping = wpwc_get_form_fields();
    $fields_customer_server_url = $thismapping['customer_server_url'];
    $fields_customer_server_username = $thismapping['customer_server_username'];
    $fields_customer_server_password = $thismapping['customer_server_password'];
    $fields_customer_server_domain = $thismapping['customer_server_domain'];




    $wpwc_this_map_customer_server_url = get_post_meta($post->ID,'wpwc_customer_server_url',true);
    if($wpwc_this_map_customer_server_url==''){$server_url_css='wpwcpostalert_error';}else{$server_url_css='wpwcpostalert';}
    $wpwc_this_map_customer_server_username = get_post_meta($post->ID,'wpwc_customer_server_username',true);
    if($wpwc_this_map_customer_server_username==''){$server_user_css='wpwcpostalert_error';}else{$server_user_css='wpwcpostalert';}
    $wpwc_this_map_customer_server_password = get_post_meta($post->ID,'wpwc_customer_server_password',true);
    if($wpwc_this_map_customer_server_password==''){$server_pass_css='wpwcpostalert_error';}else{$server_pass_css='wpwcpostalert';}
    $wpwc_this_map_customer_server_domain = get_post_meta($post->ID,'wpwc_customer_server_domain',true);
    if($wpwc_this_map_customer_server_domain==''){$server_dom_css='wpwcpostalert_error';}else{$server_dom_css='wpwcpostalert';}

    if($wpwc_this_map_customer_server_url=='' or $wpwc_this_map_customer_server_username=='' or $wpwc_this_map_customer_server_password=='' or $wpwc_this_map_customer_server_domain=='')
    {$customserverinfo_css='wpwcpostalert_error';}else{$customserverinfo_css='wpwcpostalert_info';}

    if($membership == 'Big Plan'){
    $wpwc_map_servers_info='
    <div class="wpwcpostalert_info">
    Websites are installed directly on the customer\'s cPanel.
    </div>
    <div class="'.$customserverinfo_css.'">
    <b>!!Important!!</b><br>Create 4 simple text fields in your form and assign them to the positions listed below.<br><br>These fields are used to create an upstream process that connects to the customer\'s server.<br><br>The fields are automatically moved to the beginning of the form.
    </div>';

    }

    if($membership != 'Big Plan'){
      $wpwc_map_servers_info='
      <div style="text-align:center;">
        <a href="#modal-customercpanel" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">On customer cPanel</button></a><br><br>
      </div>
      <div class="wpwcpostalert_info"><b>!!Important!!</b><br>The installation of websites directly on the cPanel account of a customer is only possible with a Big Plan version.<br>
      <div><br>Websites are installed directly on the customer\'s cPanel. An intermediate step is built in where the customer has to enter the access data of his cPanel.</div>
      <div style="text-align:center;margin-top:10px;"><a class="modalclosebutton" target="_blank" href="https://wp-website-creator.com/">Upgrade your account</a></div>
      </div>';
    }

    if($membership == 'Big Plan'){
    $wpwc_map_customer_server_url='
    <div style="text-align:center;">
      <a href="#modal-customercpanel" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">On customer cPanel</button></a><br><br>
    </div>
    <div class="'.$server_url_css.'">Select a field for cPanel server url
      <select required style="min-width:100%;" name="wpwc_customer_server_url">
      <option value="">Please select</option>
        '.$fields_customer_server_url.'
      </select>
    </div>
    <div class="'.$server_user_css.'">Select a field for cPanel username
      <select required style="min-width:100%;" name="wpwc_customer_server_username">
      <option value="">Please select</option>
        '.$fields_customer_server_username.'
      </select>
    </div>
    <div class="'.$server_pass_css.'">Select a field for cPanel password
      <select required style="min-width:100%;" name="wpwc_customer_server_password">
      <option value="">Please select</option>
        '.$fields_customer_server_password.'
      </select>
    </div>
    <div class="'.$server_dom_css.'">Select a field for the Domain
      <select style="min-width:100%;" required name="wpwc_customer_server_domain">
      <option value="">Please select</option>
        '.$fields_customer_server_domain.'
      </select>
    </div>';
  }
    $serverfields = $wpwc_map_customer_server_url;
  }

  ?>
  <div style="text-align:center;margin-top:20px;">
    <a href="#modal-decide" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">Where to install tutorial</button></a><br><br>
  </div>
  <div class="wpwcpostalert">Decide where to install the web pages created with this form
    <select required  style="min-width:100%;" onchange="this.form.submit()" name="wpwc_s_map_servers">
      <option <?php if($wpwc_map_servers=='wpwcservers'){echo ' selected ';}?> value="wpwcservers">On wpwc servers</option>
      <option <?php if($wpwc_map_servers=='whm'){echo ' selected ';}?> value="whm">My own WHM server</option>
      <option <?php if($wpwc_map_servers=='cpanel'){echo ' selected ';}?> value="cpanel">My own cPanel account</option>
      <option <?php if($wpwc_map_servers=='plesk'){echo ' selected ';}?> value="plesk">My own plesk server</option>
      <option <?php if($wpwc_map_servers=='customerservers'){echo ' selected ';}?> value="customerservers">On customers cPanel</option>
    </select></div>
  <?php
  if ($domaintype == ''){$domaintype_css = 'wpwcpostalert_error';}else{$domaintype_css = 'wpwcpostalert';}

  if($membership == 'Big Plan')
  {
    ?>

    <?php if($wpwc_map_servers != 'customerservers'){?>
    <div style="text-align:center;margin-top:20px;">
      <a href="#modal-decide" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">Choose a domain tutorial</button></a><br><br>
    </div>

    <div class="<?php echo $domaintype_css;?>">Install websites on a Subdomain or on a Top level Domain?
      <select required  style="min-width:100%;" onchange="this.form.submit()" name="wpwc_s_map_domaintype">
        <option value=""><?php echo __( "Please choose", "wp-website-creator" );?></option>
        <option <?php if($domaintype=='sub'){echo ' selected ';}?> value="sub">On a Subdomain</option>
        <option <?php if($domaintype=='tld'){echo ' selected ';}?> value="tld">On a TLD</option>
      </select>
    </div>

    <?php if($domaintype == 'tld'){?>
    <div class="<?php echo $domaintype_css;?>">What domain extensions do you offer? Separated by komma<br><b>Example:</b><br>.com,.co.uk,.sp,.de,.fr
      <input style="min-width:100%;" value="<?php echo $wpwc_s_map_domainextensions;?>" name ="wpwc_s_map_domainextensions">
    </div>
    <?php }//End if TLD?>

    <?php }// End if customer server?>

  <?php
}else{echo '<input value="sub" type="hidden" name="wpwc_s_map_domaintype">';}
  echo $wpwc_map_servers_info.$serverfields;
    }
  }

  ########## end of installation method area
  #########


  ###create the header secton of field selector and echo fieldselectors
  ####
  function wpwc_fields_mapping(){
    global $post;
    $custom = get_post_custom($post->ID);
    $wpwc_map_source = $custom["wpwc_map_source"][0];

    $formplugin_ex = explode('_',$wpwc_map_source);
  	$formplugin = $formplugin_ex[1];
  	$formularid = $formplugin_ex[2];

    $wpwc_s_map_servers = get_post_meta($post->ID,'wpwc_s_map_servers',true);
    $wpwc_use_designid_2 = get_post_meta($post->ID,'wpwc_use_designid',true);

    $wpwc_use_designarea_2 = get_post_meta($post->ID,'wpwc_use_designarea',true);

    $emailokid = get_post_meta($post->ID,'wpwc_required_email',true);
    $designokid = get_post_meta($post->ID,'wpwc_required_design',true);
    $prefixokid = get_post_meta($post->ID,'wpwc_required_prefix',true);

    $check = get_the_title($post->ID);

    if($wpwc_use_designarea_2=='yes' or ($wpwc_use_designarea_2=='no' && $wpwc_use_designid_2 >='1'))
    {

    if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$emailokid,true) == 'email' && (get_reselect_required_field($formplugin,$formularid,'email',$emailokid)>='1'))
    {$emailok = 'ok';}
    if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$designokid,true) ==  'design' && (get_reselect_required_field($formplugin,$formularid,'design',$designokid)>='1'))
    {$designok = 'ok';}
    if(get_post_meta($post->ID,'wpwc_mapfield_'.$formplugin.'_id_'.$formularid.'_fid_'.$prefixokid,true) ==  'prefix' && (get_reselect_required_field($formplugin,$formularid,'prefix',$prefixokid)>='1'))
    {$prefixok = 'ok';}

    if (get_post_meta($post->ID,'wpwc_admin_update_info',true) =='1')
    {echo '<div class="wpwcpostalert_error">'.__( "Themes imported, please save this post", "wp-website-creator" ).' <button class="button button-primary" type="submit">'.__( "Update", "wp-website-creator" ).'</button></div>';}


    if($check!='' && $check!='Auto Draft' && $wpwc_map_source !='')
    {
      if($emailok!='ok'){echo '<div class="wpwcpostalert_error">1. Please create a email field in your form and mapp it with the required <b>EMAIL</b> option</div>';}
      if($designok!='ok'){echo '<div class="wpwcpostalert_error">'.__( "2. Please create a design field (simple text field) in your form and mapp it with the required <b>DESIGN</b> option", "wp-website-creator" ).'</div>';}
      if($wpwc_s_map_servers != 'customerservers')
      {
      if($prefixok!='ok'){echo '<div class="wpwcpostalert_error">'.__( "3. Please create a prefix field (simple text field) in your form and mapp it with the required <b>PREFIX</b> option", "wp-website-creator" ).'</div>';}
      }


  	echo '<div class="wpwcpostalert">'.__( "Please connect the form fields of the selected form with the data necessary for the creation of the website.", "wp-website-creator" ).'</div>';
    }
    if($check=='' or $check == 'Auto Draft')
    {
  	echo '<div class="wpwcpostalert_error">1. Please tip in a title and save this mapping</div>';
    }
    if(($check!='' and $check != 'Auto Draft') && $wpwc_map_source =='')
    {
  	echo '<div class="wpwcpostalert_error">2. Select the form from which a website should be created</div>';
    }

  	$thismapping = wpwc_get_form_fields();
  	$fields = $thismapping['fields'];
  	echo $fields;
    }
    else
    {
      if($check=='' or $check=='Auto Draft')
      {
  	     echo '<div class="wpwcpostalert_error">1. Please tip in a title and save this mapping</div>';
      }
    }
  }


  ##### create selectors for design output columns per row ans imagesize
  ####
  function wpwc_design_area(){

    global $post;
    $custom = get_post_custom($post->ID);
    $wpwc_map_source = $custom["wpwc_map_source"][0];
    $check = get_the_title($post->ID);

    if(($wpwc_map_source != 'Please select a source' && $wpwc_map_source != '') && $check)
    {
  	$formplugin_ex = explode('_',$wpwc_map_source);
  	$formplugin = $formplugin_ex[1];
  	$formularid = $formplugin_ex[2];
    $plugindesignfield = 'wpwc_map_'.$formplugin.'_'.$formularid.'_designfield';
    $selecteddesignfield = get_post_meta($post->ID,$plugindesignfield,true);
    $wpwc_map_demotext = get_post_meta($post->ID,'wpwc_map_demotext',true);
    $wpwc_map_choosetext = get_post_meta($post->ID,'wpwc_map_choosetext',true);
    $wpwc_map_scrollid = get_post_meta($post->ID,'wpwc_map_scrollid',true);
    $wpwcdesignshadow = get_post_meta($post->ID,'wpwcdesignshadow',true);
    $wpwcdesignscroll = get_post_meta($post->ID,'wpwcdesignscroll',true);
    $wpwc_map_imagesize = get_post_meta($post->ID,'wpwc_map_imagesize',true);
    $wpwc_map_categories = get_post_meta($post->ID,'wpwc_map_categories',true);
    $wpwcbuttonclass = get_post_meta($post->ID,'wpwcbuttonclass',true);
    $wpwcchoosebuttonclass = get_post_meta($post->ID,'wpwcchoosebuttonclass',true);
    $wpwc_s_map_userrole_2 = get_post_meta($post->ID,'wpwc_s_map_userrole',true);
    $wpwc_use_designarea_2 = get_post_meta($post->ID,'wpwc_use_designarea',true);


    $wpwc_map_design_per_row = $custom['wpwc_map_design_per_row'][0];

    $thismapping = wpwc_get_form_fields();
    $fields = $thismapping['designs'];

    $themeheadertable = '
    <tr>
    <td style="text-align:center;">
    <span class="wpwcdesigndemobutton"><a href="#modal-designimport" style="color:white;" uk-toggle>Import themes</a></span>
    </td>
    </tr>
    <tr>
    <td>
      <div class="wpwcformsettings">Form template</div>
      <select style="min-width:100%;" name="'.$plugindesignfield.'">
        <option '.wpwc_is_selected($selecteddesignfield,'slide').' value="slide">Fade</option>
        <option '.wpwc_is_selected($selecteddesignfield,'stacked').' value="stacked">Stacked</option>
        <option '.wpwc_is_selected($selecteddesignfield,'reverse').' value="reverse">Stacked reverse</option>
        <option '.wpwc_is_selected($selecteddesignfield,'left').' value="left">Left-Right</option>
        <option '.wpwc_is_selected($selecteddesignfield,'right').' value="right">Right-Left</option>
        <option '.wpwc_is_selected($selecteddesignfield,'modal').' value="modal">Modal</option>
      </select>
      </td>
    </tr>

    <tr>
    <td>
  		<div class="wpwcformsettings">Images per row</div>
  		<select style="min-width:100%;" name="wpwc_map_design_per_row">
  			<option value="">'.__( "Please choose", "wp-website-creator" ).'</option>
        <option '.wpwc_is_selected($wpwc_map_design_per_row,'1').' value="1">1</option>
  			<option '.wpwc_is_selected($wpwc_map_design_per_row,'2').' value="2">2</option>
        <option '.wpwc_is_selected($wpwc_map_design_per_row,'3').' value="3">3</option>
        <option '.wpwc_is_selected($wpwc_map_design_per_row,'4').' value="4">4</option>
        <option '.wpwc_is_selected($wpwc_map_design_per_row,'5').' value="5">5</option>
        <option '.wpwc_is_selected($wpwc_map_design_per_row,'6').' value="6">6</option>
  		</select>
      </td>
    </tr>

    <tr>
    <td>
  		<div class="wpwcformsettings">Image size</div>
  		<select style="min-width:100%;" name="wpwc_map_imagesize">
  			<option '.wpwc_is_selected($wpwc_map_imagesize,'medium').' value="medium">Medium</option>
  			<option '.wpwc_is_selected($wpwc_map_imagesize,'small').' value="small">Small</option>
        <option '.wpwc_is_selected($wpwc_map_imagesize,'large').' value="large">Large</option>
  		</select>
      </td>
    </tr>

    <tr>
    <td>
  		<div class="wpwcformsettings">Show categories</div>
  		<select style="min-width:100%;" name="wpwc_map_categories">
  			<option '.wpwc_is_selected($wpwc_map_categories,'no').' value="no">No</option>
  			<option '.wpwc_is_selected($wpwc_map_categories,'yes').' value="yes">Yes</option>
  		</select>
      </td>
    </tr>

    <tr>
    <td>
      <div class="wpwcformsettings">Buttontext for all demo buttons</div>
    <input style="min-width:100%;" value="'.$wpwc_map_demotext.'" name ="wpwc_map_demotext">
    </td>
    </tr>

    <tr>
    <td>
      <div class="wpwcformsettings">Buttontext for all selection buttons</div>
    <input style="min-width:100%;" value="'.$wpwc_map_choosetext.'" name ="wpwc_map_choosetext">
    </td>
    </tr>

    <tr>
    <td>
  		<div class="wpwcformsettings">Demo button class</div>
      <input style="min-width:100%;" value="'.$wpwcbuttonclass.'" name ="wpwcbuttonclass">
      </td>
    </tr>

    <tr>
    <td>
  		<div class="wpwcformsettings">Selection button class</div>
      <input style="min-width:100%;" value="'.$wpwcchoosebuttonclass.'" name ="wpwcchoosebuttonclass">
      </td>
    </tr>

    <tr>
    <td>
  		<div class="wpwcformsettings">Designbox shadow?</div>
  		<select style="min-width:100%;" name="wpwcdesignshadow">
  			<option value="0">No</option>
        <option '.wpwc_is_selected($wpwcdesignshadow,'1').' value="1">Yes</option>
  		</select>
      </td>
    </tr>

    <tr>
    <td>
  		<div class="wpwcformsettings">Scroll to form start after choosen a design?</div>
  		<select style="min-width:100%;" name="wpwcdesignscroll">
  			<option value="0">No</option>
        <option '.wpwc_is_selected($wpwcdesignscroll,'1').' value="1">Yes</option>
  		</select>
      </td>
    </tr>
    <tr>
    <td>
  		<div class="wpwcformsettings">ID where to scroll to</div>
      <input style="min-width:100%;" value="'.$wpwc_map_scrollid.'" name ="wpwc_map_scrollid">
      </td>
    </tr>
    ';
      $thisdesigns = wpwc_get_selected_themes();
      if($thisdesigns!='')
      {
        $komplettetabelle = '
        <table>
        <tr>
          <td valign="top">
            <div class="wpwcdesignchooserheadline">You can sort the templates with drag and drop and also give them names</div>
            '.$thisdesigns.'
          </td>
          <td valign="top" class="wpwc_designarea_right">
          <div class="wpwcdesignchooserheadline">Designselector settings.</div>
            <table width="100%">'.$themeheadertable.'</table>
          </td>
        </tr>
        </table>
        ';
      }
      else
      {
        if($wpwc_use_designarea_2=='no' or $wpwc_use_designarea_2=='')
        {
        $komplettetabelle = '';
        }
        else
        {
        $komplettetabelle = '
        <table width="100%">
        <tr width="100%">
        <td align="center" width="100%">
        <div class="wpwcpostalert_error">'.__( "Please import the themes you like to offer", "wp-website-creator" ).'</div>
        <span style="text-align:center;"><a href="#modal-designimport" class="wpwcdesigndemobutton wpwcdesigndemobutton_full" style="width:100%;color:white;" uk-toggle>Import themes</a></span>
        </td>
        </tr>
        </table>';
        }
      }
      echo $komplettetabelle;
    }

    else if($wpwc_map_source != 'Please select a source' && $wpwc_map_source != '')
    {
      echo 'Choose a form first';
    }
    else
    {
      echo '';
    }
  }
  #####End
  ########


  #######If woocommerce exists we give option to relate a product
  ##########

  if ( !function_exists( 'WC' ))
  {
    global $post;


    $check = get_the_title($post->ID);
    //Show metabox only on saver posts

      function admin_init_wpwc_woo_info()
      {
        add_meta_box("wpwc-map-woo-info", "Woocommerce", "wpwc_woo_box_info", "wpwc_mappings", "side", "low");
      }
      add_action("admin_init", "admin_init_wpwc_woo_info");


    function wpwc_woo_box_info()
    {
      global $wpdb;
      $memberships = wpwc_get_themes();
      $membership = $memberships['membership'];
      if($membership != 'Big Plan' and $membership != 'Agency'){
      echo '<div style="text-align:center;">
        <a href="#modal-woo" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">Video tutorial</button></a><br><br>
      </div><div class="wpwcpostalert_info">With a Big Plan membership and Woocommerce installed you can link the website creation directly with a Woocommerce product.<div style="text-align:center;margin-top:10px;"><a class="modalclosebutton" target="_blank" href="https://wp-website-creator.com/">Upgrade your account</a></div></div>';
      }
      else{
      echo '<div style="text-align:center;">
        <a href="#modal-woo" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">Video tutorial</button></a><br><br>
      </div><div class="wpwcpostalert_info">Install Woocommerce to link the website creation directly with a Woocommerce product.</div>';
      }
    }

  }


  if ( function_exists( 'WC' ) )
  {
  global $post;

  $check = get_the_title($post->ID);
  //Show metabox only on saver posts

    function admin_init_wpwc_woo()
    {
      global $post;
      $custom = get_post_custom($post->ID);
      $wpwc_map_source = $custom["wpwc_map_source"][0];
      add_meta_box("wpwc-map-woo", "Woocommerce", "wpwc_woo_box", "wpwc_mappings", "side", "low");
    }
    add_action("admin_init", "admin_init_wpwc_woo");


  //select a woocommerce product
  function wpwc_woo_box()
  {
    global $post;
    global $wpdb;
    $memberships = wpwc_get_themes();
    $membership = $memberships['membership'];
    $custom = get_post_custom($post->ID);
    $wpwc_map_source = $custom["wpwc_map_source"][0];
  	$statusesarr = wc_get_order_statuses();
    $wpwc_woo_product = $custom["wpwc_woo_product"][0];
  	$wpwc_map_woo_state = $custom["wpwc_map_woo_state"][0];
    $wpwc_s_map_domainextensions = $custom["wpwc_s_map_domainextensions"][0];
    $domaintype = $custom["wpwc_s_map_domaintype"][0];
    $wpwc_s_map_servers = get_post_meta($post->ID,'wpwc_s_map_servers',true);

    $check = get_the_title($post->ID);
    if(($wpwc_map_source != 'Please select a source' && $wpwc_map_source != '') && $check)
    {
  	foreach ( $statusesarr as $key=>$val )
  	{
      if($key == 'wc-completed' or $key == 'wc-on-hold' or $key == 'wc-processing')
      {
  		if($wpwc_map_woo_state==$key){$stateselected = 'selected';}else {$stateselected='';}
  		$options .= '<option value="'.$key.'" '.$stateselected.'>'.$val.'</option>';
      }
  	};
    if($membership == 'Big Plan' or $membership == 'Agency'){
  	?>
    <div style="text-align:center;margin-top:20px;">
      <a href="#modal-woo" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">Woocommerce tutorial</button></a><br><br>
    </div>
  	<div class="wpwcpostalert">Product ID whitout domain cost
  		<input type="text" style="width:100%;" value="<?php echo $wpwc_woo_product;?>" name="wpwc_woo_product"></div>

      <?php if($wpwc_s_map_domainextensions !='' && $domaintype == 'tld')
      {
        $wpwc_s_map_domainextensions_explo = explode(',',$wpwc_s_map_domainextensions);
        foreach ($wpwc_s_map_domainextensions_explo as $key => $val)
          {
              $domainbeginn = substr($val, 0,1);
              if($domainbeginn != '.'){$val = '.'.$val;}
              $domain_for_meta_key = substr($val, 1);

              $domprice = get_post_meta($post->ID,'d_price_'.$domain_for_meta_key,true);
              echo '<div class="wpwcpostalert"><span style="min-width:100%;" >Product ID for '.$val.' </span>
              <input type="text" style="width:100%;" value="'.$domprice.'" name="d_price_'.$domain_for_meta_key.'"></div>';
          }
      }?>

      <?php if($wpwc_woo_product>'0'){?>
  		   <div class="wpwcpostalert">At which payment status should the page be installed?
  		    <select style="min-width:100%;" required name="wpwc_map_woo_state">
  			     <?php echo $options;?>
  		    </select>
        </div>
      <?php }?>
    <?php }
    else{

      echo '
      <div style="text-align:center;">
        <a href="#modal-woo" style="color:white;" uk-toggle><button class="wpwcdesigndemobutton wpwcdesigndemobutton_full">Video tutorial</button></a><br><br>
      </div>
      <div class="wpwcpostalert_info">With a Big Plan membership you can link the website creation directly with a Woocommerce product.<div style="text-align:center;margin-top:10px;"><a class="modalclosebutton" target="_blank" href="https://wp-website-creator.com/">Upgrade your account</a></div></div>';
    }

  }
  }
  }
  ####End if woocommerce exists
  #######
?>
