<?php
add_action('admin_head', 'wpwc_admin_header_function');
function wpwc_admin_header_function()
{
$current_screen = get_current_screen();

if($current_screen->id == 'wpwc_mappings' && $current_screen->post_type == 'wpwc_mappings')
{

  global $wpdb;
  global $post;
  $array_design = array();
  $array_design_2 = array();
  $custom = get_post_custom($post->ID);

  $wpwc_map_source = $custom["wpwc_map_source"][0];
	$formplugin_ex = explode('_',$wpwc_map_source);
	$formplugin = $formplugin_ex[1];
	$formularid = $formplugin_ex[2];

if($_POST['wpwc_map_designs'])
{

  //We first grab the designs already in use
  $designsarray = get_post_meta($post->ID,'wpwc_map_all_designs_'.$formularid);

  foreach($designsarray as $key=>$val)
  {
    foreach($val as $key2=>$val2)
    {
      $designid = $val2['id'];
      if($designid > '1')
      {
        if($_POST['wpwc_map_design_'.$designid] == 'yes')
        {
          $design_small = $_POST["wpwc_design_small_".$designid];
          $design_medium = $_POST["wpwc_design_medium_".$designid];
          $design_large = $_POST["wpwc_design_large_".$designid];
          $design_demourl = $_POST["wpwc_design_demourl_".$designid];

          $array_design1 = array('id'=>$designid,'small'=>$design_small,'medium'=>$design_medium,'large'=>$design_large,'design_demourl'=>$design_demourl);
          array_push($array_design,$array_design1);

          $arrayd_2 = array('id'=>$designid,'pos'=>'0');
          array_push($array_design_2,$arrayd_2);

          update_post_meta($post->ID, "wpwc_map_".$formularid."_designs_".$designid, '1');
          update_post_meta($post->ID, "wpwc_map_".$formularid."_design_values_".$designid, $array_design);
          $array_design = array();
        }
      }
    }
  }

  //Then add the new imported
  foreach($_POST['wpwc_map_designs'] as $save_design=>$desid)
    {

      $des = $_POST['wpwc_map_design_'.$desid];
      $alreadyselected = get_post_meta($post->ID, "wpwc_map_".$formularid."_designs_".$desid, true);

      if($des=='yes' && $alreadyselected != '1')
      {
        $design_small = $_POST["wpwc_design_small_".$desid];
        $design_medium = $_POST["wpwc_design_medium_".$desid];
        $design_large = $_POST["wpwc_design_large_".$desid];
        $design_demourl = $_POST["wpwc_design_demourl_".$desid];

        $array_design1 = array('id'=>$desid,'small'=>$design_small,'medium'=>$design_medium,'large'=>$design_large,'design_demourl'=>$design_demourl);
        array_push($array_design,$array_design1);

        $arrayd_2 = array('id'=>$desid,'pos'=>'0');
        array_push($array_design_2,$arrayd_2);

        update_post_meta($post->ID, "wpwc_map_".$formularid."_designs_".$desid, '1');
        update_post_meta($post->ID, "wpwc_map_".$formularid."_design_values_".$desid, $array_design);
        $array_design = array();
      }
      else if($des=='no')
      {
        $delpost = 'wpwc_map_'.$formularid.'_design_values_'.$desid;
        delete_post_meta($post->ID,$delpost);
        $delpost = 'wpwc_map_'.$formularid.'_designs_'.$desid;
        delete_post_meta($post->ID,$delpost);
      }

    }
    update_post_meta($post->ID, "wpwc_admin_update_info", '1');
    update_post_meta($post->ID, "wpwc_map_all_designs_".$formularid, $array_design_2);

  }//End if post wpwc_map_designs


  $thisdesigns = wpwc_get_themes();
  $importdesigns = $thisdesigns['themeimporter'];
  $membership = $thisdesigns['membership'];
  if($membership!='Big Plan'){$bigplanlink='<a target="_blank" style="float:right;" href="https://wp-website-creator.com">Offer your own prebuilt websites with a BIG PLAN membership</a><br>';}


  echo '<!-- This is the modal -->
  <div id="modal-designimport" uk-modal>
  <div class="uk-modal-dialog uk-modal-body">
      <h2 style="text-align:center;font-size:30px;">Theme importer</h2>
      '.$bigplanlink.'
      <form action="" method="post">
      <button type="submit" class="designsimortierenbutton" type="button">Import</button>
      <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>
      '.$importdesigns.'
      <button type="submit" class="designsimortierenbutton" type="button">Import</button>
      <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>
      <input type="hidden" name="save_themes" value="1">
      </form>

  </div>
  </div>';

  if ( is_plugin_active( 'ninja-forms/ninja-forms.php' ) )
  {
    $vidtitle = 'Ninja forms';
    $vid = 'https://www.youtube.com/embed/21RfhBakuis';
  }

  if (is_plugin_active( 'formidable/formidable.php' ) )
  {
    $vidtitle = 'Formidable';
    $vid = 'https://www.youtube.com/embed/34vg6qib1Ug';
  }

  if (is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) )
  {
    $vidtitle = 'Contact Form 7';
    $vid = 'https://www.youtube.com/embed/SFNDS2kFzX0';
  }

  if (is_plugin_active( 'caldera-forms/caldera-core.php' ) )
  {
    $vidtitle = 'Caldera forms';
    $vid = 'https://www.youtube.com/embed/SggtLel1D3c';
  }

  if (is_plugin_active( 'gravityforms/gravityforms.php' ))
  {
    $vidtitle = 'Gravity forms';
    $vid = 'https://www.youtube.com/embed/5Vaa7q8YNpc';
  }

  if (function_exists( 'wpforms' ))
  {
    $vidtitle = 'WPForms';
    $vid = 'https://www.youtube.com/embed/vz0Pp9UVKMY';
  }

    echo '<!-- This is the modal -->
    <div id="modal-vid" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 style="text-align:center;font-size:30px;">Create websites with '.$vidtitle.'</h2>
        <div style="margin-bottom:20px;" class="responsive-video">
          <iframe width="100%" src="'.$vid.'" frameborder="0" allowfullscreen></iframe>
        </div>
        <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>
    </div>
    </div>';

    echo '<!-- This is the modal -->
    <div id="modal-woo" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 style="text-align:center;font-size:30px;">Relate website creation to a woocommerce product</h2>
        <div style="margin-bottom:20px;" class="responsive-video">
          <iframe width="100%" src="https://www.youtube.com/embed/OQlcE6YH0J4" frameborder="0" allowfullscreen></iframe>
        </div>
        <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>
    </div>
    </div>';

    echo '<!-- This is the modal -->
    <div id="modal-decide" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 style="text-align:center;font-size:30px;">Decide where to install the websites</h2>
        <div style="margin-bottom:20px;" class="responsive-video">
          <iframe width="100%" src="https://www.youtube.com/embed/puUXiRuF_No" frameborder="0" allowfullscreen></iframe>
        </div>
        <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>
    </div>
    </div>';

    echo '<!-- This is the modal -->
    <div id="modal-customercpanel" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 style="text-align:center;font-size:30px;">Install websites directly on customers cPanel</h2>
        <div style="margin-bottom:20px;" class="responsive-video">
          <iframe width="100%" src="https://www.youtube.com/embed/Q17km_vHjME" frameborder="0" allowfullscreen></iframe>
        </div>
        <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>
    </div>
    </div>';

    echo '<!-- This is the modal -->
    <div id="modal-language" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 style="text-align:center;font-size:30px;">Language field</h2>
        <p>If you use this field, the main settings for the language will be overridden and the value of this field will be used.</p>
        <div style="margin-bottom:20px;">
        <h3 style="font-size:20px;">Use these language codes in your value fields</h3>

        en_EN -> English<br>
        ru_RU -> Русский<br>
        de_DE -> German<br>
        es_ES -> Español<br>
        fr_FR -> Français<br>
        ja -> Japanese<br>
        it_IT -> Italiano<br>
        nl_NL -> Nederlands<br>
        pl_PL -> Polish<br>
        pt_PT -> Português<br>
        sv_SE -> Swedish<br>
        tr_TR -> Turkish<br>
        zh_CN -> Chinese<br>
        cs_CZ -> Czech<br>
        he_IL -> Hebrew<br>
        hi -> Indian<br>
        in -> Indonesian<br>
        el -> Greek<br>
        ar -> Arabisch<br>
        af -> Afrikaans<br>
        ko_KR -> Korean<br>
        </div>
        <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>
    </div>
    </div>';



}//End

