<?php
//Woocommerce item

// Save custom data to order item meta data
add_action( 'woocommerce_checkout_create_order_line_item', 'wpwc_order_id_order_item_meta', 20, 4 );
function wpwc_order_id_order_item_meta( $item, $cart_item_key, $values, $order )
{

    $wpwc_order_id = WC()->session->get('_wpwc_website_creation_id');

    if( ! empty($wpwc_order_id) )
    {
        $wpwc_payment_stat = WC()->session->get('_wpwc_create_on_payment_stat');
        $item->update_meta_data( '_wpwc_create_on_payment_stat', $wpwc_payment_stat );

        $item->update_meta_data( '_wpwc_website_creation_id', $wpwc_order_id );
    }
}




//The form will be integrated for cpanel connection
function get_customcpanel_form($formplugin)
{
  if($formplugin = 'wpforms'){$formclass='wpforms-form';$formfieldsclass='wpforms-field';$formbuttonclass='wpforms-submit';}
  if($formplugin = 'formidable'){$formclass='frm_forms';$formfieldsclass='frm_form_fields';$formbuttonclass='frm_button_submit';}
  if($formplugin = 'ninja'){$formclass='nf-form-content';$formfieldsclass='nf-field-container';$formbuttonclass='ninja-forms-field nf-element';}
  if($formplugin = 'caldera'){$formclass='caldera_forms_form';$formfieldsclass='';$formbuttonclass='btn btn-default';}
  return '
<form class="'.$formclass.'" style ="display:none;" id="wpwc_cpanel_domain_form" method="post">
<div class="'.$formfieldsclass.'">
  <div class="" id="domain_select_info">'.__( "We have found the following domains in your account.<br>Please select the domain under which the website should be installed.", "wp-website-creator" ).'</div>
  <div class="" id="domain"></div>
  <div class="" id="wpwarning_wpexists" style="display:none;">'.__( "There is a WordPress installed in this folder.<br>Please remove the WordPress installation and restart the installation process.", "wp-website-creator" ).'</div>
  <div class="" id="wpwc_cpanel_form_warning" style="display:none;">'.__( "Can\'t connect", "wp-website-creator" ).'</div>
  <input type="text" style="display:none;" id="wpwc_cpanel_login_url_2" name="wpwc_cpanel_login_url" value="">
  <input type="text" style="display:none;" id="wpwc_cpanel_login_username_2" name="wpwc_cpanel_login_username" value="">
  <input type="text" style="display:none;" id="wpwc_cpanel_login_password_2" name="wpwc_cpanel_login_password" value="">
  <p>
    <input type="submit" style="display:none;" id="btn_customerservers_dom" value="'.__( "Ok install on this domain", "wp-website-creator" ).'" class="'.$formbuttonclass.'">
  </p>
  <div class="wpwc_cpanel_form_ok" id="wpwc_cpanel_form_ok" style="display:none;">'.__( "Everything looks great. You can install now.", "wp-website-creator" ).'</div>
</div>
</form>';
 }



 add_shortcode('wpwc_secretcode', 'wpwc_secretcode_code');
 function wpwc_secretcode_code($atts)
 {
   $secretcode_code = random_secretkey(28);
   return $secretcode_code;
 }

   add_shortcode('wpwc_form', 'wpwc_designgrid_show');
   function wpwc_designgrid_show($atts)
   {

     if ( !isset($_GET['fl_builder']) && !isset($_GET['action']) ) {
     global $wpdb;
     $secretcode_code = random_secretkey(28);
     $wpwcformid = $atts['id'];

     $idexplo = explode('_',$wpwcformid);
     $formplugin = $idexplo['1'];
     $formularid = $idexplo['2'];


     if($formplugin=="cf7")
       {
       $ac = '[contact-form-7 id="'.$formularid.'"]';
     }
     else if($formplugin=="formidable")
       {
     $ac = '[formidable id='.$formularid.']';
     }
     else if($formplugin=="ninja")
       {
     $ac = '[ninja_form id='.$formularid.']';
     }
     else if($formplugin=="wpforms")
       {
     $ac = '[wpforms id="'.$formularid.'"]';
     }
     else if($formplugin=="caldera")
       {
     $ac = '[caldera_form id="'.$formularid.'"]';
     }
     else if($formplugin=="gravity")
       {
     $ac = '[gravityform id="'.$formularid.'"]';
     }


     $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = '$wpwcformid'", ARRAY_A );
     $thisformid = $results['0']['post_id'];
     $get_wpwc_s_map_servers = get_post_meta($thisformid, "wpwc_s_map_servers",true);

     $plugindesignfield = 'wpwc_map_'.$formplugin.'_'.$formularid.'_designfield';
     $selecteddesignfield = get_post_meta($thisformid,$plugindesignfield,true);

     $designs = get_post_meta($thisformid, "wpwc_map_designgrid",true);
     if($designs!=''){$designs = '<div class="wpwc_design_header_h">'.__( "Please choose a theme first", "wp-website-creator" ).'</div>'.$designs;}
     $domaintype = get_post_meta($thisformid, "wpwc_s_map_domaintype",true);
     $wpwc_use_designarea = get_post_meta($thisformid, "wpwc_use_designarea",true);
     $wpwc_use_designid = get_post_meta($thisformid, "wpwc_use_designid",true);

     $fields_customer_server_url = get_post_meta($thisformid,'wpwc_customer_server_url',true);
     $wpwc_s_map_maindomain = get_post_meta($thisformid,'wpwc_s_map_maindomain',true);

     if($get_wpwc_s_map_servers == 'wpwcservers' && $wpwc_s_map_maindomain != call_wpwcservers_get_maindomain())
     {
       update_post_meta($thisformid,'wpwc_s_map_maindomain',call_wpwcservers_get_maindomain());
       $wpwc_s_map_maindomain = call_wpwcservers_get_maindomain();
     }

     $fields_customer_server_username = get_post_meta($thisformid,'wpwc_customer_server_username',true);
     $fields_customer_server_password = get_post_meta($thisformid,'wpwc_customer_server_password',true);
     $fields_customer_server_domain = get_post_meta($thisformid,'wpwc_customer_server_domain',true);

     $designfield = get_post_meta($thisformid,'wpwc_'.$formplugin.'_'.$formularid.'_design_field',true);
     $prefixfield = get_post_meta($thisformid,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field',true);
     //Only for formidable
     $designfield_id = get_post_meta($thisformid,'wpwc_'.$formplugin.'_'.$formularid.'_design_field_id',true);
     $prefixfield_id = get_post_meta($thisformid,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field_id',true);

     $auto_designid = get_post_meta($thisformid,'wpwc_use_designid',true);

     $prefixfield_id = get_post_meta($thisformid,'wpwc_'.$formplugin.'_'.$formularid.'_prefix_field_id',true);

     $secretcode_field = get_post_meta($thisformid,'wpwc_secretcode',true);


     if($formplugin == 'gravity')
     {
       $fields_secretcode_hidden = "#input_".$formularid."_".$secretcode_field;
       $prefix_hide_field = "#input_".$formularid."_".$prefixfield_id;
       $fields_customer_server_url_hidden = "#input_".$formularid."_".$fields_customer_server_url;
       $fields_customer_server_username_hidden = "#input_".$formularid."_".$fields_customer_server_username;
       $fields_customer_server_password_hidden = "#input_".$formularid."_".$fields_customer_server_password;
       $fields_customer_server_domain_hidden = "#input_".$formularid."_".$fields_customer_server_domain;
     }
     else if($formplugin == 'formidable')
     {
       $prefix_hide_field = '#'.$prefixfield;

       $fields_customer_server_url_request = "SELECT * FROM ".$wpdb->prefix."frm_fields WHERE ".$wpdb->prefix."frm_fields.id = '".$fields_customer_server_url."'";
       $fields_customer_server_url_request_ = $wpdb->get_results( "$fields_customer_server_url_request", OBJECT );
       foreach($fields_customer_server_url_request_ as $key)
       {
         $fields_customer_server_url_hidden = '#field_'.$key->field_key;
       }

       $fields_customer_server_username_request = "SELECT * FROM ".$wpdb->prefix."frm_fields WHERE ".$wpdb->prefix."frm_fields.id = '".$fields_customer_server_username."'";
       $fields_customer_server_username_request_ = $wpdb->get_results( "$fields_customer_server_username_request", OBJECT );
       foreach($fields_customer_server_username_request_ as $key)
       {
         $fields_customer_server_username_hidden = '#field_'.$key->field_key;
       }

       $fields_customer_server_password_request = "SELECT * FROM ".$wpdb->prefix."frm_fields WHERE ".$wpdb->prefix."frm_fields.id = '".$fields_customer_server_password."'";
       $fields_customer_server_password_request_ = $wpdb->get_results( "$fields_customer_server_password_request", OBJECT );
       foreach($fields_customer_server_password_request_ as $key)
       {
         $fields_customer_server_password_hidden = '#field_'.$key->field_key;
       }

       $fields_customer_server_domain_request = "SELECT * FROM ".$wpdb->prefix."frm_fields WHERE ".$wpdb->prefix."frm_fields.id = '".$fields_customer_server_domain."'";
       $fields_customer_server_domain_request_ = $wpdb->get_results( "$fields_customer_server_domain_request", OBJECT );
       foreach($fields_customer_server_domain_request_ as $key)
       {
         $fields_customer_server_domain_hidden = '#field_'.$key->field_key;
       }

       $fields_secretcode_field = "SELECT * FROM ".$wpdb->prefix."frm_fields WHERE ".$wpdb->prefix."frm_fields.id = '".$secretcode_field."'";
       $fields_secretcode_field_ = $wpdb->get_results( "$fields_secretcode_field", OBJECT );
       foreach($fields_secretcode_field_ as $key)
       {
         $fields_secretcode_hidden = '#field_'.$key->field_key;
       }


     }
     else if($formplugin == 'wpforms') {
       $wpformsprefixid_ex = explode('_',$prefixfield);
       $wpformsprefixid = $wpformsprefixid_ex[1];
       $prefix_hide_field = "#$prefixfield";

       $fields_customer_server_url_hidden = "#wpforms-$formularid-field_$fields_customer_server_url";
       $fields_customer_server_username_hidden = "#wpforms-$formularid-field_$fields_customer_server_username";
       $fields_customer_server_password_hidden = "#wpforms-$formularid-field_$fields_customer_server_password";
       $fields_customer_server_domain_hidden = "#wpforms-$formularid-field_$fields_customer_server_domain";
       $fields_secretcode_hidden = "#wpforms-$formularid-field_$secretcode_field";
     }
     else if($formplugin == 'ninja')
     {
       $prefix_hide_field = '#'.$prefixfield;
       $fields_customer_server_url_hidden = '#nf-field-'.$fields_customer_server_url;
       $fields_customer_server_username_hidden = '#nf-field-'.$fields_customer_server_username;
       $fields_customer_server_password_hidden = '#nf-field-'.$fields_customer_server_password;
       $fields_customer_server_domain_hidden = '#nf-field-'.$fields_customer_server_domain;
       $fields_secretcode_hidden = '#nf-field-'.$secretcode_field;
     }
     else if($formplugin == 'caldera'){
       $prefix_hide_field = '#'.$prefixfield.'_';
       $fields_customer_server_url_hidden = '#'.$fields_customer_server_url.'_';
       $fields_customer_server_username_hidden = '#'.$fields_customer_server_username.'_';
       $fields_customer_server_password_hidden = '#'.$fields_customer_server_password.'_';
       $fields_customer_server_domain_hidden = '#'.$fields_customer_server_domain.'_';
       $fields_secretcode_hidden = '#'.$secretcode_field.'_';
     }
     else if($formplugin == 'cf7'){
       $prefix_hide_field = 'input[name="'.$prefixfield.'"]';
       $fields_customer_server_url_hidden = 'input[name="'.$fields_customer_server_url.'"]';
       $fields_customer_server_username_hidden = 'input[name="'.$fields_customer_server_username.'"]';
       $fields_customer_server_password_hidden = 'input[name="'.$fields_customer_server_password.'"]';
       $fields_customer_server_domain_hidden = 'input[name="'.$fields_customer_server_domain.'"]';
       $fields_secretcode_hidden = 'input[name="'.$secretcode_field.'"]';
     }

     if($formplugin == 'caldera')
     {
       $secretcodescript = "
       <script>
       jQuery(document).ready(function($){
       var calderafoid_0 = jQuery('.".$formularid."').attr('data-instance');
       jQuery('".$fields_secretcode_hidden."' + calderafoid_0).val('".$secretcode_code."').trigger( 'change' );
       });
       </script>";
       $querydomaincode = "
       var calderafoid_2 = jQuery('.".$formularid."').attr('data-instance');
       jQuery('".$fields_customer_server_domain_hidden."' + calderafoid_2).val(json.data.cpdomain).trigger( 'change' );
       jQuery('".$fields_customer_server_url_hidden."' + calderafoid_2).val(json.data.url + '###' + json.data.wpexists.folder).trigger( 'change' );";
       $jqueryprefixchange= "
       var calderafoid_3 = jQuery('.".$formularid."').attr('data-instance');
       jQuery('".$prefix_hide_field."' + calderafoid_3).val(json.data.prefix).trigger( 'change' );";
     }
     else if($formplugin == 'ninja')
     {
       $secretcodescript = "
       <script>
       jQuery(window).on('load', function(){
       jQuery('".$fields_secretcode_hidden."').val('".$secretcode_code."').trigger( 'change' );
       });
       </script>";
       $querydomaincode = "
       jQuery('".$fields_customer_server_domain_hidden."').val(json.data.cpdomain).trigger( 'change' );
       jQuery('".$fields_customer_server_url_hidden."').val(json.data.url + '###' + json.data.wpexists.folder).trigger( 'change' );";

       $jqueryprefixchange="
       jQuery('".$prefix_hide_field."').val(json.data.prefix).trigger( 'change' );";
     }
     else
     {
       $secretcodescript = "
       <script>
       jQuery(document).ready(function($){
       jQuery('".$fields_secretcode_hidden."').val('".$secretcode_code."').trigger( 'change' );
       });
       </script>";
       $querydomaincode = "
       jQuery('".$fields_customer_server_domain_hidden."').val(json.data.cpdomain).trigger( 'change' );
       jQuery('".$fields_customer_server_url_hidden."').val(json.data.url + '###' + json.data.wpexists.folder).trigger( 'change' );";

       $jqueryprefixchange="
       jQuery('".$prefix_hide_field."').val(json.data.prefix).trigger( 'change' );";
     }

     if($formplugin == 'caldera')
     {
       $queryurlcode = "
       var calderafoid_4 = jQuery('.".$formularid."').attr('data-instance');
       jQuery('".$fields_customer_server_url_hidden."' + calderafoid_4).val(json.data.url).trigger( 'change' );
       jQuery('".$fields_customer_server_username_hidden."' + calderafoid).val(json.data.username).trigger( 'change' );
       jQuery('".$fields_customer_server_password_hidden."' + calderafoid).val(json.data.password).trigger( 'change' );
       ";
     }
     else
     {
       $queryurlcode = "
       jQuery('".$fields_customer_server_url_hidden."').val(json.data.url).trigger( 'change' );
       jQuery('".$fields_customer_server_username_hidden."').val(json.data.username).trigger( 'change' );
       jQuery('".$fields_customer_server_password_hidden."').val(json.data.password).trigger( 'change' );
       ";
     }


     ///Pre defined design id
     if($wpwc_use_designarea=='no')
     {
     if($formplugin=='caldera')
     {
       $pre_design = "
       <script>
       jQuery(document).ready(function($){
       var calderafoid_10 = jQuery('.".$formularid."').attr('data-instance');
       jQuery('#".$designfield."_' + calderafoid_10).val('".$auto_designid."').trigger( 'change' );
       jQuery( '#wpwc_cpanel_form' ).show('slow' );
       });
       </script>";
     }
     else if($formplugin=='ninja')
     {
       $pre_design = "
       <script>
       jQuery(window).on('load', function(){
       jQuery('#".$designfield."').val('".$auto_designid."').trigger( 'change' );
       jQuery( '#wpwc_cpanel_form' ).show('slow' );
       });
       </script>";;
     }
     else
     {
       $pre_design = "
       <script>
       jQuery(document).ready(function($){
       jQuery('#".$designfield."').val('".$auto_designid."').trigger( 'change' );
       jQuery( '#wpwc_cpanel_form' ).show('slow' );
       });
       </script>";
     }
   }else $pre_design='';

     //End predefined design id

     if($get_wpwc_s_map_servers == 'customerservers')
     {
       if( get_option( 'wpwc_membership',true) == 'Big Plan' )
       {

       $jqueryservercode = "

       <script>
       jQuery(document).ready(function($){

         jQuery('#btn_customerservers').click( function(e){
           e.preventDefault();

           $.post( '".plugin_dir_url( __FILE__ )."wpwc_cpanel_response.php', jQuery('#wpwc_cpanel_form').serialize(), function( json ){

             if( json.success ){
               if(json.data.select!=''){
                 jQuery('div#domain').replaceWith( json.data.select );
                 jQuery( '#wpwc_cpanel_domain_form' ).show( 'slow' );
                 jQuery( '#domainwarning' ).hide( 'slow' );
                 jQuery( '#btn_customerservers' ).hide( 'slow' );
                 jQuery( '#btn_customerservers_dom' ).show( 'slow' );
                 jQuery( '#domain_select_info' ).show( 'slow' );

               }else{
                 jQuery( '#domainwarning' ).show( 'slow' );
               }
             jQuery('#wpwc_cpanel_login_url_2').attr('value', json.data.url);
             jQuery('#wpwc_cpanel_login_username_2').attr('value', json.data.username);
             jQuery('#wpwc_cpanel_login_password_2').attr('value', json.data.password);

             ".$queryurlcode."

             }
             else
               jQuery( '#domainwarning' ).show( 'slow' );

           });
         });

       });
       </script>
       <script>
       jQuery(document).ready(function($){

         jQuery('#btn_customerservers_dom').click( function(e){
           e.preventDefault();

           $.post( '".plugin_dir_url( __FILE__ )."wpwc_cpanel_response_domain.php', jQuery('#wpwc_cpanel_domain_form').serialize(), function( json ){

             if( json.success ){
               if(json.data.wpexists.exists == 'yes')
               {
                 jQuery( '#wpwarning_wpexists' ).show( 'slow' );
                 jQuery( '#btn_customerservers_dom' ).hide( 'slow' );
                 jQuery( '#domain_select_info' ).hide( 'slow' );
               }
               if(json.data.wpexists.exists == 'no')
               {
                 jQuery( '#wpwc_cpanel_form_ok' ).show( 'slow' );
                 jQuery( '#wpwc_cpanel_form_container' ).show( 'slow' );
                 jQuery( '#wpwarning_wpexists' ).hide( 'slow' );
                 jQuery( '#wpwc_form_container' ).show( 'slow' );
                 jQuery( '#btn_customerservers_dom' ).hide( 'slow' );


                 ".$querydomaincode."

               }

             }
             else{
                 jQuery( '#wpwc_cpanel_form_warning' ).show( 'slow' );
             }

           });
         });

       });

       </script>";

       if($formplugin=='wpforms')
       {
         $wpwc_hiddenfields =
         '<style>
         #wpforms-'.$formularid.'-field_'.$fields_secretcode_hidden.'-container{display:none!important;}
         #wpforms-'.$formularid.'-field_'.$fields_customer_server_url.'-container{display:none!important;}
         #wpforms-'.$formularid.'-field_'.$fields_customer_server_username.'-container{display:none!important;}
         #wpforms-'.$formularid.'-field_'.$fields_customer_server_password.'-container{display:none!important;}
         #wpforms-'.$formularid.'-field_'.$fields_customer_server_domain.'-container{display:none!important;}
         #'.$designfield.'-container{display:none!important;}
         #wpwc_form_container{display:none;}
         </style>';
         $formpreprocess =
         $jqueryservercode.'
         <div class="wpforms-container wpforms-container-full">
         <form class="wpforms-form" method="post" enctype="multipart/form-data" style="display:none;" id="wpwc_cpanel_form">
            <div id="wpwc_cpanel_connect" class="wpforms-field wpforms-field-name">
              <label class="wpforms-field-label">Connect to your cPanel<span class="wpforms-required-label">*</span></label>
                <div class="wpforms-field-row wpforms-field-large">
                  <div class="wpforms-field-row-block wpforms-first wpforms-one-third">
                    <input type="text" id="wpwc_cpanel_login_url" class="wpforms-field-name-first wpforms-field-required" name="wpwc_cpanel_login_url" required="">
                    <label for="wpforms-7-field_0" class="wpforms-field-sublabel after ">'.__( "cPanel Login URL", "wp-website-creator" ).'</label>
                  </div>
                  <div class="wpforms-field-row-block wpforms-one-third">
                    <input type="text" id="wpwc_cpanel_login_user_field" class="wpforms-field-name-middle" name="wpwc_cpanel_login_username">
                    <label for="wpforms-7-field_0-middle" class="wpforms-field-sublabel after ">'.__( "cPanel username", "wp-website-creator" ).'</label>
                  </div>
                  <div class="wpforms-field-row-block wpforms-one-third">
                    <input type="text" id="wpwc_cpanel_login_password_field" class="wpforms-field-name-last wpforms-field-required" name="wpwc_cpanel_login_password" required="">
                    <label for="wpforms-7-field_0-last" class="wpforms-field-sublabel after ">'.__( "cPanel password", "wp-website-creator" ).'</label>
                  </div>
                </div>
             </div>
             <div class="wpforms-submit-container">
              <button type="submit" id="btn_customerservers" class="wpforms-submit">'.__( "Connect to your cPanel", "wp-website-creator" ).'</button>
            </div>
            <input type="hidden" name="formplugin" value="wpforms">
          </form>
          '.get_customcpanel_form('wpforms').'
         </div>
         ';
       }

       if($formplugin=='ninja')
       {
         $wpwc_hiddenfields =
         '<style>
         #nf-field-'.$fields_secretcode_hidden.'-container{display:none!important;}
         #nf-field-'.$fields_customer_server_url.'-container{display:none!important;}
         #nf-field-'.$fields_customer_server_username.'-container{display:none!important;}
         #nf-field-'.$fields_customer_server_password.'-container{display:none!important;}
         #nf-field-'.$fields_customer_server_domain.'-container{display:none!important;}
         #'.$designfield.'-container{display:none!important;}
         #wpwc_form_container{display:none;}
         </style>';
       $formpreprocess =
       $jqueryservercode.'<form style="display:none;" id="wpwc_cpanel_form">
		     <div>
			      <div class="nf-form-content">
            <div id="wpwc_cpanel" class="nf-field-container textbox-container  label-above ">

             <div class="nf-field">
              <div id="wpwc_cpanel_login_url" class="field-wrap textbox-wrap" data-field-id="1">
		              <div class="nf-field-label">
                    <label for="nf-field-1" id="nf-label-field-1" class="">'.__( "cPanel login URL", "wp-website-creator" ).' <span class="ninja-forms-req-symbol">*</span> </label>
                  </div>
		              <div class="nf-field-element">
	                 <input type="text" value="" class="ninja-forms-field nf-element" id="wpwc_cpanel_login_url_field" name="wpwc_cpanel_login_url" aria-invalid="true" aria-describedby="nf-error-1" aria-labelledby="nf-label-field-1" required="">
                  </div>
              </div>
             </div><!--End Field-->

             <div class="nf-field">
              <div id="wpwc_cpanel_login_username" class="field-wrap textbox-wrap" data-field-id="1">
		              <div class="nf-field-label">
                    <label for="nf-field-1" id="nf-label-field-1" class="">'.__( "cPanel username", "wp-website-creator" ).' <span class="ninja-forms-req-symbol">*</span> </label>
                  </div>
		              <div class="nf-field-element">
	                 <input type="text" value="" class="ninja-forms-field nf-element" id="wpwc_cpanel_login_user_field" name="wpwc_cpanel_login_username" aria-invalid="true" aria-describedby="nf-error-1" aria-labelledby="nf-label-field-1" required="">
                  </div>
              </div>
             </div><!--End Field-->

             <div class="nf-field">
              <div id="wpwc_cpanel_login_password" class="field-wrap textbox-wrap" data-field-id="1">
		              <div class="nf-field-label">
                    <label for="nf-field-1" id="nf-label-field-1" class="">'.__( "cPanel password", "wp-website-creator" ).' <span class="ninja-forms-req-symbol">*</span> </label>
                  </div>
		              <div class="nf-field-element">
	                 <input type="text" value="" class="ninja-forms-field nf-element" id="wpwc_cpanel_login_password_field" name="wpwc_cpanel_login_password" aria-invalid="true" aria-describedby="nf-error-1" aria-labelledby="nf-label-field-1" required="">
                  </div>
              </div>
             </div><!--End Field-->

             <div class="nf-field">
                <div style="margin-top:10px;" class="field-wrap submit-wrap textbox-wrap">
                  <div class="nf-field-element">
	                  <input class="ninja-forms-field nf-element " type="button" id="btn_customerservers" value="'.__( "Connect to your cPanel", "wp-website-creator" ).'">
                  </div>
                </div>
              </div>

             </div>
             </div>
             </div>
             <input type="hidden" name="formplugin" value="ninja">
	</form>
  '.get_customcpanel_form('ninja').'

';
        }

        if($formplugin == 'formidable')
        {
          $wpwc_hiddenfields =
          $jqueryservercode.'<style>
          #frm_field_'.$fields_secretcode_hidden.'_container{display:none!important;}
          #frm_field_'.$fields_customer_server_url.'_container{display:none!important;}
          #frm_field_'.$fields_customer_server_username.'_container{display:none!important;}
          #frm_field_'.$fields_customer_server_password.'_container{display:none!important;}
          #frm_field_'.$fields_customer_server_domain.'_container{display:none!important;}
          #frm_field_'.$designfield_id.'_container{display:none!important;}
          #wpwc_form_container{display:none;}

          </style>';
          $formpreprocess='
          <div class="frm_forms  with_frm_style frm_style_formidable-style" id="wpwc_cpanel_container" >
            <form enctype="multipart/form-data" method="post" class="frm-show-form " style="display:none;" id="wpwc_cpanel_form"  >
              <div class="frm_form_fields ">
                <fieldset>

                <div class="frm_fields_container">

                  <div id="wpwc_cpanel_login_url" class="frm_form_field form-field  frm_required_field frm_top_container frm_third frm_first">
                    <label for="wpwc_cpanel_login_url" id="wpwc_cpanel_login_url_label" class="frm_primary_label">'.__( "cPanel login URL", "wp-website-creator" ).'
                      <span class="frm_required">*</span>
                    </label>
                    <input type="text" id="wpwc_cpanel_login_url_field" name="wpwc_cpanel_login_url" value="">
                  </div>

                  <div id="wpwc_cpanel_login_username" class="frm_form_field form-field  frm_required_field frm_top_container frm_third">
                    <label for="wpwc_cpanel_login_user_label" id="wpwc_cpanel_login_user_label" class="frm_primary_label">'.__( "cPanel login username", "wp-website-creator" ).'
                      <span class="frm_required">*</span>
                    </label>
                    <input type="text" id="wpwc_cpanel_login_user_field" name="wpwc_cpanel_login_username" value="">
                  </div>

                  <div id="wpwc_cpanel_login_password" class="frm_form_field form-field  frm_required_field frm_top_container frm_third frm_alignright">
                    <label for="wpwc_cpanel_login_password_label" id="wpwc_cpanel_login_password_label" class="frm_primary_label">'.__( "cPanel login password", "wp-website-creator" ).'
                      <span class="frm_required">*</span>
                    </label>
                    <input type="email" id="wpwc_cpanel_login_password_field" name="wpwc_cpanel_login_password" value="" >
                  </div>

                  <input type="hidden" name="item_key" value="">
	                 <div class="frm_submit">
                    <button class="frm_button_submit" id="btn_customerservers">'.__( "Connect to your cPanel", "wp-website-creator" ).'</button>
                   </div>
              </div>
              </fieldset>
            </div>
            <input type="hidden" name="formplugin" value="formidable">
       </form>
       '.get_customcpanel_form('formidable').'
     </div>
';
        }


        if($formplugin=='gravity')
        {
          $wpwc_hiddenfields =
          '<style>
          #field_'.$formularid.'_'.$fields_secretcode_hidden.'{display:none!important;}
          #field_'.$formularid.'_'.$fields_customer_server_url.'{display:none!important;}
          #field_'.$formularid.'_'.$fields_customer_server_username.'{display:none!important;}
          #field_'.$formularid.'_'.$fields_customer_server_password.'{display:none!important;}
          #field_'.$formularid.'_'.$fields_customer_server_domain.'{display:none!important;}
          #field_'.$formularid.'_'.$designfield.'{display:none!important;}
          #wpwc_form_container{display:none;}
          </style>';
        $formpreprocess =
        $jqueryservercode.'
        <div class="gf_browser_chrome gform_wrapper" id="wpwc_cpanel_form_container">
        <form id="wpwc_cpanel_form" method="POST" style="display:none;" enctype="multipart/form-data">

        <div class="gform_body">
          <ul id="gform_fields_1" class="gform_fields left_label form_sublabel_below description_below">

            <li id="wpwc_cpanel_login_url_wrap" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
              <label class="gfield_label" for="wpwc_cpanel_login_url_label">'.__( "cPanel login URL", "wp-website-creator" ).'</label>
                <div class="ginput_container ginput_container_text">
                  <input name="wpwc_cpanel_login_url" id="wpwc_cpanel_login_url_field" type="text" value="" class="medium" tabindex="1" aria-invalid="false">
                </div>
            </li>

            <li id="wpwc_cpanel_login_username_wrap" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
              <label class="gfield_label" for="wpwc_cpanel_login_username_label">'.__( "cPanel login username", "wp-website-creator" ).'</label>
                <div class="ginput_container ginput_container_text">
                  <input name="wpwc_cpanel_login_username" id="wpwc_cpanel_login_username_field" type="text" value="" class="medium" tabindex="1" aria-invalid="false">
                </div>
            </li>

            <li id="wpwc_cpanel_login_password_wrap" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
              <label class="gfield_label" for="wpwc_cpanel_login_password_label">'.__( "cPanel login password", "wp-website-creator" ).'</label>
                <div class="ginput_container ginput_container_text">
                  <input name="wpwc_cpanel_login_password" id="wpwc_cpanel_login_password_field" type="text" value="" class="medium" tabindex="1" aria-invalid="false">
                </div>
            </li>

            <input id="btn_customerservers" class="gform_button button" value="'.__( "Submit", "wp-website-creator" ).'">
          </ul>
        </div>
        <input type="hidden" name="formplugin" value="gravity">
 	      </form>
        '.get_customcpanel_form('gravity').'
        </div>';
         }



        if($formplugin == 'caldera')
        {
          $wpwc_hiddenfields =
          '<style>
          #'.$fields_secretcode_hidden.'_1-wrap{display:none!important;}
          #'.$fields_customer_server_url.'_1-wrap{display:none!important;}
          #'.$fields_customer_server_username.'_1-wrap{display:none!important;}
          #'.$fields_customer_server_password.'_1-wrap{display:none!important;}
          #'.$fields_customer_server_domain.'_1-wrap{display:none!important;}
          #'.$designfield.'_1-wrap{display:none!important;}

          #'.$fields_secretcode_hidden.'_2-wrap{display:none!important;}
          #'.$fields_customer_server_url.'_2-wrap{display:none!important;}
          #'.$fields_customer_server_username.'_2-wrap{display:none!important;}
          #'.$fields_customer_server_password.'_2-wrap{display:none!important;}
          #'.$fields_customer_server_domain.'_2-wrap{display:none!important;}
          #'.$designfield.'_2-wrap{display:none!important;}

          #'.$fields_secretcode_hidden.'_3-wrap{display:none!important;}
          #'.$fields_customer_server_url.'_3-wrap{display:none!important;}
          #'.$fields_customer_server_username.'_3-wrap{display:none!important;}
          #'.$fields_customer_server_password.'_3-wrap{display:none!important;}
          #'.$fields_customer_server_domain.'_3-wrap{display:none!important;}
          #'.$designfield.'_3-wrap{display:none!important;}
          #wpwc_form_container{display:none;}
          </style>';
          $formpreprocess = $jqueryservercode.'
          <div class="caldera-grid" id="wpwc_cpanel_form_container">
            <form class="caldera_forms_form" method="POST" enctype="multipart/form-data" style="display:none;" id="wpwc_cpanel_form">
            <div class="row first_row">

            <div class="col-sm-4  first_col">
            <div class="form-group" id="wpwc_cpanel_login_url_wrap">
            	 <label id="wpwc_cpanel_login_url_label" class="control-label">'.__( "cPanel login URL", "wp-website-creator" ).' <span aria-hidden="true" role="presentation" class="field_required" style="color:#ee0000;">*</span></label>
            	  <div class="">
            		  <input required="" type="text" class="form-control" id="wpwc_cpanel_login_url_field" name="wpwc_cpanel_login_url" value="" data-type="text">
              </div>
            </div>
            </div>

            <div class="col-sm-4">
            <div class="form-group" id="wpwc_cpanel_login_username_wrap">
            	 <label id="wpwc_cpanel_login_username_label" class="control-label">'.__( "cPanel login URL", "wp-website-creator" ).' <span aria-hidden="true" role="presentation" class="field_required" style="color:#ee0000;">*</span></label>
            	  <div class="">
            		  <input required="" type="text" class="form-control" id="wpwc_cpanel_login_username_field" name="wpwc_cpanel_login_username" value="" data-type="text">
              </div>
            </div>
            </div>

            <div class="col-sm-4 last_col">
            <div class="form-group" id="wpwc_cpanel_login_password_wrap">
            	 <label id="wpwc_cpanel_login_password_label" class="control-label">'.__( "cPanel login URL", "wp-website-creator" ).' <span aria-hidden="true" role="presentation" class="field_required" style="color:#ee0000;">*</span></label>
            	  <div class="">
            		  <input required="" type="text" class="form-control" id="wpwc_cpanel_login_password_field" name="wpwc_cpanel_login_password" value="" data-type="text">
              </div>
            </div>
            </div>

            </div><!-- row ends -->
            <div class="row  last_row">
            <div class="col-sm-12 single">
              <div class="form-group" id="wpwc_cpanel_login_submit_wrap">
                <div class="">
            	     <input class="btn btn-default" name="wpwc_cpanel_login_submit" id="btn_customerservers" value="'.__( "Connect to your cPanel", "wp-website-creator" ).'">
                </div>
              </div>
            </div>
            </div>
            <input type="hidden" name="formplugin" value="caldera">

            </form>
            '.get_customcpanel_form('caldera').'
            </div>';
        }
        if($formplugin == 'cf7')
        {

          $wpwc_woo_product = get_post_meta($thisformid,'wpwc_woo_product',true);

          if($wpwc_woo_product>'1')
          {
          $redirectionscript =
          "
          <script>
          document.addEventListener( 'wpcf7mailsent', function( event ) {
          location = wc_get_cart_url();
          }, false );
          </script>";
          }


        $wpwc_hiddenfields=
        '<script>
        jQuery(".'.$fields_secretcode_hidden.'").parents("p").css("display", "none");
        jQuery(".'.$fields_customer_server_url.'").parents("p").css("display", "none");
        jQuery(".'.$fields_customer_server_password.'").parents("p").css("display", "none");
        jQuery(".'.$fields_customer_server_domain.'").parents("p").css("display", "none");
        jQuery(".'.$fields_customer_server_username.'").parents("p").css("display", "none");
        jQuery(".'.$designfield.'").parents("p").css("display", "none");
        </script>
        <style>
        #wpwc_form_container{display:none;}
        </style>';
        $formpreprocess = $jqueryservercode.'
        <div role="form" class="wpcf7" id="wpwc_cpanel_form_container" dir="ltr">
          <form style="display:none;" id="wpwc_cpanel_form" method="post" class="wpcf7-form">
            <p><label> cPanel login URL (required)<br>
            <span class="wpcf7-form-control-wrap">
            <input type="text" name="wpwc_cpanel_login_url" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false"></span> </label></p>
            <p><label>'.__( "cPanel login username (required)", "wp-website-creator" ).'<br>
            <span class="wpcf7-form-control-wrap">
            <input type="email" name="wpwc_cpanel_login_username" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false"></span> </label></p>
            <p><label>'.__( "cPanel login password", "wp-website-creator" ).'<br>
            <span class="wpcf7-form-control-wrap">
            <input type="text" name="wpwc_cpanel_login_password" value="" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false"></span> </label></p>
            <div class=""  style="display:none;" id="domainwarning">'.__( "Can\'t connect", "wp-website-creator" ).'</div>
            <p>
              <input type="button" id="btn_customerservers" value="'.__( "Connect to your cPanel", "wp-website-creator" ).'" class="wpcf7-form-control wpcf7-submit">
            </p>
            <input type="hidden" name="formplugin" value="cf7">
          </form>
          <form style ="display:none;" id="wpwc_cpanel_domain_form" method="post" class="wpcf7-form">
            <div class="" id="domain_select_info"><h2>'.__( "We have found the following domains in your account. Please select the domain under which the website should be installed.", "wp-website-creator" ).'</h2></div>
            <div class="" id="domain"></div>
            <div class="" id="wpwarning_wpexists" style="display:none;">'.__( "There is a WordPress installed in this folder.<br>Please remove the WordPress installation and restart the installation process.", "wp-website-creator" ).'</div>
            <div class="" id="wpwc_cpanel_form_warning" style="display:none;">'.__( "Can\'t connect", "wp-website-creator" ).'</div>
            <input type="text" style="display:none;" id="wpwc_cpanel_login_url_2" name="wpwc_cpanel_login_url" value="">
            <input type="text" style="display:none;" id="wpwc_cpanel_login_username_2" name="wpwc_cpanel_login_username" value="">
            <input type="text" style="display:none;" id="wpwc_cpanel_login_password_2" name="wpwc_cpanel_login_password" value="">
            <p>
              <input type="button" style="display:none;" id="btn_customerservers_dom" value="'.__( "Ok install on this domain", "wp-website-creator" ).'" class="wpcf7-form-control wpcf7-submit">
            </p>
          </form>
          <div class="wpwc_cpanel_form_ok" id="wpwc_cpanel_form_ok" style="display:none;"><h2>'.__( "Everything looks great. You can install now.", "wp-website-creator" ).'</h2></div>
        </div>'.$redirectionscript;
     }
   }//End only if big plan
   else
   {
     $formpreprocess = '<div style="color:red;">'.__( "You need a Big Plan license to install websites directly on customers cPanel", "wp-website-creator" ).'</div>';
   }

 }//End if customerserver
   else if($get_wpwc_s_map_servers == 'cpanel' or $get_wpwc_s_map_servers == 'whm' or $get_wpwc_s_map_servers == 'plesk' or $get_wpwc_s_map_servers == 'wpwcservers'){

        if($domaintype != 'tld')
        {
        $checkdomaintext = __( "Check subdomain availability", "wp-website-creator" );
        $jqueryservercode = "

        <script>

        jQuery(document).ready(function($){

          jQuery('#btn_domaincheck').click( function(e){
            e.preventDefault();

            $.post( '".plugin_dir_url( __FILE__ )."wpwc_cpanel_call_domainexist.php', jQuery('#wpwc_cpanel_form').serialize(), function( json ){

              if( json.success ){
                if(json.data.domainexists=='1'){
                  jQuery( '#wpwc_cpanel_domain_exists' ).show( 'slow' );
                  jQuery( '#wpwc_cpanel_domain_ok' ).hide( 'slow' );
                  jQuery( '#wpwc_form_valid' ).hide( 'slow' );
                }else{
                  if(json.data.val =='1')
                  {
                  jQuery( '#wpwc_cpanel_domain_ok' ).show( 'slow' );
                  jQuery( '#wpwc_form_container' ).show( 'slow' );
                  jQuery( '#wpwc_cpanel_domain_exists' ).hide( 'slow' );
                  jQuery( '#btn_domaincheck' ).hide( 'slow' );


                  ".$jqueryprefixchange."


                  jQuery( '#wpwc_form_valid' ).hide( 'slow' );
                  jQuery( '#wpwc_form_valid_test' ).hide( 'slow' );
                  }
                  else if(json.data.val =='2'){
                    jQuery( '#wpwc_form_valid_test' ).show( 'slow' );
                  }
                  else if(json.data.val =='3'){
                    jQuery( '#wpwc_form_valid' ).show( 'slow' );
                  }
                }

              }
              else
                jQuery( '#wpwc_connectwarning' ).show( 'slow' );

            });
          });

        });
        </script>";
        }
        if($domaintype == 'tld')
        {
        $checkdomaintext = __( "Check domain availability", "wp-website-creator" );
        $jqueryservercode = "

        <script>

        jQuery(document).ready(function($){

          jQuery('#btn_domaincheck').click( function(e){
            e.preventDefault();

            $.post( '".plugin_dir_url( __FILE__ )."wpwc_tld_call_domainexist.php', jQuery('#wpwc_cpanel_form').serialize(), function( json ){

              if( json.success ){
                if(json.data.domainexists=='1'){
                  jQuery( '#wpwc_cpanel_domain_exists' ).show( 'slow' );
                  jQuery( '#wpwc_cpanel_domain_ok' ).hide( 'slow' );
                  jQuery( '#wpwc_form_valid' ).hide( 'slow' );
                  ".$jqueryprefixchange."
                }else{
                  if(json.data.val =='1')
                  {
                  jQuery( '#wpwc_cpanel_domain_ok' ).show( 'slow' );
                  jQuery( '#wpwc_form_container' ).show( 'slow' );
                  jQuery( '#wpwc_cpanel_domain_exists' ).hide( 'slow' );
                  jQuery( '#btn_domaincheck' ).hide( 'slow' );

                  ".$jqueryprefixchange."

                  jQuery( '#wpwc_form_valid' ).hide( 'slow' );
                  jQuery( '#wpwc_form_valid_test' ).hide( 'slow' );
                  }
                  else if(json.data.val =='2'){
                    jQuery( '#wpwc_form_valid_test' ).show( 'slow' );
                  }
                  else if(json.data.val =='3'){
                    jQuery( '#wpwc_form_valid' ).show( 'slow' );
                  }
                }

              }
              else
                jQuery( '#wpwc_connectwarning' ).show( 'slow' );

            });
          });
          jQuery('#wpwc_my_domain').on('change', function(){
            jQuery( '#wpwc_cpanel_domain_ok' ).show( 'slow' );
            jQuery( '#wpwc_form_container' ).show( 'slow' );
            jQuery( '#wpwc_cpanel_domain_exists' ).hide( 'slow' );
            jQuery( '#btn_domaincheck' ).hide( 'slow' );
            jQuery( '#wpwc_form_valid' ).hide( 'slow' );
            jQuery( '#wpwc_form_valid_test' ).hide( 'slow' );
          });
        });


        </script>
        ";
        }



       if($formplugin=='wpforms')
       {
         $formpreprocess =$jqueryservercode.
         '
         <div class="wpforms-container wpforms-container-full">
         <form class="wpforms-form" method="post" enctype="multipart/form-data" style="display:none;" id="wpwc_cpanel_form">
             <div class="wpforms-field wpforms-field-name">
              '.get_wpwc_domainextension_field($domaintype,$wpwc_s_map_maindomain,$formplugin,$thisformid).'
             </div>
             <div class="wpforms-submit-container">
                <button type="submit"  class="wpforms-submit " id="btn_domaincheck" value="Connect to your cPanel" aria-live="assertive">'.$checkdomaintext.'</button>
             </div>
              '.domain_validate_fields($domaintype,$wpwc_s_map_maindomain,$thisformid,$formplugin).'
            <input type="hidden" value="'.$get_wpwc_s_map_servers.'" name="jquery_wpwc_s_map_servers">
          </form>
         </div>';

         $wpwc_hiddenfields =
         '<style>
         #'.$designfield.'-container{display:none!important;}
         #'.$prefixfield.'-container{display:none!important;}
         '.$fields_secretcode_hidden.'-container{display:none!important;}
         #wpwc_form_container{display:none;}
         </style>';
       }

       if($formplugin=='ninja')
       {
         $formpreprocess =$jqueryservercode.
         '<form style="display:none;" id="wpwc_cpanel_form">
  		     <div>
  			      <div class="nf-form-content">
              <nf-fields-wrap><nf-field>

              <div id="wpwc_cpanel" class="nf-field-container textbox-container  label-above ">
                '.get_wpwc_domainextension_field($domaintype,$wpwc_s_map_maindomain,$formplugin,$thisformid).domain_validate_fields($domaintype,$wpwc_s_map_maindomain,$thisformid,$formplugin).'
               <input type="hidden" value="'.$get_wpwc_s_map_servers.'" name="jquery_wpwc_s_map_servers">
               <div class="nf-field">
                  <div style="margin-top:10px;" class="field-wrap submit-wrap textbox-wrap">
                    <div class="nf-field-element">
  	                  <input class="ninja-forms-field nf-element " type="button" id="btn_domaincheck" value="'.$checkdomaintext.'">
                    </div>
                  </div>
                </div>

               </div>
               </div>
               </div>
  	       </form>';

         $wpwc_hiddenfields =
         '<style>
         #'.$designfield.'-container{display:none!important;}
         #'.$prefixfield.'-container{display:none!important;}
         '.$fields_secretcode_hidden.'-container{display:none!important;}
         #wpwc_form_container{display:none;}
         </style>';
        }

        if($formplugin == 'formidable')
        {

          $formpreprocess=$jqueryservercode.'
          <div class="frm_forms with_frm_style frm_style_formidable-style">
            <form enctype="multipart/form-data" method="post" class="frm-show-form" style="display:none;" id="wpwc_cpanel_form"  >
              <div class="frm_form_fields ">
              <fieldset>
                <div class="frm_fields_container">
                  '.get_wpwc_domainextension_field($domaintype,$wpwc_s_map_maindomain,$formplugin,$thisformid).domain_validate_fields($domaintype,$wpwc_s_map_maindomain,$thisformid,$formplugin).'
                  <input type="hidden" value="'.$get_wpwc_s_map_servers.'" name="jquery_wpwc_s_map_servers">
	                 <div class="frm_submit">
                    <button class="frm_button_submit" id="btn_domaincheck">'.$checkdomaintext.'</button>
                   </div>
                </div>
              </fieldset>
              </div>
            </form>
          </div>';

          $wpwc_hiddenfields =
          '<style>
          #frm_field_'.$designfield_id.'_container{display:none!important;}
          #frm_field_'.$prefixfield_id.'_container{display:none!important;}
          #frm_field_'.$secretcode_field.'_container{display:none!important;}
          #wpwc_form_container{display:none;}
          </style>';
        }

        if($formplugin == 'gravity')
        {
          $formpreprocess = $jqueryservercode.'
          <div class="gf_browser_chrome gform_wrapper">
          <form style="display:none;" id="wpwc_cpanel_form" method="POST" enctype="multipart/form-data">

          <div class="gform_body">
            <ul class="gform_fields left_label form_sublabel_below description_below">
              '.get_wpwc_domainextension_field($domaintype,$wpwc_s_map_maindomain,$formplugin,$thisformid).domain_validate_fields($domaintype,$wpwc_s_map_maindomain,$thisformid,$formplugin).'
              <input type="hidden" value="'.$get_wpwc_s_map_servers.'" name="jquery_wpwc_s_map_servers">
              <input type ="button" id="btn_domaincheck" class="gform_button button" value="'.$checkdomaintext.'">
            </ul>
          </div>

   	      </form>
          </div>
          ';
          $wpwc_hiddenfields =
          '<style>
          #field_'.$formularid.'_'.$designfield_id.'{display:none!important;}
          #field_'.$formularid.'_'.$prefixfield_id.'{display:none!important;}
          #field_'.$formularid.'_'.$secretcode_field.'{display:none!important;}
          #wpwc_form_container{display:none;}
          </style>';
        }

        if($formplugin == 'caldera')
        {
          $formpreprocess = $jqueryservercode.'
          <div class="caldera-grid">
            <form class="caldera_forms_form" method="POST" enctype="multipart/form-data" style="display:none;" id="wpwc_cpanel_form">
            <div class="row">
            <div class="col-sm-12 single">
              '.get_wpwc_domainextension_field($domaintype,$wpwc_s_map_maindomain,$formplugin,$thisformid).domain_validate_fields($domaintype,$wpwc_s_map_maindomain,$thisformid,$formplugin).'
              <input type="hidden" value="'.$get_wpwc_s_map_servers.'" name="jquery_wpwc_s_map_servers">
            </div>
            </div><!-- row ends -->
            <div class="row  last_row">
            <div class="col-sm-12 single">
              <div class="form-group">
                <div><br>
            	     <input class="btn btn-default" id="btn_domaincheck" value="'.$checkdomaintext.'">
                </div>
              </div>
            </div>
            </div>
            </form>
            </div>
            ';
          $wpwc_hiddenfields =
          '<style>
          #'.$designfield.'_1-wrap{display:none!important;}
          #'.$prefixfield.'_1-wrap{display:none!important;}
          #'.$secretcode_field.'_1-wrap{display:none!important;}
          #'.$designfield.'_2-wrap{display:none!important;}
          #'.$secretcode_field.'_2-wrap{display:none!important;}
          #'.$prefixfield.'_2-wrap{display:none!important;}
          #'.$designfield.'_3-wrap{display:none!important;}
          #'.$prefixfield.'_3-wrap{display:none!important;}
          #'.$secretcode_field.'_3-wrap{display:none!important;}
          #wpwc_form_container{display:none;}
          </style>';
        }

        if($formplugin == 'cf7')
        {
          $wpwc_woo_product = get_post_meta($thisformid,'wpwc_woo_product',true);

          if($wpwc_woo_product>'1')
          {
          $redirectionscript =
          "
          <script>
          document.addEventListener( 'wpcf7mailsent', function( event ) {
          location = wc_get_cart_url();
          }, false );
          </script>";
          }

          $formpreprocess = $jqueryservercode.'
          <form style="display:none;" id="wpwc_cpanel_form" method="post" class="wpcf7-form">
            '.get_wpwc_domainextension_field($domaintype,$wpwc_s_map_maindomain,$formplugin,$thisformid).domain_validate_fields($domaintype,$wpwc_s_map_maindomain,$thisformid,$formplugin).'
            <input type="hidden" value="'.$get_wpwc_s_map_servers.'" name="jquery_wpwc_s_map_servers">
            <p>
              <input type="button" id="btn_domaincheck" value="'.$checkdomaintext.'" class="wpcf7-form-control wpcf7-submit">
            </p>
          </form>'.$redirectionscript;

        $wpwc_hiddenfields=
        '<style>
        #wpwc_form_container{display:none;}
        </style>
        <script>
        jQuery(".'.$designfield.'").parents("p").css("display", "none");
        jQuery(".'.$prefixfield.'").parents("p").css("display", "none");
        </script>';
     }

   }

    if($wpwc_use_designarea=='yes' or !$wpwc_use_designarea)
    {
     if($selecteddesignfield == 'stacked')
     {
       return $secretcodescript.$wpwc_hiddenfields.$designs.$formpreprocess.'<br><div id="wpwc_form_container">'.do_shortcode($ac).'</div>';
     }
     else if($selecteddesignfield == 'reverse')
     {
       return $secretcodescript.$wpwc_hiddenfields.$formpreprocess.'<div id="wpwc_form_container">'.do_shortcode($ac).'</div><br>'.$designs;
     }
     else if($selecteddesignfield == 'layers')
     {
       return $secretcodescript.$wpwc_hiddenfields.'<div style="display:block;">'.$designs.'</div>'.$formpreprocess.'<div id="wpwc_form_container" style="display:none;">'.do_shortcode($ac).'</div>';
     }
     else if($selecteddesignfield == 'left')
     {
       return $secretcodescript.$wpwc_hiddenfields.'<div class="wpwcdesignarea">'.$designs.'</div>'.$formpreprocess.'<div id="wpwc_form_container" class="wpwcdesignarea">'.do_shortcode($ac).'</div>';
     }
     else if($selecteddesignfield == 'right')
     {
       return $secretcodescript.$wpwc_hiddenfields.$formpreprocess.'<div id="wpwc_form_container" class="wpwcformarea">'.do_shortcode($ac).'</div><div class="wpwcdesignarea">'.$designs.'</div>';
     }
     else if($selecteddesignfield == 'modal')
     {
       return $secretcodescript.$wpwc_hiddenfields.'<div>'.$designs.'</div>
                <!-- This is the modal -->
                <div id="wpwcdesignchooser" uk-modal>
                  <div class="uk-modal-dialog uk-modal-body">
                    <h2 class="uk-modal-title"></h2>
                    '.$formpreprocess.do_shortcode($ac).'
                  </div>
                </div>
              ';
     }
     else if($selecteddesignfield == 'slide')
     {
      $wpwcchoosebuttonclass_css = get_post_meta($thisformid, "wpwcchoosebuttonclass",true);

     	if($wpwcchoosebuttonclass_css>'0')
     	{
     		$wpwcchoosebuttonclass_css = $wpwcchoosebuttonclass_css;
     	}else {$wpwcchoosebuttonclass_css='wpwcdesignchoosebutton';}


        $formcontent = '
        <div class="toggle-animation-queued uk-card-body uk-margin-small" hidden>
        <div class="wpwc_step2_h">'.__( "Please enter your domain prefix", "wp-website-creator" ).'</div>
          <button class="'.$wpwcchoosebuttonclass_css.' button100" uk-toggle="target: .toggle-animation-queued; animation: uk-animation-fade; queued: true; duration: 300">'.__( "back to themes", "wp-website-creator" ).'</button>
          '.$formpreprocess.'<div id="wpwc_form_container">'.do_shortcode($ac).'
        </div>';
      return '<div class="toggle-animation-queued uk-card-body uk-margin-small">'.$designs.'</div>'.$secretcodescript.$formcontent.$wpwc_hiddenfields;
    }//ende if slide
    }//Ende if desig show
    //If design area dont show
    else{
      return '<div class="wpwc_step2_h">'.__( "Please enter your domain prefix", "wp-website-creator" ).'</div>'.$pre_design.$secretcodescript.$wpwc_hiddenfields.$formpreprocess.'<div id="wpwc_form_container">'.do_shortcode($ac).'</div>';
    }
  }
else{return __( "WPWC Forms will be only displayed in live mode!", "wp-website-creator" );}
}//end wpwc shortcode

  function wpwc_get_css_class($formplugin)
  {
    if($formplugin=='wpforms')
    {
      $this_is_my_domain_css = 'wpforms-field';
    }
    if($formplugin=='ninja')
    {
      $this_is_my_domain_css = 'ninja-forms-field';
    }
    if($formplugin=='formidable')
    {
      $this_is_my_domain_css = 'frm_form_field';
    }
    if($formplugin=='gravity')
    {
      $this_is_my_domain_css = 'gfield';
    }
    if($formplugin=='caldera')
    {
      $this_is_my_domain_css = 'form-control';
    }
    if($formplugin=='cf7')
    {
      $this_is_my_domain_css = 'wpcf7-form-control';
    }
    return $this_is_my_domain_css;
  }

  function domain_validate_fields($domaintype,$wpwc_s_map_maindomain,$thisformid,$formplugin)
  {
    if($domaintype!='tld')
    {
      return '
      <div class="row" style="clear:both;">
      <input type="hidden" value="'.$wpwc_s_map_maindomain.'" name="wpwc_cpanel_maindomain">
      <input type="hidden" value="'.$thisformid.'" name="wpwc_mappingid">
      <div class="wpwc_error_domainexist" style="padding-left:6px;display:none;" id="wpwc_cpanel_domain_exists">'.__( "This subdomain already exists. Please tip in a other prefix", "wp-website-creator" ).'</div>
      <div class="wpwc_form_valid" style="padding-left:6px;display:none;" id="wpwc_form_valid">'.__( "Please enter a valid domain prefix", "wp-website-creator" ).'</div>
      <div class="wpwc_form_valid" style="padding-left:6px;display:none;" id="wpwc_form_valid_test">'.__( "Please do not use the word \"test\". This word causes installation problems on many servers.", "wp-website-creator" ).'</div>
      <div class="wpwc_val_domainok" style="padding-left:6px;display:none;" id="wpwc_cpanel_domain_ok">'.__( "Great! This subdomain is free. You can install now.", "wp-website-creator" ).'</div>
      <div class="wpwc_val_domainok" style="padding-left:6px;display:none;" id="wpwc_connectwarning">'.__( "Can't connect to the server. Please contact the site owner.", "wp-website-creator" ).'</div>
      </div>';
    }
    if($domaintype=='tld')
    {
      if($formplugin=='caldera'){$margin='margin-left:5px;';}
      return '
      <div class="row" style="'.$margin.'clear:both;">
      <input type="hidden" value="" name="wpwc_cpanel_maindomain">
      <input type="hidden" value="'.$thisformid.'" name="wpwc_mappingid">
      <div class="wpwc_error_domainexist" style="padding-left:6px;padding-left:6px;display:none;" id="wpwc_cpanel_domain_exists">
      '.__( "This Domain is taken.<br>Please enter another domain or confirm that you are the owner of this domain", "wp-website-creator" ).'<br>
      <select class="'.wpwc_get_css_class($formplugin).'" id="wpwc_my_domain" name="wpwc_my_domain">
      <option value=""></option>
      <option value="1">'.__( "This is my domain", "wp-website-creator" ).'</option>
      </select>
      </div>
      <div class="wpwc_form_valid" style="display:none;" id="wpwc_form_valid">'.__( "Please enter a valid domain name", "wp-website-creator" ).'</div>
      <div class="wpwc_val_domainok" style="display:none;" id="wpwc_cpanel_domain_ok">'.__( "Great! This Domain is free. You can install now.", "wp-website-creator" ).'</div>
      </div>';
    }
  }

  function get_wpwc_domainextension_field($domaintype,$wpwc_s_map_maindomain,$formplugin,$thisformid)
  {
    if($domaintype!='tld')
    {

      if($formplugin=='wpforms')
        {
          return '
          <label class="wpforms-field-label">'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
            <div class="wpforms-field-row wpforms-field-large">
              <div class="wpforms-field-row-block wpforms-first wpforms-one-third">
                <input type="text" id="wpwc_cpanel_prefix" class="wpforms-field-name-first wpforms-field-required" name="wpwc_cpanel_prefix">
              </div>
              <div class="wpforms-one-third">
                <span class="wpwc_maindomain"> .'.$wpwc_s_map_maindomain.'</span>
              </div>
            </div>';
        }
        if($formplugin=='ninja')
        {
          return '
          <div class="nf-field">
           <div class="field-wrap textbox-wrap">
            <div class="nf-field-label">
              <label>'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
            </div>
            <div class="nf-field-element">
              <input type="text" value="" class="ninja-forms-field nf-element" id="wpwc_cpanel_prefix" name="wpwc_cpanel_prefix" aria-invalid="true"><span class="wpwc_maindomain"> .'.$wpwc_s_map_maindomain.'</span>
            </div>
           </div>
          </div>';
        }
        if($formplugin=='formidable')
        {
          return '
          <div class="frm_form_field form-field frm_top_container frm_third frm_first">
            <label class="frm_primary_label">'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
            <input type="text" id="wpwc_cpanel_prefix" name="wpwc_cpanel_prefix" value="">
          </div>
          <div class="frm_form_field form-field  frm_required_field frm_hidden_container frm_third">
            <label class="frm_primary_label">'.__( "Maindomain", "wp-website-creator" ).'</label>
            <span class="wpwc_maindomain"> .'.$wpwc_s_map_maindomain.'</span>
          </div>';
        }
        if($formplugin=='gravity')
        {
          return '
          <li class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label">'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
              <div class="ginput_container ginput_container_text">
                <input name="wpwc_cpanel_prefix" id="wpwc_cpanel_prefix" minlength="3" type="text" value="" class="medium" tabindex="1" aria-invalid="false">
                <span class="wpwc_maindomain"> .'.$wpwc_s_map_maindomain.'</span>
              </div>
          </li>';
        }
        if($formplugin=='caldera')
        {
          return '
          <label class="control-label">'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
          <div class="form-group">
             <div class="col-sm-4">
               <input type="text" class="form-control" name="wpwc_cpanel_prefix" minlength="3" id="wpwc_cpanel_prefix" value="" data-type="text">
             </div>
             <div class="col-sm-4">
               <span class="wpwc_maindomain"> .'.$wpwc_s_map_maindomain.'</span>
             </div>
          </div>';
        }
        if($formplugin=='cf7')
        {
          return '
          <p>
            <label>'.__( "Tip in your prefix", "wp-website-creator" ).'<br>
              <span class="wpcf7-form-control-wrap">
                <input type="text" name="wpwc_cpanel_prefix" minlength="3" id="wpwc_cpanel_prefix" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false">
              </span>
              <span class="wpwc_maindomain"> .'.$wpwc_s_map_maindomain.'</span>
            </label>
          </p>';
        }
    }
    if($domaintype=='tld')
    {
      if($formplugin=='wpforms')
        {
          return '
          <label class="wpforms-field-label">'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
            <div class="wpforms-field-row wpforms-field-large">
              <div class="wpforms-field-row-block wpforms-first wpforms-one-third">
                <input type="text" id="wpwc_cpanel_prefix" class="wpforms-field-name-first wpforms-field-required" name="wpwc_cpanel_prefix">
              </div>
              <div class="wpforms-one-third">
                <select class="'.wpwc_get_css_class($formplugin).'" id="tld_to_check" name="tld_to_check">
                  '.get_wpwc_top_level_domains($thisformid).'
                </select>
              </div>
            </div>';
        }
        if($formplugin=='ninja')
        {
          return '
          <div class="nf-field">
           <div class="field-wrap textbox-wrap">
            <div class="nf-field-label">
              <label>'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
            </div>
            <div class="nf-field-element">
              <input style="width:45%;float:left;" type="text" value="" class="ninja-forms-field nf-element" id="wpwc_cpanel_prefix" name="wpwc_cpanel_prefix" aria-invalid="true">
              <select class="'.wpwc_get_css_class($formplugin).'" style="width:45%;float:right;" id="tld_to_check" name="tld_to_check">
                '.get_wpwc_top_level_domains($thisformid).'
              </select>
            </div>
           </div>
          </div>';
        }
        if($formplugin=='formidable')
        {
          return '
          <div class="frm_form_field form-field frm_top_container frm_third frm_first">
            <label class="frm_primary_label">'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
            <input type="text" id="wpwc_cpanel_prefix" name="wpwc_cpanel_prefix" value="">
          </div>
          <div class="frm_form_field form-field  frm_required_field frm_hidden_container frm_third">
            <label class="frm_primary_label">'.__( "Maindomain", "wp-website-creator" ).'</label>
            <select class="'.wpwc_get_css_class($formplugin).'" id="tld_to_check" name="tld_to_check">
              '.get_wpwc_top_level_domains($thisformid).'
            </select>
          </div>';
        }
        if($formplugin=='gravity')
        {
          return '
          <li class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label">'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
              <div class="ginput_container ginput_container_text">
                <input name="wpwc_cpanel_prefix" id="wpwc_cpanel_prefix" minlength="3" type="text" value="" class="medium" tabindex="1" aria-invalid="false">
                <select class="'.wpwc_get_css_class($formplugin).'" id="tld_to_check" name="tld_to_check">
                  '.get_wpwc_top_level_domains($thisformid).'
                </select>
              </div>
          </li>';
        }
        if($formplugin=='caldera')
        {
          return '
          <label class="control-label">'.__( "Tip in your prefix", "wp-website-creator" ).'</label>
          <div class="form-group">
             <div class="col-sm-4">
               <input type="text" class="form-control" name="wpwc_cpanel_prefix" minlength="3" id="wpwc_cpanel_prefix" value="" data-type="text">
             </div>
             <div class="col-sm-4">
             <select class="'.wpwc_get_css_class($formplugin).'" id="tld_to_check" name="tld_to_check">
               '.get_wpwc_top_level_domains($thisformid).'
             </select>
             </div>
          </div>';
        }
        if($formplugin=='cf7')
        {
          return '
          <p>
            <label>'.__( "Tip in your prefix", "wp-website-creator" ).'<br>
              <span class="wpcf7-form-control-wrap">
                <input type="text" name="wpwc_cpanel_prefix" minlength="3" id="wpwc_cpanel_prefix" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false">
              </span>
              <select class="'.wpwc_get_css_class($formplugin).'" id="tld_to_check" name="tld_to_check">
                '.get_wpwc_top_level_domains($thisformid).'
              </select>
            </label>
          </p>';
        }
    }
  }

function get_wpwc_top_level_domains($thisformid)
{
  $wpwc_s_map_domainextensions = get_post_meta($thisformid,'wpwc_s_map_domainextensions',true);
  $wpwc_s_map_domainextensions_explo = explode(',',$wpwc_s_map_domainextensions);
  foreach ($wpwc_s_map_domainextensions_explo as $key => $val)
    {
        $domainoptions .= '<option value="'.$val.'">'.$val.'</option>';
    }
  return $domainoptions;
}
function get_wpwc_credentials_email($email_template_id,$wpwc_website)
{
  global $wpdb;
  global $post;
  $post_content = get_post($email_template_id);
  $email_content = $post_content->post_content;

  $email_content_admin = get_post_meta($email_template_id,'wpwc_admin_info',true);

  $email_content = apply_filters('the_content', $email_content);
  $email_content = str_replace(']]>', ']]&gt;', $email_content);

  $email_content_admin = apply_filters('the_content', $email_content_admin);
  $email_content_admin = str_replace(']]>', ']]&gt;', $email_content_admin);

  $wpwc_website_metas = get_post_meta($wpwc_website);
  if($wpwc_website_metas)
  {
    foreach($wpwc_website_metas as $key => $val)
    {
      if($val != '')
      {
        $toreplace = str_replace('wpwc_','',$key);
        if($toreplace =='website_account_login_domain' or $toreplace == 'website_account_username' or $toreplace == 'website_account_password')
        {
          $toreplace = str_replace('website_','',$toreplace);
        }
        $toreplace = '#'.$toreplace.'#';
        $replacestring = $val[0];

        if($val[0]!='')
        {
          $email_content = str_replace($toreplace,$replacestring,$email_content);
          $email_content_admin = str_replace($toreplace,$replacestring,$email_content_admin);
        }

      }
    }
  }

  $emailsubject = get_post_meta($email_template_id,'wpwc_email_subject',true);
  $emailsender = get_post_meta($email_template_id,'wpwc_sender_email',true);
  $emailsendername = get_post_meta($email_template_id,'wpwc_sender_name',true);

  return array('emailsubject' => $emailsubject,'emailsender' => $emailsender,'emailsendername' => $emailsendername,'email_content' => $email_content,'email_content_admin' => $email_content_admin);
}

  function check_some_other_plugin()
  {
    if ( is_plugin_active('wp-website-creator/wp-website-creator.php') )
    {

      if ( !is_plugin_active( 'ninja-forms/ninja-forms.php' ) and !is_plugin_active( 'formidable/formidable.php' ) and !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) and !is_plugin_active( 'caldera-forms/caldera-forms.php' ) and !is_plugin_active( 'caldera-forms/caldera-core.php' ) and !is_plugin_active( 'gravityforms/gravityforms.php' ) and !function_exists( 'wpforms' ))
      {
        add_action( 'admin_notices', 'wpwc_formplugin_needed' );
      }

    }
  }
  add_action( 'admin_init', 'check_some_other_plugin' );

  function wpwc_is_valid_domain($url)
  {

        if (filter_var('http://'.$url, FILTER_VALIDATE_URL)) {
          return '1';
        } else {
          return '0';
        }
  }


  function wpwc_formplugin_needed() {
      ?>
      <div class="error notice">
        <p><b><?php echo __( "To create websites you need to install one of the following free plugins", "wp-website-creator" );?></b></p>
        <a target="_blank" href="https://wordpress.org/plugins/ninja-forms/">Ninja Forms Free</a>
        <br><a target="_blank" href="https://wordpress.org/plugins/wpforms-lite/">WPForms Lite</a>
        <br><a target="_blank" href="https://wordpress.org/plugins/formidable/">Formidable Free</a>
        <br><a target="_blank" href="https://wordpress.org/plugins/caldera-forms/">Caldera Free</a>
        <br><a target="_blank" href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a>
        <p><b>Not free</b></p>
        <a target="_blank" href="https://www.gravityforms.com/">Gravity</a>
      </div>
      <?php
  }?>