if($current_screen->id == 'wpwc_email')
{
  global $wpdb;
  global $post;


  $jqueryservercode = "
  <script>
  jQuery(document).ready(function($){

    jQuery('#wpwc_btn_sendemail').click( function(e){
      e.preventDefault();

      $.post( '".plugin_dir_url( __FILE__ )."wpwc_testemail.php', jQuery('#wpwc_resend_email').serialize(), function( json ){

        if( json.success ){
          if(json.data.sent!='false'){
            jQuery( '#wpwcemailwarning' ).hide( 'slow' );
            jQuery( '#wpwcemailok' ).show( 'slow' );
          }else{
            jQuery( '#wpwcemailwarning' ).show( 'slow' );
          }
        }
        else
          jQuery( '#wpwcemailwarning' ).show( 'slow' );

      });
    });

  });
  </script>";

  echo $jqueryservercode.'<!-- This is the modal -->
  <div id="modal-testemail" uk-modal>
  <div class="uk-modal-dialog uk-modal-body">
      <h2 style="text-align:center;font-size:30px;">Send credentials test mail</h2>

      <form id="wpwc_resend_email" action="" method="post">
      <br><label>email recipient</label><br>
      <input style="min-width:100%;" type="text" name="email" value="">

      <button id="wpwc_btn_sendemail" type="submit" class="designsimortierenbutton" type="button">Send email</button>

      <input type="hidden" value="'.$post->ID.'" name="id">
      <input type="hidden" value="1" name="test">
      <div id="wpwcemailok" style="display:none;"><h3 style="color:green;">Email was sent successfully.</h3></div>
      <div id="wpwcemailwarning" style="display:none;"><h3 style="color:red;">Can\'t send the email. Please check all settings.</h3></div>

      </form>
      <p>
      <h3>We use these dataset to replace the placeholders in your test email</h3>
#website_domain# -> https://example.com<br>
#website_login_domain# -> https://example.com/wp-admin<br>

#website_salutation# -> Mr.<br>
#website_first_name# -> John<br>
#website_last_name# -> Doe<br>

#website_username# -> WP Username<br>
#website_password# -> WP Password<br>
#website_user_email# -> WP Email<br>
#website_user_role# -> WP Role<br>

#website_admin_username# -> Admin Username<br>
#website_admin_passord# ->Admin Password<br>

#account_login_domain# -> Server account login domain<br>
#account_username# -> Server account login username<br>
#account_password# -> Server account login password<br>
#account_ftp_host# -> FTP host<br>
#account_ftp_username# -> FTP username<br>
#account_ftp_password# -> FTP password<br>

#support_videotutorials# -> https://videotutorials.example.com<br>
#support_paymentpage# -> https://payment.example.com<br>
      </p>
      <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>

  </div>
  </div>';

  echo '<!-- This is the modal -->
  <div id="modal-emailtemplate" uk-modal>
  <div class="uk-modal-dialog uk-modal-body">
      <h2 style="text-align:center;font-size:30px;">Create and customize a Email templates</h2>
      <div style="margin-bottom:20px;" class="responsive-video">
        <iframe width="100%" src="https://www.youtube.com/embed/Auxv4UIpY3I" frameborder="0" allowfullscreen></iframe>
      </div>
      <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>
  </div>
  </div>';
}

if($current_screen->id == 'wpwc_websites')
{
  global $wpdb;
  global $post;

  $wpwc_email_template_id = get_post_meta($post->ID,'wpwc_email_template_id',true);
  $wpwc_website_user_email = get_post_meta($post->ID,'wpwc_website_user_email',true);

  $wpwc_email_data = get_wpwc_credentials_email($wpwc_email_template_id,$post->ID);

  $emailsubject = $wpwc_email_data['emailsubject'];
  $emailsender =  $wpwc_email_data['emailsender'];
  $emailsendername =  $wpwc_email_data['emailsendername'];
  $email_content =  $wpwc_email_data['email_content'];


  $jqueryservercode = "
  <script>
  jQuery(document).ready(function($){

    jQuery('#wpwc_btn_resendemail').click( function(e){
      e.preventDefault();

      $.post( '".plugin_dir_url( __FILE__ )."wpwc_testemail.php', jQuery('#wpwc_resend_email').serialize(), function( json ){

        if( json.success ){
          if(json.data.sent!='false'){
            jQuery( '#wpwcemailwarning' ).hide( 'slow' );
            jQuery( '#wpwcemailok' ).show( 'slow' );
          }else{
            jQuery( '#wpwcemailwarning' ).show( 'slow' );
          }
        }
        else
          jQuery( '#wpwcemailwarning' ).show( 'slow' );

      });
    });

  });
  </script>";

  echo $jqueryservercode.'<!-- This is the modal -->
  <div id="modal-resendmail" uk-modal>
  <div class="uk-modal-dialog uk-modal-body">
      <h2 style="text-align:center;font-size:30px;">Resend email</h2>

      <form id="wpwc_resend_email" action="" method="post">
      <br><label>email recipient</label><br>
      <input style="min-width:100%;" type="text" name="email" value="'.$wpwc_website_user_email.'">
      <input type="hidden" value="'.$post->ID.'" name="id">
      <div id="wpwcemailok" style="display:none;"><h3 style="color:green;">Email was sent successfully.</h3></div>
      <div id="wpwcemailwarning" style="display:none;"><h3 style="color:red;">Can\'t send the email. Please check all settings.</h3></div>
      <button id="wpwc_btn_resendemail" class="designsimortierenbutton" >Send email</button>
      </form>
      <p>

      Sender name: '.$emailsendername.'<br>Subject: '.$emailsubject.'<br>Sender Email: '.$emailsender.'<br><h3>Email preview</h3><br>'.$email_content.'</p>

      <button class="modalclosebutton uk-modal-close" style="float:right;" type="button">Close modal</button>

  </div>
  </div>';
}

}//End if postty
?>
